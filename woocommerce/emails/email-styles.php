<?php

/**
 * Email Styles - Taulignan Personnalisé
 *
 * @package Taulignan
 * @version 1.0.0
 */

if (! defined('ABSPATH')) {
	exit;
}

// Couleurs du thème Taulignan
$bg               = '#F5F2E8'; // Beige clair
$body             = '#ffffff'; // Blanc
$base             = '#B8A3D1'; // Lavande
$base_text        = '#ffffff';
$text             = '#000000'; // Noir
$footer_text      = '#6e5b7c'; // Violet foncé
$link_color       = '#8B7355'; // Olive
$border_color     = '#E6D7C3'; // Beige foncé
$heading_color    = '#6b764c'; // Vert olive

// Police Bellota Text (fallback sur system fonts)
$font_family = '"Bellota Text", "Helvetica Neue", Helvetica, Arial, sans-serif';
$heading_font = '"Maghfirea", Georgia, serif';

?>
body {
padding: 0;
text-align: center;
font-family: <?php echo $font_family; ?>;
}

#inner_wrapper {
border-radius: 8px;
box-shadow: 0 2px 8px rgba(107, 91, 76, 0.1);
}

#wrapper {
margin: 0 auto;
padding: 40px 20px;
-webkit-text-size-adjust: none !important;
width: 100%;
max-width: 600px;
}

#template_container {
box-shadow: none;
border: 0;
border-radius: 8px !important;
overflow: hidden;
}

#template_header {
background: linear-gradient(135deg, <?php echo esc_attr($base); ?> 0%, #D4C5E8 100%);
border-radius: 8px 8px 0 0 !important;
color: <?php echo esc_attr($base_text); ?>;
border-bottom: 0;
font-weight: bold;
line-height: 100%;
vertical-align: middle;
font-family: <?php echo $font_family; ?>;
padding: 40px 32px;
}

#template_header h1,
#template_header h1 a {
color: <?php echo esc_attr($base_text); ?>;
background-color: inherit;
font-family: <?php echo $heading_font; ?>;
font-size: 36px;
font-weight: 400;
margin: 0;
text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

#template_header_image {
padding: 32px 32px 20px;
text-align: center;
margin-bottom: 20px!important;
}

#template_header_image p {
margin-bottom: 0;
text-align: center;
}

#template_header_image img {
max-width: 180px;
height: auto;
}

.email-logo-text {
color: <?php echo esc_attr($base_text); ?>;
font-family: <?php echo $heading_font; ?>;
font-size: 32px;
font-weight: 400;
}

.email-introduction {
padding: 32px 32px 24px;
background-color: #FDFCFA;
border-bottom: 2px solid <?php echo esc_attr($border_color); ?>;
}

.reservation-badge {
display: inline-block;
background-color: <?php echo esc_attr($base); ?>;
color: white;
padding: 8px 20px;
border-radius: 20px;
font-size: 14px;
font-weight: 600;
margin-bottom: 16px;
letter-spacing: 0.5px;
}

#body_content {
background-color: <?php echo esc_attr($body); ?>;
}

#body_content table td {
padding: 32px;

}

#body_content table tr td:first-child {
	width: 80%;
}

#body_content table tr td:not(:first-child) {
	width: 10%;
}

#body_content table tr td:nth-child(2) {
	text-align: center;
}


#body_content table td td {
padding: 12px;
}

#body_content table td th {
padding: 12px;
background-color: #FDFCFA;
}

#body_content_inner {
color: <?php echo esc_attr($text); ?>;
font-family: <?php echo $font_family; ?>;
font-size: 16px;
line-height: 1.6;
text-align: left;
}

#body_content p {
margin: 0 0 16px;
color: <?php echo esc_attr($text); ?>;
font-size: 16px;
line-height: 1.6;
}

h1 {
color: <?php echo esc_attr($heading_color); ?>;
font-family: <?php echo $heading_font; ?>;
font-size: 32px;
font-weight: 400;
line-height: 1.3;
margin: 0 0 20px;
text-align: center;
}

h2 {
color: <?php echo esc_attr($heading_color); ?>;
display: block;
font-family: <?php echo $heading_font; ?>;
font-size: 24px;
font-weight: 400;
line-height: 1.4;
margin: 24px 0 16px;
text-align: left;
padding-bottom: 8px;
}

h3 {
color: <?php echo esc_attr($text); ?>;
display: block;
font-family: <?php echo $font_family; ?>;
font-size: 18px;
font-weight: 700;
line-height: 1.4;
margin: 16px 0 8px;
text-align: left;
}

