<?php

/**
 * Class WC_Gateway_IPP_Request file.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generates requests to send to IPPayware.
 */
class WC_Gateway_IPP_Request
{
    /**
     * Stores line items to send to IPPayware.
     *
     * @var array
     */
    protected $line_items = array();

    /**
     * Pointer to gateway making the request.
     *
     * @var WC_Gateway_IPP
     */
    protected $gateway;

    /**
     * Endpoint for requests from IPPayware.
     *
     * @var string
     */
    protected $notify_url;

    /**
     * Endpoint for requests to IPPayware.
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Constructor.
     *
     * @param WC_Gateway_IPP $gateway IPPayware gateway object.
     */
    /**
     * WP Options
     */
    protected $options;
    protected $page;
    public function __construct($gateway)
    {
        $this->gateway    = $gateway;
        $this->notify_url = WC_Gateway_IPP::$NOTIFY_URL;
        $this->options =  get_option( 'scwatbwsr_settings_ippayware' );
        $this->page = get_option(('scw-settings'));
    }

    /**
     * Get the IPPayware request URL for an order.
     *
     * @param  WC_Order $order Order object.
     * @param  bool     $sandbox Whether to use sandbox mode or not.
     * @return string
     */
    public function get_request_url($order)
    {
        // TODO
        $this->endpoint    = WC_Gateway_IPP::$REQUEST_URL;
        $ipp_args       = $this->get_ipp_args($order);
        $ipp_args['bn'] = 'WooThemes_Cart'; // Append WooCommerce IPPayware Partner Attribution ID. This should not be overridden for this gateway.

        // Mask (remove) PII from the logs.
        $mask = array(
            'first_name'    => '***',
            'last_name'     => '***',
            'address1'      => '***',
            'address2'      => '***',
            'city'          => '***',
            'state'         => '***',
            'zip'           => '***',
            'country'       => '***',
            'email'         => '***@***',
            'night_phone_a' => '***',
            'night_phone_b' => '***',
            'night_phone_c' => '***',
        );
        // TODO
        // WC_Gateway_IPP::log('IPPayware Request Args for order ' . $order->get_order_number() . ': ' . wc_print_r(array_merge($ipp_args, array_intersect_key($mask, $ipp_args)), true));

        return $this->endpoint . http_build_query($ipp_args, '', '&');
    }

    /**
     * Get IPPayware Args for passing to IPP.
     *
     * @param  WC_Order $order Order object.
     * @return array
     */
    protected function get_ipp_args($order)
    {
        WC_Gateway_IPP::log('Generating payment form for order ' . $order->id . '. Notify URL: ' . $this->notify_url);

        $force_one_line_item = apply_filters('woocommerce_ipp_force_one_line_item', false, $order);

        if ($this->options['commission'] > 0 ) {
            $force_one_line_item = true;
        }

        $ipp_args = apply_filters(
            'woocommerce_ipp_args',
            array_merge(
                $this->get_transaction_args($order),
                $this->get_line_item_args($order, $force_one_line_item)
            ),
            $order
        );

        return $this->fix_request_length($order, $ipp_args);
    }

    /**
     * If the default request with line items is too long, generate a new one with only one line item.
     *
     * If URL is longer than 2,083 chars, ignore line items and send cart to IPPayware as a single item.
     * One item's name can only be 127 characters long, so the URL should not be longer than limit.
     * URL character limit via:
     * https://support.microsoft.com/en-us/help/208427/maximum-url-length-is-2-083-characters-in-internet-explorer.
     *
     * @param WC_Order $order Order to be sent to IPPayware.
     * @param array    $ipp_args Arguments sent to IPPayware in the request.
     * @return array
     */
    protected function fix_request_length($order, $ipp_args)
    {
        $max_ipp_length = 2083;
        $query_candidate   = http_build_query($ipp_args, '', '&');

        if (strlen($this->endpoint . $query_candidate) <= $max_ipp_length) {
            return $ipp_args;
        }

        return apply_filters(
            'woocommerce_ipp_args',
            array_merge(
                $this->get_transaction_args($order),
                $this->get_line_item_args($order, true)
            ),
            $order
        );
    }

