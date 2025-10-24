<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package taulignan
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function taulignan_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'taulignan_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function taulignan_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'taulignan_pingback_header' );

// functions.php du thème enfant
function sejour_acf_shortcode()
{
	if (! function_exists('get_field')) return '';
	ob_start();
	// Ton rendu ACF (date, programme, prix...) — réutilise le code sécurisé que tu as
	// Exemple simple :
	$date = get_field('date');
	if ($date) {
		$d = DateTime::createFromFormat('Ymd', $date) ?: date_create($date);
		echo '<div class="sejour-date">' . esc_html($d->format('j M Y')) . '</div>';
	}
	return ob_get_clean();
}
add_shortcode('sejour_acf', 'sejour_acf_shortcode');

/**
 * Récupère toutes les métadonnées d'un séjour (produit WooCommerce)
 * 
 * Cette fonction centralise la récupération de toutes les données d'un séjour,
 * incluant les données de base, les métadonnées WooCommerce, les champs ACF personnalisés,
 * les images et les taxonomies.
 * 
 * @param int|null $post_id ID du séjour (optionnel, utilise le post courant si null)
 * @return array|false Tableau des métadonnées du séjour ou false si le post n'existe pas
 * 
 * @example
 * // Récupérer les données du séjour courant
 * $sejour_data = get_sejour_metadata();
 * 
 * // Récupérer les données d'un séjour spécifique
 * $sejour_data = get_sejour_metadata(123);
 * 
 * // Afficher le titre
 * echo $sejour_data['title'];
 * 
 * // Afficher le prix formaté
 * echo $sejour_data['woocommerce']['price_html'];
 * 
 * // Afficher la date du séjour
 * if ($sejour_data['acf']['date_sejour']) {
 *     echo $sejour_data['acf']['date_sejour']['formatted'];
 * }
 */
