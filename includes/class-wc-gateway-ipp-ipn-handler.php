<?php

/**
 * Handles responses from IPPayware IPN.
 *
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once dirname(__FILE__) . '/class-wc-gateway-ipp-response.php';

/**
 * WC_Gateway_IPP_IPN_Handler class.
 */
class WC_Gateway_IPP_IPN_Handler extends WC_Gateway_IPP_Response
{

    /**
     * API Key to validate.
     *
     * @var string API Key.
     */
    protected $api_key;

    /**
     * API Secret to validate.
     *
     * @var string API Key.
     */
    protected $api_secret;

    protected $approved_status;

    /**
     * Constructor.
     *
     * @param string $api_key API Key to receive IPN from.
     * @param string $receiver_email API Secret to receive IPN from.
     */
    public function __construct($api_key, $api_secret, $approved_status)
    {
        add_action('woocommerce_api_wc_gateway_ipp', array($this, 'check_response'));
        add_action('valid-ipp-standard-ipn-request', array($this, 'valid_response'));

        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        $this->approved_status = $approved_status;
        // WC_Gateway_IPP::log('IPN Constructor: ' . wc_print_r(wp_unslash($_POST), true));
    }

    /**
     * Check for IPPayware IPN Response.
     */
    public function check_response()
    {
        if (!empty($_POST) && $this->validate_ipn()) { // WPCS: CSRF ok.
            $posted = wp_unslash($_POST); // WPCS: CSRF ok, input var ok.

            // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
            do_action('valid-ipp-standard-ipn-request', $posted);
            // exit;
            return;
        }

        wp_die('IPPayware IPN Request Failure', 'IPPayware IPN', array('response' => 500));
    }

    /**
     * There was a valid response.
     *
     * @param  array $posted Post data after wp_unslash.
     */
    public function valid_response($posted)
    {
        // TODO
        WC_Gateway_IPP::log('IPN Valid Response: ' . wc_print_r($posted, true));
        $order = !empty($posted['custom']) ? $this->get_ipp_order($posted['custom']) : false;

        if ($order) {

            // Lowercase returned variables.
            $posted['payment_status'] = strtolower($posted['payment_status']);

            WC_Gateway_IPP::log('Found order #' . $order->get_id());
            WC_Gateway_IPP::log('Payment status: ' . $posted['payment_status']);

            if (method_exists($this, 'payment_status_' . $posted['payment_status'])) {
                call_user_func(array($this, 'payment_status_' . $posted['payment_status']), $order, $posted);
            }
        }
    }

    /**
     * Check IPPayware IPN validity.
     */
    public function validate_ipn()
    {
        WC_Gateway_IPP::log('Checking IPN response is valid');

        // Get received values from post data.
        $validate_ipn        = wp_unslash($_POST); // WPCS: CSRF ok, input var ok.
        $validate_ipn['cmd'] = '_notify-validate';

        // Send back post vars to IPPayware.
        $params = array(
            'body'        => $validate_ipn,
            'timeout'     => 60,
            'httpversion' => '1.1',
            'compress'    => false,
            'decompress'  => false,
            'user-agent'  => 'WooCommerce/' . WC()->version,
        );

        // TODO
        WC_Gateway_IPP::log('IPN Request: ' . wc_print_r($params, true));
        // Post back to get a response.
        $response = wp_safe_remote_post(WC_Gateway_IPP::$CAPTURE_URL, $params);

        WC_Gateway_IPP::log('IPN Response: ' . wc_print_r($response, true));

        // Check to see if the request was valid.
        if (!is_wp_error($response) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr($response['body'], 'VERIFIED')) {
            WC_Gateway_IPP::log('Received valid response from IPPayware IPN');
            return true;
        }

        WC_Gateway_IPP::log('Received invalid response from IPPayware IPN');

        if (is_wp_error($response)) {
            WC_Gateway_IPP::log('Error response: ' . $response->get_error_message());
        }

        return false;
    }

    /**
     * Check for a valid transaction type.
     *
     * @param string $txn_type Transaction type.
     */
    protected function validate_transaction_type($txn_type)
    {
        $accepted_types = array('cart', 'instant', 'express_checkout', 'web_accept', 'masspay', 'send_money');

        if (!in_array(strtolower($txn_type), $accepted_types, true)) {
            WC_Gateway_IPP::log('Aborting, Invalid type:' . $txn_type);
            exit;
        }
    }

