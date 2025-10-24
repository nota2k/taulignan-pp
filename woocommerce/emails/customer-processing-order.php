<?php

/**
 * Customer processing order email - Confirmation de réservation en cours
 *
 * @package Taulignan
 * @version 1.0.0
 */

if (! defined('ABSPATH')) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email);

// Récupérer les informations de la commande
$order_date = $order->get_date_created();
$order_id = $order->get_order_number();
?>

<!-- Statut de paiement -->
<?php if ($order->needs_payment()) : ?>
	<div style="background-color: #FFF9E6; padding: 20px; margin: 0 0 24px; text-align: center;">
		<p style="margin: 0; color: #6B5B47; font-size: 16px; font-weight: 600;">
			💳 Paiement en attente
		</p>
	</div>
<?php else : ?>
	<div style="background-color: #E6E6E6; padding: 20px; margin: 0 0 24px; text-align: center;">
		<p style="margin: 0; color: #000000; font-size: 16px; font-weight: 600;">
			Votre paiement a bien été accepté
		</p>
	</div>
<?php endif; ?>

<!-- Message de bienvenue -->
<div style="margin: 0 0 32px;">
	<p style="font-size: 16px; margin: 0 0 12px; color: #000000;">
		<?php
		if (! empty($order->get_billing_first_name())) {
			printf('Bonjour %s,', esc_html($order->get_billing_first_name()));
		} else {
			echo 'Bonjour,';
		}
		?>
	</p>
	<p style="font-size: 16px; margin: 0; color: #000000; line-height: 1.6;">
		Merci pour votre réservation ! 🎉<br>
		Nous avons bien reçu votre demande et nous sommes en train de la traiter. Vous recevrez une confirmation définitive très prochainement.
	</p>
</div>

<!-- Récapitulatif -->
<h2 style="color: #004f6e; font-family: 'Maghfirea', Georgia, serif; font-size: 20px; font-weight: 400; margin: 0 0 16px;">
	Récapitulatif de votre séjour
</h2>

<?php
/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 */
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);
?>

<!-- Moyen de paiement -->
<?php if ($order->get_payment_method_title()) : ?>
<p style="margin: 24px 0 8px; color: #000000; font-size: 15px;">
	<strong>Moyen de paiement :</strong> <?php echo esc_html($order->get_payment_method_title()); ?>
</p>
<?php endif; ?>

<!-- Commentaire client -->
<?php if ($order->get_customer_note()) : ?>
<div style="margin: 24px 0;">
	<p style="margin: 0 0 8px; font-weight: 600; color: #000000; font-size: 15px;">
		Commentaire client :
	</p>
	<p style="margin: 0; color: #000000; font-size: 14px;">
		<?php echo wp_kses_post(nl2br(wptexturize($order->get_customer_note()))); ?>
	</p>
</div>
<?php endif; ?>

<!-- Pour la suite -->
<div style="background-color: #D4C5B0; padding: 32px; margin: 32px 0;">
	<h3 style="color: #6B5B47; font-family: 'Maghfirea', Georgia, serif; font-size: 18px; margin: 0 0 16px; font-weight: 400;">
		Pour la suite
	</h3>
	<ul style="margin: 0; padding-left: 20px; color: #6B5B47; line-height: 1.8;">
		<li>Nous traitons votre demande dans les meilleurs délais</li>
		<li>Vous recevrez un email de confirmation dès validation</li>
		<li>En cas de question, n'hésitez pas à nous contacter</li>
		<li>Conservez bien ce numéro de réservation : <strong>#<?php echo esc_html($order_id); ?></strong></li>
	</ul>
</div>

<!-- Adresse de facturation -->
<?php
/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);
?>

<?php
/**
 * Show user-defined additional content
 */
if ($additional_content) {
	echo '<div style="margin-top: 32px;">';
	echo wp_kses_post(wpautop(wptexturize($additional_content)));
	echo '</div>';
}
