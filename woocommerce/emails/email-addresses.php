<?php

/**
 * Email Addresses - Version personnalisée Taulignan
 *
 * @package Taulignan
 * @version 1.0.0
 */

if (! defined('ABSPATH')) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';
$address    = $order->get_formatted_billing_address();
$shipping   = $order->get_formatted_shipping_address();

?>

<!-- Adresse de facturation -->
<div style="margin: 24px 0;" class="address-block">
	<h3 style="color: #004f6e; font-family: 'Cabin', sans-serif; font-size: 24px; margin: 0 0 12px; font-weight: 600;">
		Adresse de facturation
	</h3>

	<div style="padding: 16px; border: 1px solid #E6D7C3; margin: 0;">
		<?php 
		// Nom et prénom
		$billing_first_name = $order->get_billing_first_name();
		$billing_last_name = $order->get_billing_last_name();
		
		if ($billing_first_name || $billing_last_name) {
			echo '<p style="margin: 0 0 8px; font-weight: 600; color: #000000; font-size: 15px;">';
			echo esc_html($billing_first_name . ' ' . $billing_last_name);
			echo '</p>';
		}
		
		// Compagnie
		if ($order->get_billing_company()) {
			echo '<p style="margin: 0 0 8px; color: #000000; font-size: 14px;">';
			echo esc_html($order->get_billing_company());
			echo '</p>';
		}
		
		// Adresse complète
		echo '<p style="margin: 0 0 8px; color: #000000; font-size: 14px; line-height: 1.6;">';
		echo wp_kses_post($address ? $address : esc_html__('N/A', 'woocommerce'));
		echo '</p>';
		
		// Email
		if ($order->get_billing_email()) {
			echo '<p style="margin: 0; color: #000000; font-size: 14px;">';
			echo '<strong>Email :</strong><br>';
			echo esc_html($order->get_billing_email());
			echo '</p>';
		}
		
		// Téléphone
		if ($order->get_billing_phone()) {
			echo '<p style="margin: 8px 0 0; color: #000000; font-size: 14px;">';
			echo '<strong>Tél :</strong> ' . esc_html($order->get_billing_phone());
			echo '</p>';
		}
		?>
	</div>
</div>

<style type="text/css">
	@media screen and (max-width: 600px) {
		.address-block {
			display: block !important;
			width: 100% !important;
		}
	}
</style>