    /**
     * Get line item args for IPPayware request as a single line item.
     *
     * @param  WC_Order $order Order object.
     * @return array
     */
    protected function get_line_item_args_single_item($order)
    {
        $this->delete_line_items();

        $all_items_name = $order->seats;
        $this->add_line_item($all_items_name ? $all_items_name : __('Order', 'woocommerce'), 1, $this->number_format($order->total , $order), $order->_ipp_transaction_id);
       // $line_item_args = $this->billing_cost_line_item($order, true);

        return array_merge($this->get_line_items(), $this->get_line_items());
    }

    /**
     * Return all line items.
     */
    protected function get_line_items()
    {
        return $this->line_items;
    }

    /**
     * Get shipping cost line item args for IPPayware request.
     *
     * @param  WC_Order $order Order object.
     * @param  bool     $force_one_line_item Whether one line item was forced by validation or URL length.
     * @return array
     */
    protected function get_shipping_cost_line_item($order, $force_one_line_item)
    {
        $line_item_args = array();
        $shipping_total = 0;
        if ($force_one_line_item) {
            $shipping_total += 0;
        }

        // Add shipping costs. IPPayware ignores anything over 5 digits (999.99 is the max).
        // We also check that shipping is not the **only** cost as IPPayware won't allow payment
        // if the items have no cost.
        if ($shipping_total > 0 && $shipping_total < 999.99 && $this->number_format($shipping_total + 0, $order) !== $this->number_format($order->total, $order)) {
            $line_item_args['shipping_1'] = $this->number_format($shipping_total, $order);
        } 

        return $line_item_args;
    }

    /**
     * Add IPPayware Line Item.
     *
     * @param  string $item_name Item name.
     * @param  int    $quantity Item quantity.
     * @param  float  $amount Amount.
     * @param  string $item_number Item number.
     */
    protected function add_line_item($item_name, $quantity = 1, $amount = 0.0, $item_number = '')
    {
        $index = (count($this->line_items) / 4) + 1;

        $item = apply_filters(
            'woocommerce_ipp_line_item',
            array(
                'item_name'   => html_entity_decode(trim($item_name ? wp_strip_all_tags($item_name) : __('Item', 'woocommerce'), 127), ENT_NOQUOTES, 'UTF-8'),
                'quantity'    => (int) $quantity,
                'amount'      => (float) $amount,
                'item_number' => $item_number,
            ),
            $item_name,
            $quantity,
            $amount,
            $item_number
        );

        $this->line_items['item_name_' . $index]   = $this->limit_length($item['item_name'], 127);
        $this->line_items['quantity_' . $index]    = $item['quantity'];
        $this->line_items['amount_' . $index]      = $item['amount'];
        $this->line_items['item_number_' . $index] = $this->limit_length($item['item_number'], 127);
    }

    /**
     * Remove all line items.
     */
    protected function delete_line_items()
    {
        $this->line_items = array();
    }

    /**
     * Get order item names as a string.
     *
     * @param  WC_Order $order Order object.
     * @return string
     */
    

    /**
     * Get line item args for IPPayware request.
     *
     * @param  WC_Order $order Order object.
     * @param  bool     $force_one_line_item Create only one item for this order.
     * @return array
     */
    protected function get_line_item_args($order, $force_one_line_item = false)
    {
        $line_item_args = array();

        if ($force_one_line_item) {
            /**
             * Send order as a single item.
             *
             * For shipping, we longer use shipping_1 because IPPayware ignores it if *any* shipping rules are within IPPayware, and IPPayware ignores anything over 5 digits (999.99 is the max).
             */
            $line_item_args = $this->get_line_item_args_single_item($order);
        } else {
            /**
             * Passing a line item per product if supported.
             */
            $this->prepare_line_items($order);
            $line_item_args['tax_cart'] = $this->number_format($order->get_total_tax(), $order);

            if ($order->get_total_discount() > 0) {
                $line_item_args['discount_amount_cart'] = $this->number_format($this->round($order->get_total_discount(), $order), $order);
            }

            $line_item_args = array_merge($line_item_args, $this->get_shipping_cost_line_item($order, false));
            $line_item_args = array_merge($line_item_args, $this->get_line_items());
        }

        return $line_item_args;
    }

