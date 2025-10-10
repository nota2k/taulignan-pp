<?php

/**
 * Template de rendu d'une card 'événement'
 * 
 * Ce fichier gère l'affichage d'une carte d'événement complète
 * avec image, titre, date du séjour, extrait et bouton de redirection
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Récupérer les paramètres du bloc
// Vérifier si les données sont passées par le bloc de boucle
global $card_event_data;
if (isset($card_event_data) && is_array($card_event_data)) {
    $card_style = $card_event_data['card_style'] ?? 'default';
    $show_excerpt = $card_event_data['show_excerpt'] ?? true;
    $excerpt_length = $card_event_data['excerpt_length'] ?? 20;
    $button_text = $card_event_data['button_text'] ?? 'Voir les détails';
    $date_format = $card_event_data['date_format'] ?? 'j M Y';
    $finish = $card_event_data['finish'] ?? false;
} else {
    // Utiliser les champs ACF normaux
    $card_style = get_field('card_style') ?: 'default';
    $show_excerpt = get_field('show_excerpt') ?: true;
    $excerpt_length = get_field('excerpt_length') ?: 20;
    $button_text = get_field('button_text') ?: 'Voir les détails';
    $date_format = get_field('date_format') ?: 'j M Y';
    $finish = get_field('finish') ?: false;
}

// Récupérer les données du post actuel
$post_id = get_the_ID();
$title = get_the_title();
$permalink = get_permalink();
$featured_image = get_the_post_thumbnail($post_id, 'medium_large', array('class' => 'card-event-image'));
$excerpt = get_the_excerpt();

// Récupérer et formater la date du séjour
$date_sejour = get_field('date_sejour', $post_id);
$formatted_date = '';

if ($date_sejour) {
    // Si c'est un array, prendre la première valeur
    if (is_array($date_sejour)) {
        $date_sejour = $date_sejour[0] ?? '';
    }

    // Convertir le format ACF (YYYYMMDD) en objet DateTime
    if ($date_sejour && strlen($date_sejour) === 8) {
        $date_obj = DateTime::createFromFormat('Ymd', $date_sejour);
        if ($date_obj) {
            $formatted_date = '' . $date_obj->format($date_format);
        } else {
            $formatted_date = '' . $date_sejour;
        }
    } else {
        $formatted_date = '' . $date_sejour;
    }
}
?>

<div class="card-event <?php echo esc_attr($card_style); ?> <?php echo $finish ? 'finished' : 'upcoming'; ?>">
    <div class="card-event-content">

        <?php if ($featured_image) : ?>
            <div class="card-event-image-wrapper">
                <a href="<?php echo esc_url($permalink); ?>" class="card-event-image-link">
                    <?php echo $featured_image; ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="card-event-body">

            <?php if ($title) : ?>
                <h3 class="card-event-title">
                    <a href="<?php echo esc_url($permalink); ?>" class="card-event-title-link">
                        <?php echo esc_html($title); ?>
                    </a>
                </h3>
                <hr class="wp-block-separator has-text-color has-custom-bleu-canard-color has-alpha-channel-opacity has-custom-bleu-canard-background-color has-background is-style-wide is-style-wide--3">
            <?php endif; ?>

            <?php if ($formatted_date) : ?>
                <p class="card-event-date">
                    <?php echo esc_html($formatted_date); ?>
                </p>
            <?php endif; ?>

            <?php if ($show_excerpt && $excerpt) : ?>
                <div class="card-event-excerpt">
                    <?php echo wp_trim_words($excerpt, $excerpt_length, '...'); ?>
                </div>
            <?php endif; ?>

            <div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-fill">
                <a href="<?php echo esc_url($permalink); ?>" class="wp-block-button__link has-custom-bleu-canard-background-color has-background wp-element-button">
                    <?php echo esc_html($button_text); ?> 
                </a>
            </div>

        </div>

    </div>
</div>