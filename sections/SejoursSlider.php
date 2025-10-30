<?php

namespace Sections;

use BlockSections\Section;

/**
 * Bloc Sejours Slider (autonome, peut être utilisé dans un template)
 */
class SejoursSlider extends Section
{
    /**
     * Initialize the section.
     */
    function init()
    {
        // The section name, wihtout "acf/app-" prefix. It will be added automatically
        // Full slug: "acf/app-sejours-slider"
        $this->name = 'sejours-slider';

        // Human readable section Title
        $this->title = __('Sejours Slider', 'taulignan');

        // The `$setting` for the section's root ACF block. See
        // https://www.advancedcustomfields.com/resources/acf_register_block_type/#settings
        $this->args = [
            'multiple' => true,
            'description' => __('Slider de séjours Swiper (autonome)', 'taulignan'),
            'keywords' => [
                'slider',
                'carousel',
                'swiper',
                'sejours',
            ],
            'category' => 'sejours-slider',
            'icon' => 'images-alt2',
        ];

        // Enregistrer les champs ACF pour ce bloc
        $this->register_acf_fields();
    }

    /**
     * Enregistrer les champs ACF pour le bloc
     */
    function register_acf_fields()
    {
        if (!function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group(array(
            'key' => 'group_sejours_slider',
            'title' => 'Paramètres du Slider de Séjours',
            'fields' => array(
                array(
                    'key' => 'field_slider_nombre',
                    'label' => 'Nombre de séjours',
                    'name' => 'nombre_sejours',
                    'type' => 'number',
                    'default_value' => 3,
                    'min' => 1,
                    'max' => 10,
                ),
                array(
                    'key' => 'field_slider_texte_bouton',
                    'label' => 'Texte du bouton',
                    'name' => 'texte_bouton_carte',
                    'type' => 'text',
                    'default_value' => 'Voir',
                ),
                array(
                    'key' => 'field_slider_afficher_date',
                    'label' => 'Afficher les dates',
                    'name' => 'afficher_date',
                    'type' => 'true_false',
                    'default_value' => 1,
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_slider_autoplay',
                    'label' => 'Lecture automatique',
                    'name' => 'autoplay',
                    'type' => 'true_false',
                    'default_value' => 1,
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_slider_delai',
                    'label' => 'Délai autoplay (ms)',
                    'name' => 'delai_autoplay',
                    'type' => 'number',
                    'default_value' => 5000,
                    'min' => 1000,
                    'max' => 15000,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_slider_autoplay',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/app-sejours-slider',
                    ),
                ),
            ),
        ));
    }