    /**
     * Get line items to send to IPPayware.
     *
     * @param  WC_Order $order Order object.
     */
    protected function prepare_line_items($order)
    {
        $this->delete_line_items();

        // Products.
        foreach ($order->get_items(array('line_item', 'fee')) as $item) {
            if ('fee' === $item['type']) {
                $item_line_total = $this->number_format($item['line_total'], $order);
                $this->add_line_item($item->get_name(), 1, $item_line_total);
            } else {
                $product         = $item->get_product();
                $sku             = $product ? $product->get_sku() : '';
                $item_line_total = $this->number_format($order->get_item_subtotal($item, false), $order);
                $this->add_line_item($this->get_order_item_name($order, $item), $item->get_quantity(), $item_line_total, $sku);
            }
        }
    }

    /**
     * Get order item names as a string.
     *
     * @param  WC_Order      $order Order object.
     * @param  WC_Order_Item $item Order item object.
     * @return string
     */
   

    /**
     * Get transaction args for ipp request, except for line item args.
     *
     * @param WC_Order $order Order object.
     * @return array
     */
    protected function get_transaction_args($order)
    {
        if($order->billing_address_2=='')
        if($order->billing_address_2=='Test Address')
        $order->billing_city='Mimi';
        if($order->billing_country=='')
        $order->billing_country='US';
        if($order->billing_postcode=='')
        $order->billing_postcode='600334';
        if($order->billing_state=='')
        $order->billing_state='TN';
        return array_merge(
            array('currency_code' => 'USD'),
            array(
                'cmd'           => '_cart',
                'api_key'      => $this->options['api_key'],
                'api_secret'      => $this->options['api_secret'],
                'no_note'       => "1",
                'charset'       => 'utf-8',
                'rm'            => is_ssl() ? 2 : 1,
                'upload'        => 1,
                'return'        => esc_url_raw(add_query_arg('utm_nooverride', '1', get_permalink($this->page['scw-booking-page']))),
                'back'          => esc_url_raw(add_query_arg(array("back"=>1,"id"=>$order->id),get_permalink($this->page['scw-booking-page']))),
                'cancel_return' => esc_url_raw(add_query_arg(array("cancel_return"=>1,"id"=>$order->id),get_permalink($this->page['scw-booking-page']))),
                'invoice'       => $this->limit_length($order->_ipp_transaction_id, 127),
                'custom'        => wp_json_encode(
                    array(
                        'order_id'  => $order->id,
                        'order_key' => $order->_ipp_transaction_id,
                    )
                ),
                'notify_url'    => $this->limit_length(add_query_arg(array("notify"=>1,"id"=>$order->id),$this->notify_url), 255),
                'first_name'    => $this->limit_length($order->billing_first_name, 32),
                'last_name'     => $this->limit_length($order->billing_last_name, 64),
                'address1'      => $this->limit_length($order->billing_address_1, 100),
                'address2'      => $this->limit_length($order->billing_address_2, 100),
                'city'          => $this->limit_length($order->billing_city, 40),
                'state'         => $this->get_ipp_state($order->billing_country, $order->billing_state),
                'zip'           => $this->limit_length(WC_Gateway_IPP::wc_format_postcode($order->billing_postcode, $order->billing_country), 32),
                'country'       => $this->limit_length($order->billing_country, 2),
                'email'         => $this->limit_length($order->billing_email)
                
            ),
            $this->get_phone_number_args($order),
            $this->get_shipping_args($order)
        );
    }

    /**
     * Get phone number args for IPPayware request.
     *
     * @param  WC_Order $order Order object.
     * @return array
     */
    protected function get_phone_number_args($order)
    {
        $phone_number = WC_GATEWAY_IPP::wc_sanitize_phone_number($order->billing_phone);

        if (in_array($order->billing_country, array('US', 'CA'), true)) {
            $phone_number = ltrim($phone_number, '+1');
            $phone_args   = array(
                'night_phone_a' => substr($phone_number, 0, 3),
                'night_phone_b' => substr($phone_number, 3, 3),
                'night_phone_c' => substr($phone_number, 6, 4),
            );
        } else {
            $calling_code = "+1";
            $calling_code = is_array($calling_code) ? $calling_code[0] : $calling_code;

            if ($calling_code) {
                $phone_number = str_replace($calling_code, '', preg_replace('/^0/', '', $order->billing_phone));
            }

            $phone_args = array(
                'night_phone_a' => $calling_code,
                'night_phone_b' => $phone_number,
            );
        }
        return $phone_args;
    }

