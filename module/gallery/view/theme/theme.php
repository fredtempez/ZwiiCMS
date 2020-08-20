<?php echo template::formOpen('galleryThemeForm'); ?>

	<div class="row">
		<div class="col2">
			<?php echo template::button('galleryThemeBack', [
				'class' => 'buttonGrey',
				'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
				'ico' => 'left',
				'value' => 'Retour'
			]); ?>
		</div>
		<div class="col2 offset8">
			<?php echo template::submit('galleryThemeBack'); ?>
		</div>
	</div>
    <div class="row">
        <div class="col12">
            <div class="block">
                <h4>Vignettes
                    <?php 
                        echo template::help('Les paramètres du thème sont communs aux modules du même type.'); 
                    ?>
                </h4>
                <div class="row">
                    <div class="col3">
                        <?php echo template::select('galleryThemeThumbWidth', $module::$galleryThemeSizeWidth, [
                            'label' => 'Largeur',
                            'selected' => $this->getData(['theme', 'gallery','thumbWidth'])
                        ]); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::select('galleryThemeThumbHeight', $module::$galleryThemeSizeHeight, [
                            'label' => 'Hauteur',
                            'selected' => $this->getData(['theme', 'gallery','thumbHeight'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('galleryThemeThumbAlign', $module::$galleryThemeFlexAlign, [
							'label' => 'Alignement',
							'selected' => $this->getData(['theme', 'gallery','thumbAlign'])
						]); ?>
                    </div>
                    <div class="col2">
                        <?php echo template::select('galleryThemeThumbMargin', $module::$galleryThemeMargin, [
                            'label' => 'Marge',
                            'selected' => $this->getData(['theme', 'gallery','thumbMargin'])
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col4">
                        <?php echo template::select('galleryThemeThumbBorder', $module::$galleryThemeBorder, [
                            'label' => 'Bordure',
                            'selected' => $this->getData(['theme', 'gallery','thumbBorder'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::text('galleryThemeThumbBorderColor', [
                            'class' => 'colorPicker',
                            'help' => 'Le curseur horizontal règle le niveau de transparence.',
                            'label' => 'Couleur de la bordure',
                            'value' => $this->getData(['theme', 'gallery','thumbBorderColor'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('galleryThemeThumbRadius', $module::$galleryThemeRadius, [
                            'label' => 'Arrondi des angles',
                            'selected' => $this->getData(['theme', 'gallery','thumbRadius'])
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col4">
                        <?php echo template::select('galleryThemeThumbShadows', $module::$galleryThemeShadows, [
                            'label' => 'Ombre',
                            'selected' => $this->getData(['theme', 'gallery','thumbShadows'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::text('galleryThemeThumbShadowsColor', [
                            'class' => 'colorPicker',
                            'help' => 'Le curseur horizontal règle le niveau de transparence.',
                            'label' => 'Couleur de l\'ombre',
                            'value' => $this->getData(['theme', 'gallery','thumbShadowsColor'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('galleryThemeThumbOpacity', $module::$galleryThemeOpacity, [
                            'label' => 'Opacité au survol',
                            'selected' => $this->getData(['theme', 'gallery','thumbOpacity'])
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col12">
            <div class="block">
            <h4>Légendes
                <?php 
                    echo template::help('Les paramètres du thème sont communs aux modules du même type.'); 
				?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::text('galleryThemeLegendTextColor', [
                        'class' => 'colorPicker',
                        'help' => 'Le curseur horizontal règle le niveau de transparence.',
                        'label' => 'Texte',
                        'value' => $this->getData(['theme', 'gallery','legendTextColor'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::text('galleryThemeLegendBgColor', [
                        'class' => 'colorPicker',
                        'help' => 'Le curseur horizontal règle le niveau de transparence.',
                        'label' => 'Fond',
                        'value' => $this->getData(['theme', 'gallery','legendBgColor'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('galleryThemeLegendHeight', $module::$galleryThemeLegendHeight, [
                        'label' => 'Hauteur',
                        'selected' => $this->getData(['theme', 'gallery','legendHeight'])
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('galleryThemeLegendAlign', $module::$galleryThemeAlign, [
                        'label' => 'Alignement',
                        'selected' => $this->getData(['theme', 'gallery','legendAlign'])
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

<?php echo template::formClose(); ?>
<div class="row">
    <div class="col12">
        <div class="moduleVersion">Version n°
            <?php echo $module::GALLERY_VERSION; ?>
        </div>
    </div>
</div>