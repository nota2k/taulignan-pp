<?php
/**
 * Interface d'administration pour la génération automatique de dates
 * 
 * @package Taulignan
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Ajouter une page dans le menu WordPress
add_action('admin_menu', 'taulignan_automatic_dates_menu');

function taulignan_automatic_dates_menu() {
    add_submenu_page(
        'tools.php',                           // Menu parent (Outils)
        'Génération de dates automatique',    // Titre de la page
        'Dates automatiques',                  // Titre du menu
        'manage_options',                      // Capacité requise
        'taulignan-automatic-dates',          // Slug du menu
        'taulignan_automatic_dates_page'      // Fonction d'affichage
    );
}

// Afficher la page d'administration
function taulignan_automatic_dates_page() {
    // Vérifier les permissions
    if (!current_user_can('manage_options')) {
        wp_die('Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
    }

    // Traiter les actions
    if (isset($_POST['taulignan_action'])) {
        check_admin_referer('taulignan_automatic_dates_action');
        
        switch ($_POST['taulignan_action']) {
            case 'generate':
                $end_month = isset($_POST['end_month']) ? intval($_POST['end_month']) : 12;
                $result = taulignan_execute_automatic_dates($end_month);
                break;
                
            case 'reset':
                delete_option('weekend_attributes_script_executed_once');
                delete_option('taulignan_automatic_dates_last_execution');
                $result = array(
                    'success' => true,
                    'message' => 'Le compteur a été réinitialisé. Vous pouvez maintenant régénérer les dates.'
                );
                break;
                
            case 'delete_all':
                $result = taulignan_delete_all_date_attributes();
                break;
        }
        
        // Afficher le message de résultat
        if (isset($result)) {
            $class = $result['success'] ? 'notice-success' : 'notice-error';
            echo '<div class="notice ' . $class . ' is-dismissible"><p><strong>' . esc_html($result['message']) . '</strong></p>';
            if (!empty($result['details'])) {
                echo '<ul style="margin-left: 20px;">';
                foreach ($result['details'] as $detail) {
                    echo '<li>' . esc_html($detail) . '</li>';
                }
                echo '</ul>';
            }
            echo '</div>';
        }
    }

    // Récupérer le statut actuel
    $execution_flag = get_option('weekend_attributes_script_executed_once');
    $last_execution = get_option('taulignan_automatic_dates_last_execution');
    $current_year = date('Y');
    $current_month = date('n');

    ?>
    <div class="wrap">
        <h1>🗓️ Génération automatique de dates</h1>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>📊 Statut actuel</h2>
            
            <?php if ($execution_flag): ?>
                <div class="notice notice-info inline">
                    <p><strong>✅ Script déjà exécuté</strong></p>
                    <p>Dernière exécution : <?php echo date('d/m/Y à H:i:s', $execution_flag); ?></p>
                    <?php if ($last_execution): ?>
                        <p>Paramètres : jusqu'au mois <strong><?php echo $last_execution['end_month']; ?></strong> de l'année <strong><?php echo $last_execution['year']; ?></strong></p>
                        <p>Résultat : <?php echo $last_execution['attributes_created']; ?> attribut(s) créé(s), <?php echo $last_execution['terms_created']; ?> date(s) générée(s)</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="notice notice-warning inline">
                    <p><strong>⚠️ Le script n'a jamais été exécuté</strong></p>
                    <p>Aucune date automatique n'a été générée pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>🚀 Actions disponibles</h2>
            
            <form method="post" style="margin-bottom: 20px;">
                <?php wp_nonce_field('taulignan_automatic_dates_action'); ?>
                <input type="hidden" name="taulignan_action" value="generate">
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="end_month">Générer jusqu'au mois</label>
                        </th>
                        <td>
                            <select name="end_month" id="end_month" class="regular-text">
                                <?php
                                $mois = array(
                                    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
                                    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
                                    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                                );
                                
                                for ($m = 1; $m <= 12; $m++) {
                                    $selected = ($m == 12) ? 'selected' : '';
                                    echo '<option value="' . $m . '" ' . $selected . '>' . $mois[$m] . ' ' . $current_year . '</option>';
                                }
                                ?>
                            </select>
                            <p class="description">
                                Génère tous les weekends du mois actuel jusqu'au mois sélectionné.<br>
                                <strong>Note :</strong> Cela créera un attribut par mois avec tous les samedis correspondants.
                            </p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <button type="submit" class="button button-primary button-large" <?php echo $execution_flag ? '' : ''; ?>>
                        <span class="dashicons dashicons-calendar-alt" style="margin-top: 3px;"></span>
                        <?php echo $execution_flag ? 'Régénérer les dates' : 'Générer les dates'; ?>
                    </button>
                </p>
                
                <?php if ($execution_flag): ?>
                    <div class="notice notice-warning inline" style="margin-top: 10px;">
                        <p><strong>⚠️ Attention :</strong> Le script a déjà été exécuté. Il ne créera que les nouveaux attributs/dates qui n'existent pas encore.</p>
                    </div>
                <?php endif; ?>
            </form>

            <hr style="margin: 30px 0;">

            <h3>🔧 Outils de gestion</h3>

            <?php if ($execution_flag): ?>
                <form method="post" style="display: inline-block; margin-right: 10px;">
                    <?php wp_nonce_field('taulignan_automatic_dates_action'); ?>
                    <input type="hidden" name="taulignan_action" value="reset">
                    <button type="submit" class="button" onclick="return confirm('Êtes-vous sûr de vouloir réinitialiser le compteur ?');">
                        <span class="dashicons dashicons-update" style="margin-top: 3px;"></span>
                        Réinitialiser le compteur
                    </button>
                </form>
            <?php endif; ?>

            <form method="post" style="display: inline-block;">
                <?php wp_nonce_field('taulignan_automatic_dates_action'); ?>
                <input type="hidden" name="taulignan_action" value="delete_all">
                <button type="submit" class="button button-link-delete" onclick="return confirm('⚠️ ATTENTION : Cette action supprimera TOUS les attributs de dates générés automatiquement. Les variations de produits associées seront également supprimées.\n\nÊtes-vous absolument sûr ?');">
                    <span class="dashicons dashicons-trash" style="margin-top: 3px;"></span>
                    Supprimer tous les attributs de dates
                </button>
            </form>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>ℹ️ Informations</h2>
            
            <h3>Comment ça fonctionne ?</h3>
            <ol style="line-height: 1.8;">
                <li>Le script génère automatiquement tous les <strong>weekends</strong> (vendredi-samedi-dimanche)</li>
                <li>Les weekends sont groupés par <strong>mois</strong> (ex: "Octobre 2025", "Novembre 2025"...)</li>
                <li>Chaque mois devient un <strong>attribut WooCommerce</strong></li>
                <li>Chaque samedi devient une <strong>variation</strong> au format <code>dd/MM/YYYY</code> (ex: 14/10/2025)</li>
                <li>Vous pouvez ensuite utiliser ces attributs dans vos produits séjours</li>
            </ol>

            <h3>Où trouver les attributs créés ?</h3>
            <p>Allez dans <strong>Produits > Attributs</strong> pour voir tous les attributs générés (octobre-2025, novembre-2025, etc.)</p>

            <h3>Comment les utiliser dans un produit ?</h3>
            <ol style="line-height: 1.8;">
                <li>Éditez un produit</li>
                <li>Allez dans l'onglet <strong>Attributs</strong></li>
                <li>Ajoutez l'attribut du mois souhaité (ex: "Octobre 2025")</li>
                <li>Cochez "Utilisé pour les variations"</li>
                <li>Enregistrez</li>
                <li>Allez dans l'onglet <strong>Variations</strong></li>
                <li>Créez les variations pour les dates souhaitées</li>
            </ol>

            <div class="notice notice-info inline" style="margin-top: 20px;">
                <p><strong>💡 Astuce :</strong> Ce système vous évite de créer manuellement des centaines de variations de dates. Pratique pour gérer les réservations de séjours !</p>
            </div>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>🔗 Liens utiles</h2>
            <ul style="line-height: 2;">
                <li><a href="<?php echo admin_url('edit.php?post_type=product&page=product_attributes'); ?>" class="button">📋 Voir tous les attributs</a></li>
                <li><a href="<?php echo admin_url('edit.php?post_type=product'); ?>" class="button">🛍️ Gérer les produits</a></li>
            </ul>
        </div>
    </div>

    <style>
        .card h3 {
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .button .dashicons {
            float: left;
            margin-right: 5px;
        }
        .notice.inline {
            padding: 12px;
            margin: 15px 0;
        }
    </style>
    <?php
}

// Fonction pour exécuter la génération de dates
function taulignan_execute_automatic_dates($end_month) {
    global $wpdb;
    
    if (!function_exists('wc_create_attribute')) {
        return array(
            'success' => false,
            'message' => 'WooCommerce n\'est pas actif.'
        );
    }

    $current_year = date('Y');
    
    // Inclure les fonctions nécessaires
    require_once get_template_directory() . '/inc/automatic-dates.php';
    
    // Exécuter la génération
    $result = create_monthly_weekend_attributes($end_month, $current_year);
    
    if ($result['success']) {
        // Enregistrer les informations de cette exécution
        update_option('weekend_attributes_script_executed_once', time(), false);
        update_option('taulignan_automatic_dates_last_execution', array(
            'end_month' => $end_month,
            'year' => $current_year,
            'attributes_created' => $result['attributes_created'],
            'terms_created' => $result['terms_created'],
            'timestamp' => time()
        ), false);
    }
    
    return $result;
}

// Fonction pour supprimer tous les attributs de dates
function taulignan_delete_all_date_attributes() {
    global $wpdb;
    
    $deleted_attributes = 0;
    $deleted_terms = 0;
    
    // Récupérer tous les attributs qui correspondent au pattern mois-année
    $attributes = $wpdb->get_results("
        SELECT attribute_id, attribute_name, attribute_label 
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies 
        WHERE attribute_name REGEXP '^[a-z]+-[0-9]{4}$'
    ");
    
    if (empty($attributes)) {
        return array(
            'success' => true,
            'message' => 'Aucun attribut de date à supprimer.',
            'details' => array()
        );
    }
    
    $details = array();
    
    foreach ($attributes as $attribute) {
        $taxonomy_name = wc_attribute_taxonomy_name($attribute->attribute_name);
        
        // Compter les termes avant suppression
        $terms = get_terms(array(
            'taxonomy' => $taxonomy_name,
            'hide_empty' => false,
        ));
        
        $term_count = is_array($terms) ? count($terms) : 0;
        
        // Supprimer tous les termes de cette taxonomie
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                wp_delete_term($term->term_id, $taxonomy_name);
                $deleted_terms++;
            }
        }
        
        // Supprimer l'attribut
        $wpdb->delete(
            $wpdb->prefix . 'woocommerce_attribute_taxonomies',
            array('attribute_id' => $attribute->attribute_id),
            array('%d')
        );
        
        $deleted_attributes++;
        $details[] = sprintf('✓ %s : supprimé (%d date(s))', $attribute->attribute_label, $term_count);
    }
    
    // Vider les caches
    delete_transient('wc_attribute_taxonomies');
    delete_option('weekend_attributes_script_executed_once');
    delete_option('taulignan_automatic_dates_last_execution');
    wp_cache_flush();
    
    return array(
        'success' => true,
        'message' => sprintf('%d attribut(s) et %d date(s) supprimé(s)', $deleted_attributes, $deleted_terms),
        'details' => $details
    );
}

