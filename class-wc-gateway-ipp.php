<?php

/**
 * IPP Payment Gateway
 * 
 * Provides a IPPayware Payment Gateway
 * 
 * @class WC_Gateway_IPP
 * @extends WC_Payment_Gateway
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * WC_Gateway_IPP Class.
 */
class WC_Gateway_IPP 
{
    /**
     * Logger instance
     *
     * @var WC_Logger
     */
    public static $log = false;

    /**
     * Endpoint for requests to IPPayware.
     *
     * @var string
     */
    public static $REQUEST_URL;
    public static $CAPTURE_URL;
    public static $TRANSACTION_URL;
    public static $PAYMENT_URL;

    public static $NOTIFY_URL;

    public $id;
    public $has_fields;
    
    public $order_button_text;

    public $method_title;

    public $method_description;

    public $supports;

    public $title;

    public $description;

    public $api_key;

    public $api_secret;

    public $approved_status;

    public $enabled;

    public $view_transaction_url;

    /**
     * Constructor for the gateway.
     */
    public function __construct($url='')
    {
        // Load Constants
        $env = file(dirname(__FILE__) . '/.env', FILE_IGNORE_NEW_LINES);
        self::$REQUEST_URL = $env[0];
        self::$CAPTURE_URL = $env[1];
        self::$TRANSACTION_URL = $env[2];
        self::$PAYMENT_URL = $env[3];
        self::$NOTIFY_URL = $url."/helper.php";
        $this->id = 'ipp';
        $this->has_fields = false;
        $this->order_button_text = __('Proceed to IPPayware', 'scwatbwsr-translate');
        $this->method_title = __('IPPayware Standard', 'scwatbwsr-translate');
        $this->method_description = __('IPPayware Standard redirects customers to IPPayware to process their payment.', 'scwatbwsr-translate');
        $this->supports = [
            'products',
            'refunds'
        ];

        $options = get_option( 'scwatbwsr_settings' );

        // Define user set variables.
        $this->title = 'Ipp Payware';
        $this->description = 'Ipp Payware';
        $this->api_key = $options['api_key'];
        $this->api_secret = $options['api_secret'];
        $this->approved_status = ['Completed','Process','Pending'];
        $this->enabled = $options['enabled_payment'];

       
            include_once dirname(__FILE__) . '/includes/class-wc-gateway-ipp-ipn-handler.php';
            new WC_Gateway_IPP_IPN_Handler($this->api_key, $this->api_secret, $this->approved_status);
        
     
    }

    /**
     * Return whether or not this gateway still requires setup to function.
     *
     * When this gateway is toggled on via AJAX, if this returns true a
     * redirect will occur to the settings page instead.
     *
     * @since 1.0.0
     * @return bool
     */
    public function needs_setup()
    {
        return !is_string($this->api_key) || !is_string($this->api_secret);
    }

    /**
     * Get gateway icon.
     *
     * @return string
     */
    public function get_icon()
    {
        $icon_html = '<img src="https://ippayware.com/images/nav-logo.png" alt="' . esc_attr__('IPPayware acceptance mark', 'woocommerce') . '" />';
        $icon_html .= sprintf('<a href="%1$s" class="about_ipp" onclick="javascript:window.open(\'%1$s\',\'IPPayware\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700\'); return false;">' . esc_attr__('What is IPPayware?', 'woocommerce') . '</a>', esc_url('https://ippayware.com'));
        return apply_filters('woocommerce_gateway_icon', $icon_html, $this->id);
    }

    /**
     * Get the transaction URL.
     *
     * @param  WC_Order $order Order object.
     * @return string
     */
    public function get_transaction_url($order)
    {
        // TODO
        $this->view_transaction_url = self::$TRANSACTION_URL . '?cmd=_view-a-trans&id=%s';
        return $this->view_transaction_url;
    }

    /**
     * Process the payment and return the result.
     *
     * @param  int $order_id Order ID.
     * @return array
     */
    public function process_payment($order_id)
    {
        include_once dirname(__FILE__) . '/includes/class-wc-gateway-ipp-request.php';
        include_once dirname(__FILE__) . '/includes/settings-ipp.php';
        $order          = $this->scw_get_order($order_id);
        $ipp_request = new WC_Gateway_IPP_Request($this);
        $redirect =  $ipp_request->get_request_url($order);
        orderUpdate($order_id,["_ipp_payment_url"=>$redirect]);
        return array(
            'result'   => 'success',
            'redirect' => $redirect,
        );
    }
    /**
     * Get Order
     */
    public function scw_get_order($order_id)
    {
        global $wpdb;
		$table_name = $wpdb->prefix . 'scwatbwsr_orders';
        $getdtSql = $wpdb->prepare("SELECT * from {$table_name} where id = %d", $order_id);
	    $order = $wpdb->get_row($getdtSql);
        return  $order;
    }
    /**
     * Can the order be refunded via IPPayware?
     *
     * @param  WC_Order $order Order object.
     * @return bool
     */
    public function can_refund_order($order)
    {
        $has_api_creds = false;
        $has_api_creds = $this->api_key && $this->api_secret;
        return $order && $order->get_transaction_id() && $has_api_creds;
    }