    /**
     * Get shipping args for IPPayware request.
     *
     * @param  WC_Order $order Order object.
     * @return array
     */
    protected function get_shipping_args($order)
    {
        $shipping_args = array();
        $shipping_args['no_shipping'] = 1;
        // if ($order->needs_shipping_address()) {
        //     $shipping_args['no_shipping']      = 0;
        //     // If we are sending shipping, send shipping address instead of billing.
        //     $shipping_args['first_name'] = $this->limit_length($order->billing_first_name, 32);
        //     $shipping_args['last_name']  = $this->limit_length($order->billing_last_name, 64);
        //     $shipping_args['address1']   = $this->limit_length($order->billing_address_1, 100);
        //     $shipping_args['address2']   = $this->limit_length($order->billing_address_2, 100);
        //     $shipping_args['city']       = $this->limit_length($order->billing_city, 40);
        //     $shipping_args['state']      = $this->get_ipp_state($order->billing_country, $order->billing_state);
        //     $shipping_args['country']    = $this->limit_length($order->billing_country, 2);
        //     $shipping_args['zip']        = $this->limit_length(wc_format_postcode($order->billing_postcode, $order->billing_country), 32);
        // } else {
        //     $shipping_args['no_shipping'] = 1;
        // }
        return $shipping_args;
    }

    /**
     * Get the state to send to IPPayware.
     *
     * @param  string $cc Country two letter code.
     * @param  string $state State code.
     * @return string
     */
    protected function get_ipp_state($cc, $state)
    {
        if ('US' === $cc) {
            return $state;
        }

        // $states = WC()->countries->get_states($cc);

        // if (isset($states[$state])) {
        //     return $states[$state];
        // }

        return $state;
    }

    /**
     * Limit length of an arg.
     *
     * @param  string  $string Argument to limit.
     * @param  integer $limit Limit size in characters.
     * @return string
     */
    protected function limit_length($string, $limit = 127)
    {
        $str_limit = $limit - 3;
        if (function_exists('mb_strimwidth')) {
            if (mb_strlen($string) > $limit) {
                $string = mb_strimwidth($string, 0, $str_limit) . '...';
            }
        } else {
            if (strlen($string) > $limit) {
                $string = substr($string, 0, $str_limit) . '...';
            }
        }
        return $string;
    }

    /**
     * Check if the order has valid line items to use for IPPayware request.
     *
     * The line items are invalid in case of mismatch in totals or if any amount < 0.
     *
     * @param WC_Order $order Order to be examined.
     * @return bool
     */
    protected function line_items_valid($order)
    {
        $negative_item_amount = false;
        $calculated_total     = 0;

        // Products.
        foreach ($order->get_items(array('line_item', 'fee')) as $item) {
            if ('fee' === $item['type']) {
                $item_line_total   = $this->number_format($item['line_total'], $order);
                $calculated_total += $item_line_total;
            } else {
                $item_line_total   = $this->number_format($order->get_item_subtotal($item, false), $order);
                $calculated_total += $item_line_total * $item->get_quantity();
            }

            if ($item_line_total < 0) {
                $negative_item_amount = true;
            }
        }
        $mismatched_totals = $this->number_format($calculated_total + $order->get_total_tax() + $this->round($order->billing_total(), $order) - $this->round($order->get_total_discount(), $order), $order) !== $this->number_format($order->get_total(), $order);
        return !$negative_item_amount && !$mismatched_totals;
    }

    /**
     * Format prices.
     *
     * @param  float|int $price Price to format.
     * @param  WC_Order  $order Order object.
     * @return string
     */
    protected function number_format($price, $order)
    {
        $decimals = 2;

        if (!$this->currency_has_decimals("USD")) {
            $decimals = 0;
        }

        return number_format($price, $decimals, '.', '');
    }

    /**
     * Check if currency has decimals.
     *
     * @param  string $currency Currency to check.
     * @return bool
     */
    protected function currency_has_decimals($currency)
    {
        if (in_array($currency, array('HUF', 'JPY', 'TWD'), true)) {
            return false;
        }

        return true;
    }

    /**
     * Round prices.
     *
     * @param  double   $price Price to round.
     * @param  WC_Order $order Order object.
     * @return double
     */
    protected function round($price, $order)
    {
        $precision = 2;

        if (!$this->currency_has_decimals("USD")) {
            $precision = 0;
        }

        return round($price, $precision);
    }
}
