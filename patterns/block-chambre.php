<?php
/**
 * Title: Block Chambre
 * Slug: taulignan/block-chambre
 * Categories: chambres, presentation
 * Keywords: chambre, hôtel, galerie, confort
 * Block Types: core/group
 */
?>

<!-- wp:group {"className":"block-chambre","layout":{"type":"constrained"}} -->
<div class="wp-block-group block-chambre">
    
    <!-- wp:group {"className":"chambre flex gap-2","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
    <div class="wp-block-group chambre flex gap-2">
        
        <!-- wp:group {"className":"gallery-wrapper","layout":{"type":"constrained"}} -->
        <div class="wp-block-group gallery-wrapper">
            
            <!-- wp:group {"className":"grid-gallery","layout":{"type":"grid","columnCount":7}} -->
            <div class="wp-block-group grid-gallery" style="display:grid;grid-template-columns:repeat(7, 1fr);gap:1rem;">
                
                <!-- wp:image {"className":"minigallery-item item-1","style":{"gridColumn":"span 3"},"sizeSlug":"large"} -->
                <figure class="wp-block-image minigallery-item item-1" style="grid-column: span 3;">
                    <img src="<?php echo get_template_directory_uri(); ?>/resources/images/placeholder-chambre-1.jpg" alt="Vue principale de la chambre" />
                </figure>
                <!-- /wp:image -->
                
                <!-- wp:image {"className":"minigallery-item item-2","style":{"gridColumn":"span 2"},"sizeSlug":"medium"} -->
                <figure class="wp-block-image minigallery-item item-2" style="grid-column: span 2;">
                    <img src="<?php echo get_template_directory_uri(); ?>/resources/images/placeholder-chambre-2.jpg" alt="Détail de la chambre" />
                </figure>
                <!-- /wp:image -->
                
                <!-- wp:image {"className":"minigallery-item item-3","style":{"gridColumn":"span 2"},"sizeSlug":"medium"} -->
                <figure class="wp-block-image minigallery-item item-3" style="grid-column: span 2;">
                    <img src="<?php echo get_template_directory_uri(); ?>/resources/images/placeholder-chambre-3.jpg" alt="Autre vue de la chambre" />
                </figure>
                <!-- /wp:image -->
                
                <!-- wp:image {"className":"minigallery-item item-4","style":{"gridColumn":"span 3"},"sizeSlug":"large"} -->
                <figure class="wp-block-image minigallery-item item-4" style="grid-column: span 3;">
                    <img src="<?php echo get_template_directory_uri(); ?>/resources/images/placeholder-chambre-4.jpg" alt="Vue d'ensemble de la chambre" />
                </figure>
                <!-- /wp:image -->
                
            </div>
            <!-- /wp:group -->
            
        </div>
        <!-- /wp:group -->
        
        <!-- wp:group {"className":"info-chambre","layout":{"type":"constrained"}} -->
        <div class="wp-block-group info-chambre">
            
            <!-- wp:heading {"level":2,"className":"chambre-title"} -->
            <h2 class="wp-block-heading chambre-title">Chambre 1</h2>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph {"className":"superficie"} -->
            <p class="superficie"><strong>Superficie : </strong>25m²</p>
            <!-- /wp:paragraph -->
            
            <!-- wp:group {"className":"comfort-item flex align-center gap-1","layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group comfort-item flex align-center gap-1">
                <!-- wp:html -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wifi">
                    <path d="M12 20h.01" />
                    <path d="M2 8.82a15 15 0 0 1 20 0" />
                    <path d="M5 12.859a10 10 0 0 1 14 0" />
                    <path d="M8.5 16.429a5 5 0 0 1 7 0" />
                </svg>
                <!-- /wp:html -->
                <span>WiFi gratuit</span>
            </div>
            <!-- /wp:group -->
            
            <!-- wp:group {"className":"comfort-item flex align-center gap-1","layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group comfort-item flex align-center gap-1">
                <!-- wp:html -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-monitor">
                    <rect width="20" height="14" x="2" y="3" rx="2" />
                    <line x1="8" x2="16" y1="21" y2="21" />
                    <line x1="12" x2="12" y1="17" y2="21" />
                </svg>
                <!-- /wp:html -->
                <span>TV</span>
            </div>
            <!-- /wp:group -->
            
            <!-- wp:group {"className":"comfort-item flex align-center gap-1","layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group comfort-item flex align-center gap-1">
                <!-- wp:html -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-snowflake">
                    <line x1="2" x2="22" y1="12" y2="12" />
                    <line x1="12" x2="12" y1="2" y2="22" />
                    <path d="m20 16-4-4 4-4" />
                    <path d="m4 8 4 4-4 4" />
                    <path d="m16 4-4 4-4-4" />
                    <path d="m8 20 4-4 4 4" />
                </svg>
                <!-- /wp:html -->
                <span>Climatisation</span>
            </div>
            <!-- /wp:group -->
            
            <!-- wp:group {"className":"comfort-item flex align-center gap-1","layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group comfort-item flex align-center gap-1">
                <!-- wp:html -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shower-head">
                    <path d="m4 4 2.5 2.5" />
                    <path d="M13.5 6.5a4.95 4.95 0 0 0-7 7" />
                    <path d="M15 5 5 15" />
                    <path d="M14 17v.01" />
                    <path d="M10 16v.01" />
                    <path d="M13 13v.01" />
                    <path d="M16 10v.01" />
                    <path d="M11 20v.01" />
                    <path d="M17 14v.01" />
                    <path d="M20 11v.01" />
                </svg>
                <!-- /wp:html -->
                <span>Salle de bain et toilettes privatives</span>
            </div>
            <!-- /wp:group -->
            
        </div>
        <!-- /wp:group -->
        
    </div>
    <!-- /wp:group -->
    
</div>
<!-- /wp:group -->
