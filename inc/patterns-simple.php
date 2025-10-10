<?php
/**
 * Version simplifiée pour l'enregistrement des patterns
 */

// Empêcher l'accès direct
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enregistrer les patterns de manière simple et directe
 */
function taulignan_simple_register_patterns() {
    // Vérifier que la fonction existe
    if ( ! function_exists( 'register_block_pattern' ) ) {
        return;
    }

    // Pattern 1: Titre avec image ronde
    register_block_pattern(
        'taulignan/titre-image-rounded',
        array(
            'title' => 'Titre avec image ronde et texte',
            'description' => 'Section de présentation avec titre stylisé, image ronde et texte descriptif',
            'categories' => array( 'sections', 'featured' ),
            'keywords' => array( 'presentation', 'introduction', 'chateau', 'taulignan' ),
            'viewportWidth' => 1200,
            'content' => '<!-- wp:group {"tagName":"section","className":"bg-olive flex column relative","id":"presentation"} -->
<section class="wp-block-group bg-olive flex column relative" id="presentation">
    <!-- wp:group {"className":"title-block relative"} -->
    <div class="wp-block-group title-block relative">
        <!-- wp:heading {"level":2,"className":"title-block-wrapper"} -->
        <h2 class="wp-block-heading title-block-wrapper">
            <span>Un lieu d\'exception</span>
            <span>au cœur</span>
            <span>de la Provence</span>    
        </h2>
        <!-- /wp:heading -->
    </div>
    <!-- /wp:group -->

    <!-- wp:image {"className":"img-circle-container large-size border border-beige parallax","id":"img-circle-container","sizeSlug":"large","linkDestination":"none"} -->
    <figure class="wp-block-image img-circle-container large-size border border-beige parallax" id="img-circle-container">
        <img src="https://www.chateaudetaulignan.com/wp-content/uploads/2025/07/2023-09-Chateau-de-Taulignan-Exterieur-WEB-14.jpg" alt="Photo de l\'allée de platanes" data-speed="-0.5"/>
    </figure>
    <!-- /wp:image -->

    <!-- wp:paragraph {"className":"presentation-text"} -->
    <p class="presentation-text">Niché au cœur de la Provence, le Château de Taulignan vous offre un cadre exceptionnel pour tous vos événements. Entre histoire et modernité, découvrez un lieu unique.</p>
    <!-- /wp:paragraph -->
</section>
<!-- /wp:group -->'
        )
    );

    // Pattern 2: Test simple
    register_block_pattern(
        'taulignan/test-simple',
        array(
            'title' => 'Test Simple',
            'description' => 'Pattern de test simple',
            'categories' => array( 'featured' ),
            'keywords' => array( 'test', 'simple' ),
            'viewportWidth' => 800,
            'content' => '<!-- wp:group {"className":"test-simple-pattern"} -->
<div class="wp-block-group test-simple-pattern">
    <!-- wp:heading {"textAlign":"center"} -->
    <h2 class="wp-block-heading has-text-align-center">Test Simple</h2>
    <!-- /wp:heading -->
    
    <!-- wp:paragraph {"align":"center"} -->
    <p class="has-text-align-center">Ceci est un pattern de test simple.</p>
    <!-- /wp:paragraph -->
</div>
<!-- /wp:group -->'
        )
    );

    // Pattern 3: Debug test
    register_block_pattern(
        'taulignan/debug-test',
        array(
            'title' => 'Debug Test',
            'description' => 'Test de débogage',
            'categories' => array( 'featured' ),
            'keywords' => array( 'debug', 'test' ),
            'viewportWidth' => 600,
            'content' => '<!-- wp:paragraph -->
<p>Test de débogage</p>
<!-- /wp:paragraph -->'
        )
    );
}

// Enregistrer les patterns
add_action( 'init', 'taulignan_simple_register_patterns' );