    /**
     * Process a refund if supported.
     *
     * @param  int    $order_id Order ID.
     * @param  float  $amount Refund amount.
     * @param  string $reason Refund reason.
     * @return bool|WP_Error
     */
    public function process_refund($order_id, $amount = null, $reason = '')
    {
        $order = wc_get_order($order_id);

        if (!$this->can_refund_order($order)) {
            return new WP_Error('error', __('Refund failed.', 'woocommerce'));
        }

        $this->init_api();

        $result = WC_Gateway_IPP_API_Handler::refund_transaction($order, $amount, $reason);

        if (is_wp_error($result)) {
            $this->log('Refund Failed: ' . $result->get_error_message(), 'error');
            return new WP_Error('error', $result->get_error_message());
        }

        $this->log('Refund Result: ' . wc_print_r($result, true));

        switch (strtolower($result->ACK)) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
            case 'success':
            case 'successwithwarning':
                $order->add_order_note(
                    /* translators: 1: Refund amount, 2: Refund ID */
                    sprintf(__('Refunded %1$s - Refund ID: %2$s', 'woocommerce'), $result->GROSSREFUNDAMT, $result->REFUNDTRANSACTIONID) // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                );
                return true;
        }

        return isset($result->L_LONGMESSAGE0) ? new WP_Error('error', $result->L_LONGMESSAGE0) : false; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
    }

    /**
     * Capture payment when the order is changed from on-hold to complete or processing
     *
     * @param  int $order_id Order ID.
     */
    public function capture_payment($order_id)
    {
        $order = $this->scw_get_order($order_id);
       
        if ('ipp' === $this->id && 'Pending' === $order->_ipp_status) {
            $this->init_api();
            $result = WC_Gateway_IPP_API_Handler::do_capture($order,$order->total);
            print_r($result);
            if (is_wp_error($result)) {
                $this->log('Capture Failed: ' . $result->get_error_message(), 'error');
                /* translators: %s: IPPayware gateway error message */
                $order->add_order_note(sprintf(__('Payment could not be captured: %s', 'woocommerce'), $result->get_error_message()));
                return;
            }

            $this->log('Capture Result: ' . wc_print_r($result, true));

            // phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
            if (!empty($result->PAYMENTSTATUS)) {
                switch ($result->PAYMENTSTATUS) {
                    case 'Completed':
                        /* translators: 1: Amount, 2: Authorization ID, 3: Transaction ID */
                        $order->add_order_note(sprintf(__('Payment of %1$s was captured - Auth ID: %2$s, Transaction ID: %3$s', 'scwatbwsr-translate'), $result->AMT, $result->AUTHORIZATIONID, $result->TRANSACTIONID));
                        update_post_meta($order->get_id(), '_ipp_status', $result->PAYMENTSTATUS);
                        update_post_meta($order->get_id(), '_transaction_id', $result->TRANSACTIONID);
                        break;
                    default:
                        /* translators: 1: Authorization ID, 2: Payment status */
                        $order->add_order_note(sprintf(__('Payment could not be captured - Auth ID: %1$s, Status: %2$s', 'scwatbwsr-translate'), $result->AUTHORIZATIONID, $result->PAYMENTSTATUS));
                        break;
                }
            }
            // phpcs:enable
        }
    }


    /**
     * Init the API class and set the username/password etc.
     */
    protected function init_api()
    {
        include_once dirname(__FILE__) . '/includes/class-wc-gateway-ipp-api-handler.php';
        WC_Gateway_IPP_API_Handler::$api_key = $this->api_key;
        WC_Gateway_IPP_API_Handler::$api_secret = $this->api_secret;
    }
    

    /**
     * Logging method.
     *
     * @param string $message Log message.
     * @param string $level Optional. Default 'info'. Possible values:
     *                      emergency|alert|critical|error|warning|notice|info|debug.
     */
    public static function log($log, $level = 'info')
    {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
      
    }
    public static  function wc_sanitize_phone_number( $phone ) {
        return preg_replace( '/[^\d+]/', '', $phone );
    }
    public static function wc_normalize_postcode( $postcode ) {
        return preg_replace( '/[\s\-]/', '', trim( strtoupper( $postcode ) ) );
    }
    public static function wc_format_postcode( $postcode, $country ) {
        $postcode = self::wc_normalize_postcode( $postcode );
    
        switch ( $country ) {
            case 'CA':
            case 'GB':
                $postcode = substr_replace( $postcode, ' ', -3, 0 );
                break;
            case 'IE':
                $postcode = substr_replace( $postcode, ' ', 3, 0 );
                break;
            case 'BR':
            case 'PL':
                $postcode = substr_replace( $postcode, '-', -3, 0 );
                break;
            case 'JP':
                $postcode = substr_replace( $postcode, '-', 3, 0 );
                break;
            case 'PT':
                $postcode = substr_replace( $postcode, '-', 4, 0 );
                break;
            case 'PR':
            case 'US':
                $postcode = rtrim( substr_replace( $postcode, '-', 5, 0 ), '-' );
                break;
            case 'NL':
                $postcode = substr_replace( $postcode, ' ', 4, 0 );
                break;
            case 'LV':
                if ( preg_match( '/(?:LV)?-?(\d+)/i', $postcode, $matches ) ) {
                    $postcode = count( $matches ) >= 2 ? "LV-$matches[1]" : $postcode;
                }
                break;
            case 'DK':
                $postcode = preg_replace( '/^(DK)(.+)$/', '${1}-${2}', $postcode );
                break;
        }
        return $postcode;
    }    
    /**
     * Check if this gateway is available in the user's country based on currency.
     *
     * @return bool
     */
    public function is_valid_for_use()
    {
        return in_array(
            get_woocommerce_currency(),
            apply_filters(
                'woocommerce_ipp_supported_currencies',
                ['USD']
            ),
            true
        );
    }



    /**
     * Custom IPPayware order received text.
     *
     * @since 1.0.0
     * @param string   $text Default text.
     * @param WC_Order $order Order data.
     * @return string
     */
    public function order_received_text($text, $order)
    {
        if ($order && $this->id === $order->get_payment_method()) {
            return esc_html__('Thank you for your payment. Your transaction has been completed, and a receipt for your purchase has been emailed to you.', 'scwatbwsr-translate');
        }

        return $text;
    }
}
