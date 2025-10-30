<?php

/**
 * Email Header - Taulignan Personnalisé
 *
 * @package Taulignan
 * @version 1.0.0
 */

if (! defined('ABSPATH')) {
	exit;
}

$store_name = get_bloginfo('name', 'display');

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<title><?php echo esc_html($store_name); ?></title>
	<style type="text/css">
		<?php
		// Inclure les styles personnalisés
		$email_styles_file = get_template_directory() . '/woocommerce/emails/email-styles.php';
		if (file_exists($email_styles_file)) {
			include $email_styles_file;
		}
		?>
	</style>
</head>

<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
	<div width="100%" id="outer_wrapper">
		<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>" style="margin: 0 auto; max-width: 600px;">
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="inner_wrapper">
				<tr>
					<td align="center" valign="top">
						<!-- Logo / Nom du site -->
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td id="template_header_image" style="padding: 32px 32px 0; text-align: center; background-color: #ffffff;margin-bottom: 20px;">
											<?php
											$img = get_option('woocommerce_email_header_image');
											if ($img) {
												echo '<p style="margin-bottom:20px;"><img src="' . esc_url($img) . '" alt="' . esc_attr($store_name) . '" style="max-width: 180px; height: auto; border: none;" /></p>';
											} else {
												echo '<p class="email-logo-text" style="color: #6b764c;margin-bottom: 20px; font-size: 32px; margin: 0; font-family: \'Cabin\', sans-serif;">' . esc_html($store_name) . '</p>';
											}
											?>
										</td>
									</tr>
								</table>

								<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_container" style="background-color: #ffffff;">
									<tr>
										<td align="center" valign="top">
											<!-- Header avec titre -->
											<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" style="background: #6b764c; padding: 30px 32px;">
												<tr>
													<td id="header_wrapper" style="padding: 0; display: block;">
														<h1 style="color: #ffffff; font-family: 'Bellota Text', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 20px; font-weight: 300; margin: 0; text-align: center; line-height: 1.4;">
															<?php echo esc_html($email_heading); ?>
														</h1>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td align="center" valign="top">
											<!-- Body -->
											<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body">
												<tr>
													<td valign="top" id="body_content" style="background-color: #ffffff;">
														<!-- Content -->
														<table border="0" cellpadding="32" cellspacing="0" width="100%">
															<tr>
																<td valign="top" id="body_content_inner_cell" style="text-align: left;">
																	<div id="body_content_inner" style="color: #000000; font-family: 'Bellota Text', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 1.6;">
