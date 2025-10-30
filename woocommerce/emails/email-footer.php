<?php

/**
 * Email Footer - Taulignan Personnalisé
 *
 * @package Taulignan
 * @version 1.0.0
 */

if (! defined('ABSPATH')) {
	exit;
}

?>
</div>
</td>
</tr>
</table>
<!-- End Content -->
</td>
</tr>
</table>
<!-- End Body -->
</td>
</tr>
</table>
</td>
</tr>
<tr>
	<td align="center" valign="top">
		<!-- Footer -->
		<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_footer">
			<tr>
				<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td id="credit" valign="middle" style="background-color: #D4C5B0; color: #6B5B47; font-family: 'Bellota Text', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.6; text-align: center;">
								<?php 
								$site_name = get_bloginfo('name');
								$site_url = home_url();
								?>
								<p style="margin: 0 0 12px; font-weight: 600; color: #6B5B47; font-size: 16px; font-family: 'Cabin', sans-serif;">
									<?php echo esc_html($site_name); ?>
								</p>
								<p style="margin: 0 0 8px; color: #6B5B47;">
									<?php 
									// Récupérer les informations de contact
									$contact_email = get_option('admin_email');
									$contact_phone = get_option('taulignan_contact_phone', '+33 (0)4 75 00 00 00');
									
									echo esc_html($contact_email) . '<br>';
									echo esc_html($contact_phone);
									?>
								</p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<!-- End Footer -->
	</td>
</tr>
</table>
</div>
</td>
<td></td>
</tr>
</table>
</body>

</html>