function get_sejour_metadata($post_id = null) {
	// Si pas de post_id fourni, utiliser le post courant
	if (!$post_id) {
		$post_id = get_the_ID();
	}
	
	// Vérifier que le post existe
	$post = get_post($post_id);
	if (!$post || $post->post_type !== 'product') {
		return false;
	}
	
	// Initialiser le tableau de données
	$metadata = array();
	
	// ============================================================================
	// DONNÉES DE BASE DU POST
	// ============================================================================
	$metadata['id'] = $post_id;
	$metadata['title'] = get_the_title($post_id);
	$metadata['slug'] = $post->post_name;
	$metadata['permalink'] = get_permalink($post_id);
	$metadata['excerpt'] = get_the_excerpt($post_id);
	$metadata['content'] = get_the_content(null, false, $post_id);
	$metadata['date_published'] = get_the_date('Y-m-d H:i:s', $post_id);
	$metadata['date_modified'] = get_the_modified_date('Y-m-d H:i:s', $post_id);
	$metadata['author_id'] = $post->post_author;
	$metadata['author_name'] = get_the_author_meta('display_name', $post->post_author);
	$metadata['status'] = $post->post_status;
	
	// ============================================================================
	// IMAGES
	// ============================================================================
	$metadata['images'] = array(
		'featured' => array(
			'full' => get_the_post_thumbnail_url($post_id, 'full'),
			'large' => get_the_post_thumbnail_url($post_id, 'large'),
			'medium' => get_the_post_thumbnail_url($post_id, 'medium'),
			'thumbnail' => get_the_post_thumbnail_url($post_id, 'thumbnail'),
			'id' => get_post_thumbnail_id($post_id),
			'alt' => get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true),
		),
	);
	
	// ============================================================================
	// DONNÉES WOOCOMMERCE
	// ============================================================================
	if (class_exists('WC_Product')) {
		$product = wc_get_product($post_id);
		
		if ($product) {
			$metadata['woocommerce'] = array(
				// Prix
				'price' => $product->get_price(),
				'regular_price' => $product->get_regular_price(),
				'sale_price' => $product->get_sale_price(),
				'price_html' => $product->get_price_html(),
				'on_sale' => $product->is_on_sale(),
				
				// Stock
				'in_stock' => $product->is_in_stock(),
				'stock_status' => $product->get_stock_status(),
				'stock_quantity' => $product->get_stock_quantity(),
				'manage_stock' => $product->get_manage_stock(),
				
				// Informations générales
				'sku' => $product->get_sku(),
				'type' => $product->get_type(),
				'is_virtual' => $product->is_virtual(),
				'is_downloadable' => $product->is_downloadable(),
				'is_purchasable' => $product->is_purchasable(),
				
				// Descriptions
				'short_description' => $product->get_short_description(),
				
				// Dimensions et poids
				'weight' => $product->get_weight(),
				'length' => $product->get_length(),
				'width' => $product->get_width(),
				'height' => $product->get_height(),
				
				// Liens
				'add_to_cart_url' => $product->add_to_cart_url(),
				'add_to_cart_text' => $product->add_to_cart_text(),
			);
		}
	}
	
	// ============================================================================
	// TAXONOMIES (CATÉGORIES ET TAGS)
	// ============================================================================
	$metadata['taxonomies'] = array(
		'categories' => array(),
		'tags' => array(),
	);
	
	// Catégories de produits
	$product_cats = get_the_terms($post_id, 'product_cat');
	if ($product_cats && !is_wp_error($product_cats)) {
		foreach ($product_cats as $cat) {
			$metadata['taxonomies']['categories'][] = array(
				'id' => $cat->term_id,
				'name' => $cat->name,
				'slug' => $cat->slug,
				'description' => $cat->description,
				'url' => get_term_link($cat),
			);
		}
	}
	
	// Tags de produits
	$product_tags = get_the_terms($post_id, 'product_tag');
	if ($product_tags && !is_wp_error($product_tags)) {
		foreach ($product_tags as $tag) {
			$metadata['taxonomies']['tags'][] = array(
				'id' => $tag->term_id,
				'name' => $tag->name,
				'slug' => $tag->slug,
				'description' => $tag->description,
				'url' => get_term_link($tag),
			);
		}
	}
	
	// ============================================================================
	// CHAMPS ACF PERSONNALISÉS
	// ============================================================================
	$metadata['acf'] = array();
	
	if (function_exists('get_field')) {
		// Récupérer tous les champs ACF
		$acf_fields = get_fields($post_id);
		
		if ($acf_fields) {
			$metadata['acf'] = $acf_fields;
			
			// Traitement spécial pour date_sejour
			if (isset($acf_fields['date_sejour'])) {
				$date_sejour = $acf_fields['date_sejour'];
				
				// Si c'est un array, prendre la première valeur
				if (is_array($date_sejour)) {
					$date_sejour = $date_sejour[0] ?? '';
				}
				
				// Convertir et formater la date
				if ($date_sejour && strlen($date_sejour) === 8) {
					$date_obj = DateTime::createFromFormat('Ymd', $date_sejour);
					
					if ($date_obj) {
						$metadata['acf']['date_sejour'] = array(
							'raw' => $date_sejour,
							'object' => $date_obj,
							'formatted' => $date_obj->format('j F Y'),
							'short' => $date_obj->format('d/m/Y'),
							'iso' => $date_obj->format('Y-m-d'),
							'timestamp' => $date_obj->getTimestamp(),
						);
					}
				}
			}
		}
	}
	
	// ============================================================================
	// GALERIE WOOCOMMERCE
	// ============================================================================
	if (class_exists('WC_Product')) {
		$product = wc_get_product($post_id);
		
		if ($product) {
			$gallery_ids = $product->get_gallery_image_ids();
			$metadata['images']['gallery'] = array();
			
			if ($gallery_ids) {
				foreach ($gallery_ids as $gallery_id) {
					$metadata['images']['gallery'][] = array(
						'id' => $gallery_id,
						'full' => wp_get_attachment_image_url($gallery_id, 'full'),
						'large' => wp_get_attachment_image_url($gallery_id, 'large'),
						'medium' => wp_get_attachment_image_url($gallery_id, 'medium'),
						'thumbnail' => wp_get_attachment_image_url($gallery_id, 'thumbnail'),
						'alt' => get_post_meta($gallery_id, '_wp_attachment_image_alt', true),
						'caption' => wp_get_attachment_caption($gallery_id),
					);
				}
			}
		}
	}
	
	return $metadata;
}