    /**
     * Render Section root element.
     */
    function render($block, $content, $is_preview, $post_id, $wp_block, $context)
    {
        // Récupérer les paramètres
        $nombre_sejours = get_field('nombre_sejours') ?: 3;
        $texte_bouton = get_field('texte_bouton_carte') ?: 'Voir';
        $afficher_date = get_field('afficher_date') !== false ? get_field('afficher_date') : true;
        $autoplay = get_field('autoplay') !== false ? get_field('autoplay') : true;
        $delai_autoplay = get_field('delai_autoplay') ?: 5000;

        // Récupérer les séjours
        $sejours_query = new \WP_Query(array(
            'post_type' => 'product',
            'posts_per_page' => $nombre_sejours,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish',
        ));

        if (!$sejours_query->have_posts()) {
            if ($is_preview) {
                echo '<p>' . __('Aucun séjour trouvé. (Aperçu de l\'éditeur)', 'taulignan') . '</p>';
            }
            return;
        }
?>

        <!-- Swiper Container -->
        <div class="swiper sejours-swiper"
            data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
            data-delay="<?php echo esc_attr($delai_autoplay); ?>">

            <!-- Swiper Wrapper -->
            <div class="swiper-wrapper">

                <?php while ($sejours_query->have_posts()) : $sejours_query->the_post();
                    $current_post_id = get_the_ID();
                    // Récupérer les données du séjour
                    $date_sejour = get_field('date_sejour', $current_post_id);
                    $date_formatted = '';

                    if ($afficher_date && $date_sejour) {
                        if (is_array($date_sejour)) {
                            $date_sejour = $date_sejour[0] ?? '';
                        }

                        if ($date_sejour && strlen($date_sejour) === 8) {
                            $date_obj = \DateTime::createFromFormat('Ymd', $date_sejour);
                            if ($date_obj) {
                                $date_formatted = $date_obj->format('j M Y');
                            }
                        }
                    }

                    // Récupérer le produit WooCommerce
                    $product = wc_get_product($current_post_id);

                    // Récupérer les variations/attributs du produit groupés par attribut
                    $variations_grouped = [];
                    $variation_stock_status = [];

                    if ($product) {
                        // Récupérer le statut de stock des variations via la fonction utilitaire
                        if ($product->is_type('variable')) {
                            $variation_stock_status = get_product_variations_stock_status($product);
                        }

                        $attributes = $product->get_attributes();

                        foreach ($attributes as $attribute) {
                            // On affiche tous les attributs visibles
                            if ($attribute->get_visible()) {
                                // Récupérer le nom de l'attribut
                                $attribute_label = wc_attribute_label($attribute->get_name());
                                $attribute_name = $attribute->get_name();

                                if ($attribute->is_taxonomy()) {
                                    // Attribut basé sur une taxonomie
                                    $terms = wp_get_post_terms($current_post_id, $attribute_name, ['fields' => 'all']);
                                    if (!empty($terms) && !is_wp_error($terms)) {
                                        $variations_grouped[$attribute_label] = [];
                                        foreach ($terms as $term) {
                                            // Vérifier si cette variation est en rupture de stock
                                            $is_out_of_stock = isset($variation_stock_status[$attribute_name][$term->slug])
                                                && $variation_stock_status[$attribute_name][$term->slug];

                                            // Filtrer : ne pas afficher si en rupture de stock
                                            if (!$is_out_of_stock) {
                                                $variations_grouped[$attribute_label][] = [
                                                    'name' => $term->name,
                                                    'slug' => $term->slug,
                                                    'out_of_stock' => false,
                                                ];
                                            }
                                        }
                                    }
                                } else {
                                    // Attribut personnalisé
                                    $options = $attribute->get_options();
                                    if (!empty($options)) {
                                        $variations_grouped[$attribute_label] = [];
                                        $option_values = is_array($options) ? $options : [$options];

                                        foreach ($option_values as $option) {
                                            $option_slug = sanitize_title($option);
                                            // Vérifier si cette variation est en rupture de stock
                                            $is_out_of_stock = isset($variation_stock_status[$attribute_name][$option_slug])
                                                && $variation_stock_status[$attribute_name][$option_slug];

                                            // Filtrer : ne pas afficher si en rupture de stock
                                            if (!$is_out_of_stock) {
                                                $variations_grouped[$attribute_label][] = [
                                                    'name' => $option,
                                                    'slug' => $option_slug,
                                                    'out_of_stock' => false,
                                                ];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // Récupérer les données du post
                    $image_url = get_the_post_thumbnail_url($current_post_id, 'large');
                    $title = get_the_title($current_post_id);
                    $excerpt = wp_trim_words(get_the_excerpt($current_post_id), 20, '...');
                    $permalink = get_permalink($current_post_id);
                ?>

                    <!-- Swiper Slide -->
                    <div class="swiper-slide sejour-slide">

                        <div class="sejour-slide-content">

                            <figure class="sejour-image">
                                <?php if ($image_url) : ?>
                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy" />
                                <?php else : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/resources/images/placeholder.jpg" alt="<?php echo esc_attr($title); ?>" loading="lazy" />
                                <?php endif; ?>
                            </figure>

                            <div class="sejour-content">

                                <h2 class="sejour-title"><?php echo esc_html($title); ?></h2>

                                <?php if (!empty($variations_grouped)) : ?>
                                    <div class="sejour-variations-wrapper">
                                        <?php foreach ($variations_grouped as $label => $values) : ?>
                                            <?php if (!empty($values)) : ?>
                                                <div class="sejour-variation-group">
                                                    <span class="variation-label"><?php echo esc_html($label); ?></span>
                                                    <div class="sejour-variations-container">
                                                        <?php foreach ($values as $value) :
                                                            // Récupérer le nom à afficher
                                                            $display_name = is_array($value) ? $value['name'] : $value;
                                                        ?>
                                                            <p class="sejour-variation">
                                                                <span class="variation-value"><?php echo esc_html($display_name); ?></span>
                                                            </p>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <hr class="sejour-separator" />

                                <?php if ($date_formatted) : ?>
                                    <p class="sejour-date"><?php echo esc_html($date_formatted); ?></p>
                                <?php endif; ?>

                                <p class="sejour-excerpt"><?php echo esc_html($excerpt); ?></p>

                                <div class="sejour-button-wrapper">
                                    <a href="<?php echo esc_url($permalink); ?>" class="wp-block-button__link has-blanc-color has-custom-bleu-canard-background-color has-text-color has-background wp-element-button">
                                        <?php echo esc_html($texte_bouton); ?>
                                    </a>
                                </div>

                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>

            </div>

            <!-- Swiper Pagination -->
            <div class="swiper-pagination"></div>

        </div>

<?php
    }
}
