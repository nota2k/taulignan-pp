<?php

if (!defined('ABSPATH')) {
    exit;
}

// Contexte ACF du bloc
$images              = get_field('images') ?: [];
$image_ratio         = get_field('image_ratio') ?: 'auto';
$show_nav            = (bool) get_field('show_nav');
$show_pagination     = (bool) get_field('show_pagination');
$max_width           = trim((string) get_field('max_width'));
$slider_height       = trim((string) get_field('slider_height'));
$slides_per_view_raw = get_field('slides_per_view');
$space_between       = (int) get_field('space_between');
$loop                = (bool) get_field('loop');
$centered_slides     = (bool) get_field('centered_slides');
$grab_cursor         = (bool) get_field('grab_cursor');
$auto_height         = (bool) get_field('auto_height');
$speed               = (int) get_field('speed');
$effect              = get_field('effect') ?: 'slide';
$autoplay            = (bool) get_field('autoplay');
$autoplay_delay      = (int) get_field('autoplay_delay');

if (empty($images)) {
    if (is_admin()) {
        echo '<p>' . esc_html__('Aucune image sélectionnée pour le Slider Gallery.', 'taulignan') . '</p>';
    }
    return;
}

// Identifiant unique pour éviter conflits si plusieurs blocs
$block_id = 'slider-gallery-' . uniqid();

// Options Swiper sérialisées dans un data-attribute
$slides_per_view = is_numeric($slides_per_view_raw) ? (float) $slides_per_view_raw : (string) $slides_per_view_raw;
$swiper_options = array(
    'slidesPerView'  => ($slides_per_view === '' ? 1 : $slides_per_view),
    'spaceBetween'   => $space_between,
    'loop'           => $loop,
    'centeredSlides' => $centered_slides,
    'grabCursor'     => $grab_cursor,
    'autoHeight'     => $auto_height,
    'speed'          => $speed ?: 400,
    'effect'         => $effect,
);

if ($autoplay) {
    $swiper_options['autoplay'] = array(
        'delay' => $autoplay_delay ?: 3000,
        'disableOnInteraction' => false,
    );
}

// Styles inline pour largeur/hauteur
$style_parts = array('width:100%');
if (!empty($max_width)) {
    $style_parts[] = 'max-width:' . esc_attr($max_width);
}
if (!empty($slider_height)) {
    $style_parts[] = 'height:' . esc_attr($slider_height);
}
$wrapper_style = implode(';', $style_parts) . ';';
?>

<div id="<?php echo esc_attr($block_id); ?>" class="slider-gallery swiper" data-swiper='<?php echo wp_json_encode($swiper_options); ?>' style="<?php echo esc_attr($wrapper_style); ?>">
    <div class="swiper-wrapper">
        <?php foreach ($images as $img) :
            $url     = isset($img['url']) ? $img['url'] : '';
            $alt     = isset($img['alt']) ? $img['alt'] : '';
            $width   = isset($img['width']) ? (int) $img['width'] : 0;
        ?>
            <div class="swiper-slide">
                <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" />
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($show_pagination) : ?>
        <div class="swiper-pagination"></div>
    <?php endif; ?>

    <?php if ($show_nav) : ?>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    <?php endif; ?>
</div>