<?php
// Sections et composants
\Sections\ThemeLayout::register();

// Bloc slider de séjours (autonome, peut être utilisé seul ou dans un template)
\Sections\SejoursSlider::register();

// Section complète avec 2 colonnes (utilise SejoursSlider dans le template)
\Sections\SejourSliderSection::register();