a {
color: <?php echo esc_attr($link_color); ?>;
font-weight: 600;
text-decoration: none;
transition: color 0.3s ease;
}

a:hover {
color: #6B5B47;
text-decoration: underline;
}

/* Tableau des détails de commande */
#body_content .email-order-details {
background-color: #FDFCFA;
border-radius: 8px;
overflow: hidden;
margin: 20px 0;
}

#body_content .email-order-details th {
color: black;
font-weight: 600;
text-align: left;
padding: 16px 12px;
font-size: 14px;
letter-spacing: 0.5px;
}

#body_content .email-order-details tr{
border-bottom: 1px solid <?php echo esc_attr($border_color); ?>;
}

#body_content .email-order-details > td {
padding:5px;
vertical-align: top;
}

/* Centrer explicitement la colonne Quantité (2ᵉ colonne) */
#body_content .email-order-details th:nth-child(2),
#body_content .email-order-details td:nth-child(2) {
text-align: center !important;
}

#body_content .email-order-details tbody tr:last-child td {
padding-bottom: 24px;
}

#body_content .email-order-details tfoot tr:first-child td,
#body_content .email-order-details tfoot tr:first-child th {
padding-top: 24px;
}

#body_content .email-order-details .order-totals td,
#body_content .email-order-details .order-totals th {
font-weight: normal;
padding: 8px 12px;
font-size: 16px;
}

#body_content .email-order-details .order-totals-total th {
font-weight: bold;
font-size: 18px;
color: <?php echo esc_attr($heading_color); ?>;
}

#body_content .email-order-details .order-totals-total td {
font-weight: bold;
font-size: 24px;
color: <?php echo esc_attr($base); ?>;
}

/* Adresses */
.address {
background-color: #FDFCFA;
color: <?php echo esc_attr($text); ?>;
font-style: normal;
padding: 20px;
border-radius: 8px;
border: 1px solid <?php echo esc_attr($border_color); ?>;
margin: 16px 0;
}

.address-title {
color: <?php echo esc_attr($heading_color); ?>;
font-family: <?php echo $heading_font; ?>;
font-size: 18px;
font-weight: 400;
margin-bottom: 12px;
display: block;
}

#addresses {
margin: 24px 0;
}

#addresses td {
vertical-align: top;
width: 50%;
padding: 0 10px;
}

/* Footer */
#template_footer td {
padding: 0;
border-radius: 0;
background-color: #FDFCFA;
}

#template_footer #credit {
border: 0;
border-top: 2px solid <?php echo esc_attr($border_color); ?>;
color: <?php echo esc_attr($footer_text); ?>;
font-family: <?php echo $font_family; ?>;
font-size: 13px;
line-height: 1.6;
text-align: center;
padding: 32px;
background-color: #FDFCFA;
}

#template_footer #credit p {
margin: 0 0 8px;
}

#template_footer #credit a {
color: <?php echo esc_attr($link_color); ?>;
}

/* Bouton CTA */
.email-cta-button {
display: inline-block;
background-color: <?php echo esc_attr($base); ?>;
color: white !important;
padding: 14px 32px;
border-radius: 30px;
text-decoration: none;
font-weight: 600;
font-size: 16px;
margin: 24px 0;
transition: all 0.3s ease;
box-shadow: 0 4px 12px rgba(184, 163, 209, 0.3);
}

.email-cta-button:hover {
background-color: #D4C5E8;
box-shadow: 0 6px 16px rgba(184, 163, 209, 0.4);
transform: translateY(-2px);
}

.email-button-container {
text-align: center;
margin: 32px 0;
}

/* Infos importantes */
.info-box {
background: linear-gradient(135deg, #D4C5E8 0%, #E6D7C3 100%);
border-left: 4px solid <?php echo esc_attr($base); ?>;
padding: 20px;
margin: 24px 0;
border-radius: 4px;
}

.info-box p {
margin: 0;
color: #6B5B47;
font-weight: 500;
}

/* Media queries pour mobile */
@media screen and (max-width: 600px) {
#wrapper {
padding: 20px 10px !important;
}

#template_header {
padding: 24px 16px !important;
}

#template_header h1 {
font-size: 24px !important;
}

#body_content table td {
padding: 16px !important;
}

#body_content_inner {
font-size: 14px !important;
}

h2 {
font-size: 20px !important;
}

#addresses td {
display: block;
width: 100% !important;
padding: 0 !important;
margin-bottom: 16px;
}

.email-order-details th,
.email-order-details td {
font-size: 13px !important;
padding: 10px 8px !important;
}

.email-order-details .order-totals-total td {
font-size: 18px !important;
}
}