/**
 * Affiche les métadonnées d'un séjour de manière formatée
 * 
 * @param int|null $post_id ID du séjour (optionnel)
 * @param array $fields Liste des champs à afficher (optionnel, affiche tout par défaut)
 * @return void
 */
function display_sejour_metadata($post_id = null, $fields = array()) {
	$metadata = get_sejour_metadata($post_id);
	
	if (!$metadata) {
		echo '<p>Aucune métadonnée disponible pour ce séjour.</p>';
		return;
	}
	
	// Si des champs spécifiques sont demandés
	if (!empty($fields)) {
		foreach ($fields as $field) {
			// Navigation dans les sous-tableaux avec la notation "woocommerce.price"
			$keys = explode('.', $field);
			$value = $metadata;
			
			foreach ($keys as $key) {
				if (isset($value[$key])) {
					$value = $value[$key];
				} else {
					$value = null;
					break;
				}
			}
			
			if ($value !== null) {
				echo '<div class="sejour-field">';
				echo '<strong>' . esc_html($field) . ':</strong> ';
				
				if (is_array($value)) {
					echo '<pre>' . esc_html(print_r($value, true)) . '</pre>';
				} else {
					echo esc_html($value);
				}
				
				echo '</div>';
			}
		}
	} else {
		// Afficher toutes les métadonnées
		echo '<div class="sejour-metadata">';
		echo '<pre>' . esc_html(print_r($metadata, true)) . '</pre>';
		echo '</div>';
	}
}

/**
 * Récupère le statut de stock des variations d'un produit variable
 * 
 * Cette fonction retourne un tableau associatif avec le statut de stock
 * pour chaque variation d'un produit variable.
 * 
 * @param int|WC_Product_Variable $product ID du produit ou objet produit
 * @return array Tableau avec la structure [nom_attribut][valeur_attribut] => bool (true si en rupture)
 * 
 * @example
 * $stock_status = get_product_variations_stock_status($product_id);
 * if (isset($stock_status['pa_date']['2025-01-15']) && $stock_status['pa_date']['2025-01-15']) {
 *     echo 'Cette date est complète';
 * }
 */
function get_product_variations_stock_status($product)
{
	// Récupérer l'objet produit si un ID est fourni
	if (is_numeric($product)) {
		$product = wc_get_product($product);
	}

	// Vérifier que c'est bien un produit variable
	if (!$product || !$product->is_type('variable')) {
		return [];
	}

	$variation_stock_status = [];

	// Récupérer toutes les variations disponibles
	/** @var WC_Product_Variable $product */
	$available_variations = $product->get_available_variations();

	foreach ($available_variations as $variation_data) {
		$variation_obj = wc_get_product($variation_data['variation_id']);

		if (!$variation_obj) {
			continue;
		}

		// Récupérer les attributs de cette variation
		$variation_attributes = $variation_data['attributes'];

		// Pour chaque attribut de la variation, enregistrer son statut de stock
		foreach ($variation_attributes as $attr_key => $attr_value) {
			// Nettoyer le nom de l'attribut (enlever le préfixe "attribute_")
			$clean_attr_key = str_replace('attribute_', '', $attr_key);

			// Vérifier le stock : si stock_quantity = 0 ou stock_status = 'outofstock'
			$is_out_of_stock = false;

			if ($variation_obj->managing_stock()) {
				$stock_quantity = $variation_obj->get_stock_quantity();
				$is_out_of_stock = ($stock_quantity !== null && $stock_quantity <= 0);
			} else {
				$is_out_of_stock = !$variation_obj->is_in_stock();
			}

			// Stocker le statut pour cette valeur d'attribut
			if (!isset($variation_stock_status[$clean_attr_key])) {
				$variation_stock_status[$clean_attr_key] = [];
			}
			$variation_stock_status[$clean_attr_key][$attr_value] = $is_out_of_stock;
		}
	}

	return $variation_stock_status;
}

