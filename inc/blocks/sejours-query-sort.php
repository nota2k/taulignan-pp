<?php
/**
 * Template pour le bloc ACF Query Loop Séjours avec Tri
 * 
 * @package Taulignan_U_Child
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Récupérer les valeurs des champs ACF
$query_title = get_field('query_title') ?: 'Nos Séjours';
$posts_per_page = get_field('posts_per_page') ?: 6;
$default_sort = get_field('default_sort') ?: 'event_finish_desc';
$show_sort_options = get_field('show_sort_options') ?: true;
$sort_options = get_field('sort_options') ?: array('event_finish_desc', 'date_sejour_asc', 'title_asc', 'price_asc');
$filter_by_date = get_field('filter_by_date') ?: 'active';
$layout_style = get_field('layout_style') ?: 'grid';
$show_pagination = get_field('show_pagination') ?: true;
$empty_message = get_field('empty_message') ?: 'Aucun séjour trouvé pour le moment.';

// Récupérer les paramètres de tri depuis l'URL (pour le tri dynamique)
$current_sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : $default_sort;
$current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;

// Construire la requête WP_Query
$query_args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => $posts_per_page,
    'paged' => $current_page,
    'meta_query' => array(
        'relation' => 'AND',
    ),
);

// Ajouter le filtre par statut selon le champ event_finish
if ($filter_by_date !== 'all') {
    switch ($filter_by_date) {
        case 'active':
            $query_args['meta_query'][] = array(
                'key' => 'event_finish',
                'value' => 'oui',
                'compare' => '!='
            );
            break;
            
        case 'finished':
            $query_args['meta_query'][] = array(
                'key' => 'event_finish',
                'value' => 'oui',
                'compare' => '='
            );
            break;
            
        case 'upcoming':
            $today = date('Ymd');
            $query_args['meta_query'][] = array(
                'key' => 'date_sejour',
                'value' => $today,
                'compare' => '>=',
                'type' => 'DATE'
            );
            break;
            
        case 'past':
            $today = date('Ymd');
            $query_args['meta_query'][] = array(
                'key' => 'date_sejour',
                'value' => $today,
                'compare' => '<',
                'type' => 'DATE'
            );
            break;
    }
}

// Ajouter le tri selon le critère sélectionné
switch ($current_sort) {
    case 'event_finish_asc':
        $query_args['meta_key'] = 'event_finish';
        $query_args['orderby'] = 'meta_value';
        $query_args['order'] = 'ASC';
        break;
        
    case 'event_finish_desc':
        $query_args['meta_key'] = 'event_finish';
        $query_args['orderby'] = 'meta_value';
        $query_args['order'] = 'DESC';
        break;
        
    case 'price_asc':
        $query_args['meta_key'] = '_price';
        $query_args['orderby'] = 'meta_value_num';
        $query_args['order'] = 'ASC';
        break;
        
    case 'price_desc':
        $query_args['meta_key'] = '_price';
        $query_args['orderby'] = 'meta_value_num';
        $query_args['order'] = 'DESC';
        break;
        
    case 'date_sejour_asc':
        $query_args['meta_key'] = 'date_sejour';
        $query_args['orderby'] = 'meta_value';
        $query_args['order'] = 'ASC';
        break;
        
    case 'date_sejour_desc':
        $query_args['meta_key'] = 'date_sejour';
        $query_args['orderby'] = 'meta_value';
        $query_args['order'] = 'DESC';
        break;
        
    case 'title_asc':
        $query_args['orderby'] = 'title';
        $query_args['order'] = 'ASC';
        break;
        
    case 'title_desc':
        $query_args['orderby'] = 'title';
        $query_args['order'] = 'DESC';
        break;
}

// Exécuter la requête
$sejours_query = new WP_Query($query_args);

// Classes CSS pour le style d'affichage
$container_classes = array(
    'sejours-query-sort',
    'layout-' . $layout_style,
    'filter-' . $filter_by_date
);

if ($show_sort_options) {
    $container_classes[] = 'has-sort-options';
}

?>

<div class="<?php echo esc_attr(implode(' ', $container_classes)); ?>">
    
    <?php if ($query_title) : ?>
        <div class="sejours-query-header">
            <h2 class="sejours-query-title"><?php echo esc_html($query_title); ?></h2>
        </div>
    <?php endif; ?>
    
    <?php if ($show_sort_options && !empty($sort_options)) : ?>
        <div class="sejours-sort-controls">
            <form method="get" class="sejours-sort-form">
                <label for="sejours-sort-select">Trier par :</label>
                <select name="sort" id="sejours-sort-select" class="sejours-sort-select">
                    <?php
                    $sort_labels = array(
                        'event_finish_asc' => 'Statut événement (terminé → actif)',
                        'event_finish_desc' => 'Statut événement (actif → terminé)',
                        'date_sejour_asc' => 'Date du séjour (croissant)',
                        'date_sejour_desc' => 'Date du séjour (décroissant)',
                        'title_asc' => 'Titre (A-Z)',
                        'title_desc' => 'Titre (Z-A)',
                        'price_asc' => 'Prix (croissant)',
                        'price_desc' => 'Prix (décroissant)',
                    );
                    
                    foreach ($sort_options as $option) :
                        if (isset($sort_labels[$option])) :
                    ?>
                        <option value="<?php echo esc_attr($option); ?>" <?php selected($current_sort, $option); ?>>
                            <?php echo esc_html($sort_labels[$option]); ?>
                        </option>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </select>
                
                <?php
                // Préserver les autres paramètres GET
                foreach ($_GET as $key => $value) {
                    if ($key !== 'sort' && $key !== 'paged') {
                        echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
                    }
                }
                ?>
                
                <noscript>
                    <button type="submit" class="sejours-sort-submit">Appliquer</button>
                </noscript>
            </form>
        </div>
    <?php endif; ?>
    
    <div class="sejours-query-results">
        <?php if ($sejours_query->have_posts()) : ?>
            
            <div class="sejours-grid sejours-grid-<?php echo esc_attr($layout_style); ?>">
                <?php while ($sejours_query->have_posts()) : $sejours_query->the_post(); ?>
                    <?php
                    $post_id = get_the_ID();
                    $product = wc_get_product($post_id);
                    $event_finish = get_field('event_finish', $post_id);
                    $periode = get_field('periode', $post_id);
                    
                    // Formater la période si elle existe
                    $periode_formatted = '';
                    if ($periode) {
                        try {
                            // Essayer différents formats de date, en priorité dd/MM/YY
                            $formats = ['d/m/y', 'd/m/Y', 'Y-m-d', 'd-m-Y', 'm/d/Y', 'Y/m/d'];
                            $date_obj = null;
                            
                            foreach ($formats as $format) {
                                $date_obj = DateTimeImmutable::createFromFormat($format, $periode);
                                if ($date_obj !== false) {
                                    break;
                                }
                            }
                            
                            if ($date_obj !== false) {
                                // Formater pour afficher seulement le mois et l'année en toutes lettres
                                $periode_formatted = $date_obj->format('F Y');
                            } else {
                                // Si aucun format ne fonctionne, utiliser la valeur brute
                                $periode_formatted = $periode;
                            }
                        } catch (Exception $e) {
                            // En cas d'erreur, utiliser la valeur brute
                            $periode_formatted = $periode;
                        }
                    }
                    
                    // Préparer les données pour le bloc card-event
                    $card_event_data = array(
                        'card_style' => $layout_style === 'list' ? 'compact' : 'default',
                        'show_excerpt' => true,
                        'excerpt_length' => 20,
                        'button_text' => 'Voir les détails',
                        'date_format' => 'j M Y',
                        'finish' => ($event_finish === 'oui'),
                        'event_finish' => $event_finish
                    );
                    
                    // Rendre le bloc card-event
                    ob_start();
                    include get_template_directory() . '/inc/blocks/card-event.php';
                    $card_content = ob_get_clean();
                    
                    // Ajouter les informations supplémentaires (periode et event_finish)
                    if ($periode_formatted || $event_finish) {
                        $additional_info = '<div class="sejour-additional-info">';
                        
                        // Afficher le champ ACF periode formaté
                        if ($periode_formatted) {
                            $additional_info .= '<div class="sejour-card-periode">
                                <span class="sejour-periode-label">Période:</span>
                                <span class="sejour-periode-value">' . esc_html($periode_formatted) . '</span>
                            </div>';
                        }
                        
                        if ($event_finish) {
                            $status_class = ($event_finish === 'oui') ? 'status-finished' : 'status-active';
                            $status_icon = ($event_finish === 'oui') ? '✅' : '🔄';
                            $status_text = ($event_finish === 'oui') ? 'Terminé' : 'Actif';
                            
                            $additional_info .= '<div class="sejour-card-status ' . $status_class . '">
                                <span class="sejour-status-icon">' . $status_icon . '</span>
                                ' . $status_text . '
                            </div>';
                        }
                        
                        $additional_info .= '</div>';
                        
                        
                        // Insérer les informations supplémentaires après la date ou avant l'extrait
                        if (strpos($card_content, 'card-event-date') !== false) {
                            // Insérer après la date
                            $card_content = preg_replace(
                                '/(<p class="card-event-date">.*?<\/p>)/',
                                '$1' . $additional_info,
                                $card_content
                            );
                        } else {
                            // Insérer avant l'extrait si pas de date
                            $card_content = str_replace(
                                '<div class="card-event-excerpt">',
                                $additional_info . '<div class="card-event-excerpt">',
                                $card_content
                            );
                        }
                    } else {
                        // Debug si aucune information supplémentaire
                        if (current_user_can('administrator')) {
                            echo '<!-- Debug: Aucune information supplémentaire à afficher -->';
                        }
                    }
                    
                    echo $card_content;
                    ?>
                    
                <?php endwhile; ?>
            </div>
            
            <?php if ($show_pagination && $sejours_query->max_num_pages > 1) : ?>
                <div class="sejours-pagination">
                    <?php
                    $pagination_args = array(
                        'total' => $sejours_query->max_num_pages,
                        'current' => $current_page,
                        'format' => '?paged=%#%',
                        'show_all' => false,
                        'type' => 'list',
                        'end_size' => 2,
                        'mid_size' => 1,
                        'prev_next' => true,
                        'prev_text' => '&laquo; Précédent',
                        'next_text' => 'Suivant &raquo;',
                        'add_args' => false,
                        'add_fragment' => '',
                    );
                    
                    // Ajouter le paramètre de tri à la pagination
                    if ($current_sort !== $default_sort) {
                        $pagination_args['add_args'] = array('sort' => $current_sort);
                    }
                    
                    echo paginate_links($pagination_args);
                    ?>
                </div>
            <?php endif; ?>
            
        <?php else : ?>
            <div class="sejours-empty">
                <p class="sejours-empty-message"><?php echo esc_html($empty_message); ?></p>
            </div>
        <?php endif; ?>
        
    </div>
    
</div>

<?php
// Réinitialiser les données de post
wp_reset_postdata();
?>

<script>
// Tri automatique avec JavaScript (améliore l'UX)
document.addEventListener('DOMContentLoaded', function() {
    const sortSelect = document.getElementById('sejours-sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
});
</script>
