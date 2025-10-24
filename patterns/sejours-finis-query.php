<?php
/**
 * Title: Séjours Finis - Query Loop
 * Slug: taulignan/sejours-finis-query
 * Categories: sejours
 * Keywords: séjours, finis, événements, passés, query loop
 * Block Types: core/query
 * Description: Affiche les séjours terminés en utilisant le bloc Query Loop natif avec filtre personnalisé
 */
?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|large"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--large)">

    <!-- wp:heading {"textAlign":"center","level":2,"style":{"typography":{"fontSize":"2.5rem","fontWeight":"700"}},"textColor":"contrast"} -->
    <h2 class="wp-block-heading has-text-align-center has-contrast-color has-text-color" style="font-size:2.5rem;font-weight:700">Nos Séjours Passés</h2>
    <!-- /wp:heading -->

    <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.125rem"}},"textColor":"contrast-2"} -->
    <p class="has-text-align-center has-contrast-2-color has-text-color" style="font-size:1.125rem">Découvrez nos précédents séjours et événements</p>
    <!-- /wp:paragraph -->

    <!-- wp:spacer {"height":"2rem"} -->
    <div style="height:2rem" aria-hidden="true" class="wp-block-spacer"></div>
    <!-- /wp:spacer -->

    <!-- wp:query {"queryId":1,"query":{"perPage":9,"pages":0,"offset":0,"postType":"product","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"query-sejours-finis","layout":{"type":"default"}} -->
    <div class="wp-block-query query-sejours-finis">

        <!-- wp:post-template {"style":{"spacing":{"blockGap":"2rem"}},"layout":{"type":"grid","columnCount":3}} -->

        <!-- wp:acf/card-event {"data":{"card_style":"default","show_excerpt":true,"excerpt_length":20,"button_text":"Voir les détails","date_format":"j M Y","finish":true}} /-->

        <!-- /wp:post-template -->

        <!-- wp:query-no-results -->
        <!-- wp:paragraph {"align":"center","style":{"typography":{"fontStyle":"italic","fontSize":"1.125rem"}},"textColor":"contrast-2"} -->
        <p class="has-text-align-center has-contrast-2-color has-text-color" style="font-size:1.125rem;font-style:italic">Aucun séjour passé trouvé.</p>
        <!-- /wp:paragraph -->
        <!-- /wp:query-no-results -->

        <!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"3rem"}}}} -->
        <!-- wp:query-pagination-previous /-->
        <!-- wp:query-pagination-numbers /-->
        <!-- wp:query-pagination-next /-->
        <!-- /wp:query-pagination -->

    </div>
    <!-- /wp:query -->

</div>
<!-- /wp:group -->

