<?php
/**
 * Template de rendu du bloc Date du Séjour
 * 
 * Ce fichier gère l'affichage du bloc ACF pour la date du séjour
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Récupérer les paramètres du bloc
$date_format = get_field('date_format') ?: 'j M Y';
$show_icon = get_field('show_icon');
$custom_prefix = get_field('custom_prefix');

// Récupérer et formater la date du séjour
$date_obj = get_formatted_date_sejour();

// Déterminer le préfixe à afficher
$prefix = '';
if ($custom_prefix) {
    $prefix = $custom_prefix . ' ';
} elseif ($show_icon) {
    $prefix = '📅 ';
}

// Afficher la date si elle existe
if ($date_obj instanceof DateTime) {
    echo '<p class="sejour-date has-contrast-2-color has-text-color" style="font-size:0.875rem;font-weight:600">';
    echo esc_html($prefix . $date_obj->format($date_format));
    echo '</p>';
} elseif ($date_obj) {
    // Fallback pour les dates non formatées
    echo '<p class="sejour-date has-contrast-2-color has-text-color" style="font-size:0.875rem;font-weight:600">';
    echo esc_html($prefix . $date_obj);
    echo '</p>';
}
?>