/**
 * Filtre pour masquer les options de variation en rupture de stock
 * dans les dropdowns WooCommerce et ajouter la classe "out-of-order"
 */
add_filter('woocommerce_dropdown_variation_attribute_options_html', 'filter_and_mark_out_of_stock_variations', 10, 2);

function filter_and_mark_out_of_stock_variations($html, $args)
{
	global $product;

	if (!$product || !$product->is_type('variable')) {
		return $html;
	}

	// Récupérer le statut de stock des variations
	$stock_status = get_product_variations_stock_status($product);

	// Récupérer le nom de l'attribut actuel
	$attribute = $args['attribute'];

	// Si on a des informations de stock pour cet attribut
	if (isset($stock_status[$attribute])) {
		// Parser le HTML pour filtrer et marquer les options
		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
		libxml_clear_errors();

		$select = $doc->getElementsByTagName('select')->item(0);
		if ($select) {
			$options = $select->getElementsByTagName('option');
			$options_to_remove = [];

			// Parcourir les options
			foreach ($options as $option) {
				$value = $option->getAttribute('value');

				// Si cette option est en rupture de stock
				if ($value && isset($stock_status[$attribute][$value]) && $stock_status[$attribute][$value]) {
					// Ajouter la classe "out-of-order"
					$existing_class = $option->getAttribute('class');
					$new_class = trim($existing_class . ' out-of-order');
					$option->setAttribute('class', $new_class);

					// Marquer pour suppression (filtrage)
					$options_to_remove[] = $option;
				}
			}

			// Supprimer les options en rupture de stock
			foreach ($options_to_remove as $option) {
				$option->parentNode->removeChild($option);
			}

			$html = $doc->saveHTML();
		}
	}

	return $html;
}

/**
 * Récupère le stock de toutes les variations d'un produit
 * 
 * @param int|WC_Product_Variable|null $product ID du produit, objet produit ou null pour utiliser le produit global
 * @return array Tableau avec la structure [attribute_name][attribute_value] => stock_quantity
 */
function get_stock_variations_from_product($product = null)
{
	// Si pas de produit fourni, utiliser le produit global
	if ($product === null) {
		global $product;
	}
	
	// Récupérer l'objet produit si un ID est fourni
	if (is_numeric($product)) {
		$product = wc_get_product($product);
	}

	// Vérifier que c'est bien un produit variable
	if (!$product || !$product->is_type('variable')) {
		return [];
	}

	$stock_data = [];

	/** @var WC_Product_Variable $product */
	$available_variations = $product->get_available_variations();

	foreach ($available_variations as $variation) {
		$variation_id = $variation['variation_id'];
		$variation_obj = wc_get_product($variation_id);

		if (!$variation_obj) {
			continue;
		}

		// Récupérer le stock
		$stock = $variation_obj->get_stock_quantity();
		if ($stock === null) {
			$stock = $variation_obj->is_in_stock() ? 999 : 0;
		}

		// Récupérer les attributs de cette variation
		$variation_attributes = $variation['attributes'];

		// Pour chaque attribut de la variation
		foreach ($variation_attributes as $attr_key => $attr_value) {
			// Nettoyer le nom de l'attribut (enlever le préfixe "attribute_")
			$clean_attr_key = str_replace('attribute_', '', $attr_key);

			// Stocker le stock pour cette combinaison d'attributs
			if (!isset($stock_data[$clean_attr_key])) {
				$stock_data[$clean_attr_key] = [];
			}

			// Si cette valeur d'attribut existe déjà, additionner le stock
			if (isset($stock_data[$clean_attr_key][$attr_value])) {
				$stock_data[$clean_attr_key][$attr_value] += $stock;
			} else {
				$stock_data[$clean_attr_key][$attr_value] = $stock;
			}
		}
	}

	return $stock_data;
}

