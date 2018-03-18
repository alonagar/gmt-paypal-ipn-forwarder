<?php

/**
 * Plugin Name: GMT PayPal IPN Forwarder
 * Plugin URI: https://github.com/cferdinandi/gmt-paypal-ipn-forwarder/
 * GitHub Plugin URI: https://github.com/cferdinandi/gmt-paypal-ipn-forwarder/
 * Description: Forward PayPal IPN to multiple other IPN services in WordPress. Extends <a href="https://wordpress.org/plugins/paypal-ipn/">PayPal IPN for WordPress</a>. Add forwarding URLs under <a href="options-general.php?page=gmt_paypal_ipn_forwarder_options">Settings &rarr; PayPal IPN Forwarder</a>
 * Version: 1.0.3
 * Author URI: http://gomakethings.com
 * License: MIT
 *
 * Kudos to Shawn Gaffney for the inspiration.
 * @link https://gist.github.com/anointed/3805698
 */

require_once( plugin_dir_path( __FILE__ ) . 'options.php' );

function WC_paypal_ipn_forwarder( $caller ) {
// this is not a Paypal request, quite
	if ($caller != 'wc_gateway_paypal') {
		return true;
	}
    $options = gmt_paypal_ipn_forwarder_get_theme_options();
	// Broadcast
	foreach ( $options['urls'] as $url ) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1500);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($_POST));
		$data = curl_exec($ch);
		curl_close($ch);
	}

}
add_action( 'woocommerce_api_request', 'WC_paypal_ipn_forwarder', 10, 1 );

