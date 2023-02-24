<?php

/**
 * Class WC_Gateway_IPP_API_Handler file.
 */

defined('ABSPATH') || exit;

/**
 * Handles Refunds and other API requests such as capture.
 *
 * @since 1.0.0
 */
class WC_Gateway_IPP_API_Handler
{
    /**
     * API Key
     *
     * @var string
     */
    public static $api_key;

    /**
     * API Secret
     *
     * @var string
     */
    public static $api_secret;

    /**
     * Get capture request args.
     *
     * @param  WC_Order $order Order object.
     * @param  float    $amount Amount.
     * @return array
     */
    public static function get_capture_request($order, $amount = null)
    {
        $request = array(
            'KEY'             => self::$api_key,
            'SECRET'          => self::$api_secret,
            'METHOD'          => 'DoCapture',
            'AUTHORIZATIONID' => $order->_ipp_transaction_id,
            'AMT'             => number_format(is_null($amount) ? $order->total: $amount, 2, '.', ''),
            'CURRENCYCODE'    => "USD",
            'COMPLETETYPE'    => 'Complete'
        );
        return apply_filters('woocommerce_ipp_capture_request', $request, $order, $amount);
    }

    /**
     * Get refund request args.
     *
     * @param  WC_Order $order Order object.
     * @param  float    $amount Refund amount.
     * @param  string   $reason Refund reason.
     * @return array
     */
    public static function get_refund_request($order, $amount = null, $reason = '')
    {
        $request = array(
            'KEY'           => self::$api_key,
            'SECRET'        => self::$api_secret,
            'METHOD'        => 'RefundTransaction',
            'TRANSACTIONID' => $order->get_transaction_id,
            'NOTE'          => html_entity_decode(wc_trim_string($reason, 255), ENT_NOQUOTES, 'UTF-8'),
            'REFUNDTYPE'    => 'Full',
        );
        if (!is_null($amount)) {
            $request['AMT']          = number_format($amount, 2, '.', '');
            $request['CURRENCYCODE'] = $order->get_currency();
            $request['REFUNDTYPE']   = 'Partial';
        }
        return apply_filters('woocommerce_ipp_refund_request', $request, $order, $amount, $reason);
    }

    /**
     * Capture an authorization.
     *
     * @param  WC_Order $order Order object.
     * @param  float    $amount Amount.
     * @return object Either an object of name value pairs for a success, or a WP_ERROR object.
     */
    public static function do_capture($order, $amount = null)
    {
        $plugin_data = get_plugin_data('scw-table-booking-pro.php');
       
        // TODO
        $request = [
            'method'      => 'POST',
            'body'        => self::get_capture_request($order, $amount),
            'timeout'     => $plugin_data['Version'],
            'user-agent'  => 'Ippayware/' . WC()->version,
            'httpversion' => '1.1',
        ];
        WC_Gateway_IPP::log('DoCapture Request: ' . wc_print_r($request, true));
        $raw_response = wp_safe_remote_post(
            WC_Gateway_IPP::$PAYMENT_URL,
            $request
        );

        WC_Gateway_IPP::log('DoCapture Response: ' . wc_print_r($raw_response, true));

        if (is_wp_error($raw_response)) {
            return $raw_response;
        } elseif (empty($raw_response['body'])) {
            return new WP_Error('ipp-api', 'Empty Response');
        }

        parse_str($raw_response['body'], $response);

        return (object) $response;
    }

    /**
     * Refund an order via IPPayware.
     *
     * @param  WC_Order $order Order object.
     * @param  float    $amount Refund amount.
     * @param  string   $reason Refund reason.
     * @return object Either an object of name value pairs for a success, or a WP_ERROR object.
     */
    public static function refund_transaction($order, $amount = null, $reason = '')
    {
        // TODO
        $request = [
            'method'      => 'POST',
            'body'        => self::get_refund_request($order, $amount, $reason),
            'timeout'     => 70,
            'user-agent'  => 'WooCommerce/' . WC()->version,
            'httpversion' => '1.1',
        ];
        WC_Gateway_IPP::log('Refund Request: ' . wc_print_r($request, true));
        $raw_response = wp_safe_remote_post(
            WC_Gateway_IPP::$PAYMENT_URL,
            $request
        );

        WC_Gateway_IPP::log('Refund Response: ' . wc_print_r($raw_response, true));

        if (is_wp_error($raw_response)) {
            return $raw_response;
        } elseif (empty($raw_response['body'])) {
            return new WP_Error('ipp-api', 'Empty Response');
        }

        parse_str($raw_response['body'], $response);

        return (object) $response;
    }
}

/**
 * Here for backwards compatibility.
 *
 * @since 3.0.0
 */
class WC_Gateway_IPP_Refund extends WC_Gateway_IPP_API_Handler
{
    /**
     * Get refund request args. Proxy to WC_Gateway_IPP_API_Handler::get_refund_request().
     *
     * @param WC_Order $order Order object.
     * @param float    $amount Refund amount.
     * @param string   $reason Refund reason.
     *
     * @return array
     */
    public static function get_request($order, $amount = null, $reason = '')
    {
        return self::get_refund_request($order, $amount, $reason);
    }

    /**
     * Process an order refund.
     *
     * @param  WC_Order $order Order object.
     * @param  float    $amount Refund amount.
     * @param  string   $reason Refund reason.
     * @return object Either an object of name value pairs for a success, or a WP_ERROR object.
     */
    public static function refund_order($order, $amount = null, $reason = '')
    {
        $result = self::refund_transaction($order, $amount, $reason);
        if (is_wp_error($result)) {
            return $result;
        } else {
            return (array) $result;
        }
    }
}