/**
 * Filtre pour ajouter des attributs data et classes aux options de variation
 */
add_filter('woocommerce_dropdown_variation_attribute_options_html', 'add_out_of_order_to_variation_options', 10, 2);

function add_out_of_order_to_variation_options($html, $args)
{
	global $product;

	if (!$product || !$product->is_type('variable')) {
		return $html;
	}

	// Récupérer le stock des variations
	$stock_data = get_stock_variations_from_product($product);

	// Récupérer le nom de l'attribut actuel
	$attribute = $args['attribute'];

	// Si on a des informations de stock pour cet attribut
	if (isset($stock_data[$attribute])) {
		// Parser le HTML pour ajouter la classe et les attributs data
		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
		libxml_clear_errors();

		$select = $doc->getElementsByTagName('select')->item(0);
		if ($select) {
			$options = $select->getElementsByTagName('option');
			$options_to_remove = [];

			// Parcourir les options
			foreach ($options as $option) {
				$value = $option->getAttribute('value');

				// Si cette option a un stock défini
				if ($value && isset($stock_data[$attribute][$value])) {
					$stock = $stock_data[$attribute][$value];

					// Ajouter l'attribut data-stock
					$option->setAttribute('data-stock', $stock);

					// Si stock == 0, ajouter la classe "out-of-order"
					if ($stock == 0) {
						$existing_class = $option->getAttribute('class');
						$new_class = trim($existing_class . ' out-of-order');
						$option->setAttribute('class', $new_class);

						// Marquer pour suppression (filtrage)
						$options_to_remove[] = $option;
					}
				}
			}

			// Supprimer les options avec stock = 0
			foreach ($options_to_remove as $option) {
				$option->parentNode->removeChild($option);
			}

			$html = $doc->saveHTML();
		}
	}

	return $html;
}

/**
 * Ajouter des attributs data-stock sur les pills WooCommerce Blocks
 * via les données de variation
 */
add_filter('woocommerce_available_variation', 'add_stock_data_to_variation', 10, 3);

function add_stock_data_to_variation($variation_data, $product, $variation)
{
	// Ajouter le stock dans les données de variation
	$stock = $variation->get_stock_quantity();
	if ($stock === null) {
		$stock = $variation->is_in_stock() ? 999 : 0;
	}

	$variation_data['stock_quantity'] = $stock;
	$variation_data['is_out_of_order'] = ($stock == 0);
	
	// Ajouter aussi un indicateur dans les attributs pour le JavaScript
	if ($stock == 0) {
		$variation_data['availability_html'] = '<p class="stock out-of-stock out-of-order">Complet</p>';
	}

	return $variation_data;
}

/**
 * Hook pour ajouter du JavaScript inline avec les données de stock
 */
add_action('wp_footer', 'add_variation_stock_data_to_page', 20);

function add_variation_stock_data_to_page()
{
	// Vérifier si on est sur une page produit
	if (!is_product()) {
		return;
	}

	global $product;

	if (!$product || !$product->is_type('variable')) {
		return;
	}

	// Récupérer les données de stock
	$stock_data = get_stock_variations_from_product($product);

	// Convertir en JSON pour JavaScript
	$stock_json = wp_json_encode($stock_data);

	// Ajouter un script inline avec les données
	?>
	<script type="text/javascript">
		window.taulignanVariationStockData = <?php echo $stock_json; ?>;
		console.log('[Taulignan PHP] Stock data injecté:', window.taulignanVariationStockData);
	</script>
	<?php
}

/**
 * Filtre pour modifier le rendu du bloc "Add to Cart" de WooCommerce
 * et ajouter des attributs data-stock sur les options de variation
 */
add_filter('render_block', 'modify_woocommerce_variation_block_output', 10, 2);

