<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Template pour afficher un séjour individuel (single product)
 * 
 * Ce template remplace le template par défaut de WooCommerce
 * pour afficher une page de séjour personnalisée.
 * 
 * @package Taulignan_U_Child
 * @since 1.0.0
 */
?>

<!-- wp:template-part {"slug":"header","theme":"taulignan-u-child"} /-->

<!-- wp:group {"tagName":"main","layout":{"inherit":true,"type":"constrained"}} -->
<main class="wp-block-group"><!-- wp:woocommerce/breadcrumbs /-->

<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|small"}}}} -->
<div class="wp-block-columns"><!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%"><!-- wp:post-featured-image /-->

<!-- wp:woocommerce/product-details {"hideTabTitle":true,"className":"is-style-minimal"} -->
<div class="wp-block-woocommerce-product-details alignwide is-style-minimal"></div>
<!-- /wp:woocommerce/product-details --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:post-title {"level":1,"style":{"elements":{"link":{"color":{"text":"var:preset|color|custom-bleu-canard"}}}},"textColor":"custom-bleu-canard","fontSize":"x-large","__woocommerceNamespace":"woocommerce/product-query/product-title"} /-->

<!-- wp:woocommerce/product-price {"isDescendentOfSingleProductTemplate":true,"textColor":"custom-bleu-canard","fontSize":"large","style":{"elements":{"link":{"color":{"text":"var:preset|color|custom-bleu-canard"}}}}} /-->

<!-- wp:post-excerpt {"excerptLength":100,"__woocommerceNamespace":"woocommerce/product-query/product-summary"} /-->

<!-- wp:woocommerce/add-to-cart-with-options /-->

<!-- wp:woocommerce/product-meta -->
<div class="wp-block-woocommerce-product-meta"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:woocommerce/product-sku /-->

<!-- wp:post-terms {"term":"product_cat","prefix":"Category: "} /-->

<!-- wp:post-terms {"term":"product_tag","prefix":"Tags: "} /--></div>
<!-- /wp:group -->

<!-- wp:post-terms {"term":"product_brand","prefix":"Marques : "} /--></div>
<!-- /wp:woocommerce/product-meta --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"className":"sejour-custom-fields"} -->
<div class="wp-block-group sejour-custom-fields">
<?php if (function_exists('get_field')) : ?>
    
    <?php $date = get_field('date'); if ($date) : ?>
    <!-- wp:group {"className":"sejour-field sejour-duree"} -->
    <div class="wp-block-group sejour-field sejour-duree">
        <!-- wp:heading {"level":3,"className":"field-title"} -->
        <h3 class="wp-block-heading field-title">Date du séjour</h3>
        <!-- /wp:heading -->
        <!-- wp:paragraph {"className":"field-content"} -->
        <p class="field-content"><?php echo esc_html($date); ?></p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->
    <?php endif; ?>

    <?php $programme = get_field('programme'); if ($programme) : ?>
    <!-- wp:group {"className":"sejour-field sejour-services"} -->
    <div class="wp-block-group sejour-field sejour-services">
        <!-- wp:heading {"level":3,"className":"field-title"} -->
        <h3 class="wp-block-heading field-title">Programme du séjour</h3>
        <!-- /wp:heading -->
        <!-- wp:html -->
        <div class="field-content"><?php echo wp_kses_post($programme); ?></div>
        <!-- /wp:html -->
    </div>
    <!-- /wp:group -->
    <?php endif; ?>

<?php endif; ?>
</div>
<!-- /wp:group -->

</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","theme":"taulignan-u-child"} /-->