    /**
     * Check currency from IPN matches the order.
     *
     * @param WC_Order $order    Order object.
     * @param string   $currency Currency code.
     */
    protected function validate_currency($order, $currency)
    {
        if ($order->get_currency() !== $currency) {
            WC_Gateway_IPP::log('Payment error: Currencies do not match (sent "' . $order->get_currency() . '" | returned "' . $currency . '")');

            /* translators: %s: currency code. */
            $order->update_status('on-hold', sprintf(__('Validation error: IPPayware currencies do not match (code %s).', 'woocommerce'), $currency));
            exit;
        }
    }

    /**
     * Check payment amount from IPN matches the order.
     *
     * @param WC_Order $order  Order object.
     * @param int      $amount Amount to validate.
     */
    protected function validate_amount($order, $amount)
    {
        if (number_format($order->get_total(), 2, '.', '') !== number_format($amount, 2, '.', '')) {
            WC_Gateway_IPP::log('Payment error: Amounts do not match (gross ' . $amount . ')');

            /* translators: %s: Amount. */
            $order->update_status('on-hold', sprintf(__('Validation error: IPPayware amounts do not match (gross %s).', 'woocommerce'), $amount));
            exit;
        }
    }

    /**
     * Handle a completed payment.
     *
     * @param WC_Order $order  Order object.
     * @param array    $posted Posted data.
     */
    protected function payment_status_completed($order, $posted)
    {
        if ($order->has_status(wc_get_is_paid_statuses())) {
            WC_Gateway_IPP::log('Aborting, Order #' . $order->get_id() . ' is already complete.');
            exit;
        }

        $this->validate_transaction_type($posted['txn_type']);
        $this->validate_currency($order, $posted['mc_currency']);
        $this->validate_amount($order, $posted['mc_gross']);
        $this->save_ipp_meta_data($order, $posted);

        if ('completed' === $posted['payment_status']) {
            if ($order->has_status('cancelled')) {
                $this->payment_status_paid_cancelled_order($order, $posted);
            }

            if (!empty($posted['mc_fee'])) {
                $order->add_meta_data('IPPayware Transaction Fee', wc_clean($posted['mc_fee']));
            }

            $this->payment_complete($order, (!empty($posted['txn_id']) ? wc_clean($posted['txn_id']) : ''), __('IPN payment completed', 'woocommerce'));
            $order->update_status($this->approved_status, __('Status changed by IPP', 'woocommerce'));
        } else {
            if ('authorization' === $posted['pending_reason']) {
                $this->payment_on_hold($order, __('Payment authorized. Change payment status to processing or complete to capture funds.', 'woocommerce'));
            } else {
                /* translators: %s: pending reason. */
                $this->payment_on_hold($order, sprintf(__('Payment pending (%s).', 'woocommerce'), $posted['pending_reason']));
            }
        }
    }

    /**
     * Handle a pending payment.
     *
     * @param WC_Order $order  Order object.
     * @param array    $posted Posted data.
     */
    protected function payment_status_pending($order, $posted)
    {
        $this->payment_status_completed($order, $posted);
    }

    /**
     * Handle a failed payment.
     *
     * @param WC_Order $order  Order object.
     * @param array    $posted Posted data.
     */
    protected function payment_status_failed($order, $posted)
    {
        /* translators: %s: payment status. */
        $order->update_status('failed', sprintf(__('Payment %s via IPN.', 'woocommerce'), wc_clean($posted['payment_status'])));
    }

    /**
     * Handle a denied payment.
     *
     * @param WC_Order $order  Order object.
     * @param array    $posted Posted data.
     */
    protected function payment_status_denied($order, $posted)
    {
        $this->payment_status_failed($order, $posted);
    }

    /**
     * Handle an expired payment.
     *
     * @param WC_Order $order  Order object.
     * @param array    $posted Posted data.
     */
    protected function payment_status_expired($order, $posted)
    {
        $this->payment_status_failed($order, $posted);
    }

    /**
     * Handle a voided payment.
     *
     * @param WC_Order $order  Order object.
     * @param array    $posted Posted data.
     */
    protected function payment_status_voided($order, $posted)
    {
        $this->payment_status_failed($order, $posted);
    }

