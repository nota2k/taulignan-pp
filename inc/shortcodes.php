<?php

/**
 * Shortcode pour afficher les informations de séjours
 */
function infosejours_shortcode($atts)
{
    // Vérifier qu'on est sur une page produit et que ACF est disponible
    if (!is_product() || !function_exists('get_field')) {
        return '';
    }

    ob_start();
?>

    <?php
    // Programme du séjour
    $programme_group = get_field('programme');
    if ($programme_group) :
    ?>
        <div class="sejour-field sejour-programme">
            <p class="field-title">Programme du séjour</p>
            <div class="field-content">
                <?php
                // Jours de la semaine
                $jours = array(
                    'vendredi' => 'Vendredi',
                    'samedi' => 'Samedi',
                    'dimanche' => 'Dimanche'
                );

                foreach ($jours as $jour_key => $jour_label) :
                    $repeater = $programme_group[$jour_key] ?? null;
                    if ($repeater && is_array($repeater)) :
                ?>
                        <div class="programme-jour">
                            <h4 class="jour-title"><?php echo esc_html($jour_label); ?></h4>
                            <ul class="programme-list">
                                <?php
                                foreach ($repeater as $item) {
                                    $programme_item = $item['programme'] ?? '';
                                    if (!empty($programme_item)) {
                                        echo '<li class="programme-item">';
                                        echo wp_kses_post($programme_item);
                                        echo '</li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php
    // Prix inclus
    $prix_inclus = get_field('prix_inclus');
    if ($prix_inclus) :
    ?>
        <div class="sejour-field sejour-inclus">
            <h3 class="field-title">Le prix comprend</h3>
            <div class="field-content">
                <?php
                if (is_array($prix_inclus)) {
                    echo '<ul class="prix-list">';
                    foreach ($prix_inclus as $item) {
                        echo '<li class="prix-item">' . wp_kses_post($item) . '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo wp_kses_post($prix_inclus);
                }
                ?>
            </div>
        </div>
    <?php endif; ?>

    <?php
    // Prix non inclus
    $prix_non_inclus = get_field('prix_non_inclus');
    if ($prix_non_inclus) :
    ?>
        <div class="sejour-field sejour-non-inclus">
            <h3 class="field-title">Non inclus</h3>
            <div class="field-content">
                <?php
                if (is_array($prix_non_inclus)) {
                    echo '<ul class="prix-list">';
                    foreach ($prix_non_inclus as $item) {
                        echo '<li class="prix-item">' . wp_kses_post($item) . '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo wp_kses_post($prix_non_inclus);
                }
                ?>
            </div>
        </div>
    <?php endif; ?>

    <?php
    // Informations pratiques
    $infos_pratiques = get_field('informations_pratiques');
    if ($infos_pratiques) :
    ?>
        <div class="sejour-field sejour-infos">
            <h3 class="field-title">Informations pratiques</h3>
            <div class="field-content">
                <?php echo wp_kses_post($infos_pratiques); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php
    // Galerie supplémentaire
    $galerie = get_field('galerie_supplementaire');
    if ($galerie && is_array($galerie)) :
    ?>
        <div class="sejour-field sejour-galerie">
            <h3 class="field-title">Plus d'images</h3>
            <div class="galerie-grid">
                <?php foreach ($galerie as $image) : ?>
                    <?php if (isset($image['sizes']['medium'])) : ?>
                        <div class="galerie-item">
                            <img src="<?php echo esc_url($image['sizes']['medium']); ?>"
                                alt="<?php echo esc_attr($image['alt']); ?>"
                                loading="lazy">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

<?php
    return ob_get_clean();
}
add_shortcode('infosejours', 'infosejours_shortcode');
