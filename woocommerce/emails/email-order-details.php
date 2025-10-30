<?php

/**
 * Order details table shown in emails - Version personnalisée Taulignan
 *
 * @package Taulignan
 * @version 1.0.0
 */

if (! defined('ABSPATH')) {
	exit;
}

$text_align  = is_rtl() ? 'right' : 'left';
$margin_side = is_rtl() ? 'left' : 'right';

// Styles inline pour une meilleure compatibilité email
$table_style = 'width: 100%; border-collapse: collapse; margin: 20px 0; border: 1px solid #004f6e; border-radius: 8px; overflow: hidden;';
$th_style = 'background-color: #004f6e; color: white; font-weight: 600; text-align: ' . $text_align . '; padding: 16px 12px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; font-family: "Bellota Text", "Helvetica Neue", Helvetica, Arial, sans-serif;';
$td_style = 'padding: 16px 12px; vertical-align: top; color: #000000; font-family: "Bellota Text", "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 15px;';
$product_name_style = 'font-weight: 600; color: #6b764c; font-size: 16px;';
$total_row_style = 'padding: 12px; border-bottom: 1px solid #E6D7C3; font-size: 15px; color: #000000;';
$total_label_style = 'text-align: ' . $text_align . '; padding: 12px; font-weight: 500;';
$total_amount_style = 'text-align: ' . (is_rtl() ? 'left' : 'right') . '; padding: 12px;';
$row_style = 'text-transform: inherit;';

?>

<div class="email-order-details" style="margin: 20px 0;">

	<?php
	do_action('woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email);
	?>

	<table cellspacing="0" cellpadding="0" style="<?php echo esc_attr($table_style); ?>" border="0" class="email-order-details">
		<thead>
			<tr>
				<th scope="col" style="<?php echo esc_attr($th_style); ?>">
					<?php esc_html_e('Séjour', 'woocommerce'); ?>
				</th>
				<th scope="col" style="<?php echo esc_attr($th_style . 'text-align: center;'); ?>">
					<?php esc_html_e('Quantité', 'woocommerce'); ?>
				</th>
				<th scope="col" style="<?php echo esc_attr($th_style . 'text-align: ' . (is_rtl() ? 'left' : 'right') . ';'); ?>">
					<?php esc_html_e('Prix', 'woocommerce'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo wp_kses_post(wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$order,
				array(
					'show_sku'      => $sent_to_admin,
					'show_image'    => false,
					'image_size'    => array(32, 32),
					'plain_text'    => $plain_text,
					'sent_to_admin' => $sent_to_admin,
					'row_style'    => $row_style,
				)
			));
			?>
		</tbody>
		<tfoot>
			<?php
			$item_totals = $order->get_order_item_totals();
			$item_totals_count = is_array($item_totals) ? count($item_totals) : 0;
			if ($item_totals_count) {
				$i = 0;
				foreach ($item_totals as $total) {
					// Ignorer la ligne du moyen de paiement dans les totaux
					if (isset($total['type']) && $total['type'] === 'payment_method') {
						continue;
					}
					$i++;
					$label_cell_style = $total_label_style;
					$value_cell_style = $total_amount_style;
					// Déterminer si la ligne correspond au total général
					$is_total = isset($total['type']) && in_array($total['type'], array('order_total', 'total'), true);
					$row_classes = 'order-totals';
					if ($is_total) {
						$row_classes .= ' order-totals-total';
					}
					if (1 === $i) {
						$row_classes .= ' first';
					}
			?>
					<tr class="<?php echo esc_attr($row_classes); ?>" style="<?php echo esc_attr($row_style); ?>">
						<th scope="row" style="<?php echo esc_attr($label_cell_style); ?>"><?php echo wp_kses_post($total['label']); ?></th>
						<td style="padding: 0; mso-line-height-rule: exactly;">&nbsp;</td>
						<td style="<?php echo esc_attr($value_cell_style); ?>"><?php echo wp_kses_post($total['value']); ?></td>
					</tr>
			<?php
				}
			}
			?>
			<?php

			if ($order->get_customer_note()) {
			?>
				<tr class="order-customer-note">
					<td colspan="3" style="padding: 24px 12px; border-top: 2px solid #E6D7C3; background-color: #FFF9E6;">
						<p style="margin: 0 0 8px; font-weight: 600; color: #8B7355; font-size: 14px;">
							<?php esc_html_e('Note :', 'woocommerce'); ?>
						</p>
						<p style="margin: 0; color: #6B5B47; font-size: 14px; line-height: 1.6;">
							<?php echo wp_kses_post(nl2br(wptexturize($order->get_customer_note()))); ?>
						</p>
					</td>
				</tr>
			<?php
			}
			?>
		</tfoot>
	</table>
</div>

<style type="text/css">
	/* Styles additionnels pour une meilleure compatibilité */

	.td.text-align-right {
		text-align: right !important;
	}

	.order-totals-total th,
	.order-totals-total td {
		color: #fff !important;
		background-color: #004f6e !important;
	}

	.order-totals-total td {
		font-weight: 600 !important;
		font-size: 18px !important;

	}

	.order-totals-total th {
		font-weight: 600 !important;
		font-size: 20px;

	}

	.order-item-data  td:last-of-type{
		padding: 0!important;
	}
	
	.email-order-details {
		font-family: "Bellota Text", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
	}

	.email-order-details td.product-name {
		font-weight: 600 !important;
		color: #6b764c !important;
		font-size: 16px !important;
	}

	.email-order-details .wc-item-meta {
		font-size: 13px !important;
		color: #6B5B47 !important;
		margin-top: 8px !important;
	}

	.email-order-details .wc-item-meta li {
		margin: 4px 0 !important;
	}

	.email-order-details .wc-item-meta-label {
		font-weight: 600 !important;
		color: #8B7355 !important;
	}

	th {
		background-color: #004f6e;
	}
</style>