    /**
     * When a user cancelled order is marked paid.
     *
     * @param WC_Order $order  Order object.
     * @param array    $posted Posted data.
     */
    protected function payment_status_paid_cancelled_order($order, $posted)
    {
        // $this->send_ipn_email_notification(
        //     /* translators: %s: order link. */
        //     sprintf(__('Payment for cancelled order %s received', 'woocommerce'), '<a class="link" href="' . esc_url($order->get_edit_order_url()) . '">' . $order->get_order_number() . '</a>'),
        //     /* translators: %s: order ID. */
        //     sprintf(__('Order #%s has been marked paid by IPPayware IPN, but was previously cancelled. Admin handling required.', 'woocommerce'), $order->get_order_number())
        // );
    }

    /**
     * Handle a refunded order.
     *
     * @param WC_Order $order  Order object.
     * @param array    $posted Posted data.
     */
    protected function payment_status_refunded($order, $posted)
    {
        // Only handle full refunds, not partial.
        if ($order->get_total() === wc_format_decimal($posted['mc_gross'] * -1, wc_get_price_decimals())) {

            /* translators: %s: payment status. */
            $order->update_status('refunded', sprintf(__('Payment %s via IPN.', 'woocommerce'), strtolower($posted['payment_status'])));

            // $this->send_ipn_email_notification(
            //     /* translators: %s: order link. */
            //     sprintf(__('Payment for order %s refunded', 'woocommerce'), '<a class="link" href="' . esc_url($order->get_edit_order_url()) . '">' . $order->get_order_number() . '</a>'),
            //     /* translators: %1$s: order ID, %2$s: reason code. */
            //     sprintf(__('Order #%1$s has been marked as refunded - IPPayware reason code: %2$s', 'woocommerce'), $order->get_order_number(), $posted['reason_code'])
            // );
        }
    }

    /**
     * Handle a reversal.
     *
     * @param WC_Order $order  Order object.
     * @param array    $posted Posted data.
     */
    protected function payment_status_reversed($order, $posted)
    {
        /* translators: %s: payment status. */
        $order->update_status('on-hold', sprintf(__('Payment %s via IPN.', 'woocommerce'), wc_clean($posted['payment_status'])));

        // $this->send_ipn_email_notification(
        //     /* translators: %s: order link. */
        //     sprintf(__('Payment for order %s reversed', 'woocommerce'), '<a class="link" href="' . esc_url($order->get_edit_order_url()) . '">' . $order->get_order_number() . '</a>'),
        //     /* translators: %1$s: order ID, %2$s: reason code. */
        //     sprintf(__('Order #%1$s has been marked on-hold due to a reversal - IPPayware reason code: %2$s', 'woocommerce'), $order->get_order_number(), wc_clean($posted['reason_code']))
        // );
    }

    /**
     * Handle a cancelled reversal.
     *
     * @param WC_Order $order  Order object.
     * @param array    $posted Posted data.
     */
    protected function payment_status_canceled_reversal($order, $posted)
    {
        // $this->send_ipn_email_notification(
        //     /* translators: %s: order link. */
        //     sprintf(__('Reversal cancelled for order #%s', 'woocommerce'), $order->get_order_number()),
        //     /* translators: %1$s: order ID, %2$s: order link. */
        //     sprintf(__('Order #%1$s has had a reversal cancelled. Please check the status of payment and update the order status accordingly here: %2$s', 'woocommerce'), $order->get_order_number(), esc_url($order->get_edit_order_url()))
        // );
    }

    /**
     * Save important data from the IPN to the order.
     *
     * @param WC_Order $order  Order object.
     * @param array    $posted Posted data.
     */
    protected function save_ipp_meta_data($order, $posted)
    {
        if (!empty($posted['payment_type'])) {
            update_post_meta($order->get_id(), 'Payment type', wc_clean($posted['payment_type']));
        }
        if (!empty($posted['txn_id'])) {
            update_post_meta($order->get_id(), '_transaction_id', wc_clean($posted['txn_id']));
        }
        if (!empty($posted['payment_status'])) {
            update_post_meta($order->get_id(), '_ipp_status', wc_clean($posted['payment_status']));
        }
    }
}