function modify_woocommerce_variation_block_output($block_content, $block)
{
	// Vérifier si c'est un bloc WooCommerce lié aux variations
	if (
		!isset($block['blockName']) ||
		(strpos($block['blockName'], 'woocommerce/add-to-cart-with-options') === false &&
		 strpos($block['blockName'], 'woocommerce/product-button') === false)
	) {
		return $block_content;
	}

	// Récupérer le produit courant
	global $product;

	if (!$product || !$product->is_type('variable')) {
		return $block_content;
	}

	// Récupérer les données de stock
	$stock_data = get_stock_variations_from_product($product);

	// Si pas de données de stock, retourner le contenu tel quel
	if (empty($stock_data)) {
		return $block_content;
	}

	// Parser le HTML pour ajouter des attributs data-stock
	$doc = new DOMDocument();
	libxml_use_internal_errors(true);
	
	// Ajouter un wrapper pour éviter les erreurs de parsing
	$wrapped_content = '<?xml encoding="UTF-8"><div>' . $block_content . '</div>';
	$doc->loadHTML($wrapped_content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
	libxml_clear_errors();

	// Chercher tous les inputs radio des variations
	$xpath = new DOMXPath($doc);
	$inputs = $xpath->query('//input[@type="radio"][@name]');

	foreach ($inputs as $input) {
		$input_name = $input->getAttribute('name');
		$input_value = $input->getAttribute('value');

		// Nettoyer le nom de l'attribut
		$clean_attr_name = str_replace('attribute_', '', $input_name);

		// Vérifier si on a des données de stock pour cet attribut
		if (isset($stock_data[$clean_attr_name][$input_value])) {
			$stock = $stock_data[$clean_attr_name][$input_value];

			// Ajouter l'attribut data-stock sur l'input
			$input->setAttribute('data-stock', $stock);

			// Si stock == 0, ajouter data-out-of-stock et classe
			if ($stock == 0) {
				$input->setAttribute('data-out-of-stock', 'true');

				// Trouver le parent (pill) pour ajouter la classe
				$parent = $input->parentNode;
				while ($parent && $parent->nodeName !== 'div') {
					$parent = $parent->parentNode;
				}

				if ($parent) {
					$existing_class = $parent->getAttribute('class');
					if (strpos($existing_class, 'out-of-order') === false) {
						$parent->setAttribute('class', trim($existing_class . ' out-of-order'));
					}
					$parent->setAttribute('data-stock', '0');
				}

				// Trouver le label pour ajouter la classe aussi
				$label = $xpath->query('.//label', $parent)->item(0);
				if ($label) {
					$existing_label_class = $label->getAttribute('class');
					if (strpos($existing_label_class, 'out-of-order') === false) {
						$label->setAttribute('class', trim($existing_label_class . ' out-of-order'));
					}
				}
			}
		}
	}

	// Récupérer le contenu modifié
	$modified_content = '';
	$body = $doc->getElementsByTagName('div')->item(0);
	if ($body) {
		foreach ($body->childNodes as $child) {
			$modified_content .= $doc->saveHTML($child);
		}
	}

	return $modified_content ?: $block_content;
}

/**
 * Ajouter des classes CSS sur les blocs WooCommerce en fonction du contexte
 */
add_filter('render_block_woocommerce/add-to-cart-with-options', 'add_stock_classes_to_variation_block', 10, 3);

function add_stock_classes_to_variation_block($block_content, $block, $instance)
{
	global $product;

	if (!$product || !$product->is_type('variable')) {
		return $block_content;
	}

	// Récupérer les données de stock
	$stock_data = get_stock_variations_from_product($product);

	// Ajouter un attribut data pour que JavaScript puisse utiliser
	$data_attr = 'data-variation-stock="' . esc_attr(wp_json_encode($stock_data)) . '"';
	
	// Injecter l'attribut dans le wrapper principal
	$block_content = preg_replace(
		'/class="([^"]*wc-block[^"]*)"/i',
		'class="$1" ' . $data_attr,
		$block_content,
		1
	);

	return $block_content;
}