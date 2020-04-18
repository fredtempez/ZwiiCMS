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
            <h4>Configuration des vignettes</h4>
                <div class="row">
                    <div class="col4">
                        <?php echo template::select('galleryThemeThumbAlign', $module::$galleryThemeFlexAlign, [
							'label' => 'Alignement des vignettes sur la page :',
							'selected' => $this->getData(['theme', $this->getUrl(0),'thumbAlign'])
						]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('galleryThemeThumbWidth', $module::$galleryThemeSize, [
                            'label' => 'Largeur des vignettes :',
                            'selected' => $this->getData(['theme', $this->getUrl(0),'thumbWidth'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('galleryThemeThumbHeight', $module::$galleryThemeSize, [
                            'label' => 'Hauteur des vignettes :',
                            'selected' => $this->getData(['theme', $this->getUrl(0),'thumbHeight'])
                        ]); ?>
                    </div>                  
                </div>
                <div class="row">
                    <div class="col3">
                        <?php echo template::select('galleryThemeThumbMargin', $module::$galleryThemeMargin, [
                            'label' => 'Marges autour des vignettes :',
                            'selected' => $this->getData(['theme', $this->getUrl(0),'thumbMargin'])
                        ]); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::select('galleryThemeThumbBorder', $module::$galleryThemeBorder, [
                            'label' => 'Epaisseur des bordures',
                            'selected' => $this->getData(['theme', $this->getUrl(0),'thumbBorder'])
                        ]); ?>
                    </div>
                    <div class="col3">
                        <?php echo template::text('galleryThemeThumbBorderColor', [
                            'class' => 'colorPicker',
                            'help' => 'Le curseur horizontal règle le niveau de transparence.',							
                            'label' => 'Couleur de la bordure',
                            'value' => $this->getData(['theme', $this->getUrl(0),'ThumbBorderColor'])
                        ]); ?>
                    </div>            
                    <div class="col3">
                        <?php echo template::select('galleryThemeThumbOpacity', $module::$galleryThemeOpacity, [
                            'label' => 'Opacité au survol',
                            'selected' => $this->getData(['theme', $this->getUrl(0),'thumbOpacity'])
                        ]); ?>
                    </div>
                </div>
            </div>           
        </div>        
    </div>
    <div class="row">
        <div class="col12">
            <div class="block">
            <h4>Configuration des légendes</h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::select('galleryThemeLegendHeight', $module::$galleryThemeLegendHeight, [
                        'label' => 'Hauteur des légendes :',
                        'selected' => $this->getData(['theme', $this->getUrl(0),'legendHeight'])
                    ]); ?>
                </div>
                <div class="col6">
                        <?php echo template::select('galleryThemeLegendAlign', $module::$galleryThemeAlign, [
							'label' => 'Alignement des légendes dans les vignettes :',
							'selected' => $this->getData(['theme', $this->getUrl(0),'legendAlign'])
						]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <?php echo template::text('galleryThemeLegendTextColor', [
                        'class' => 'colorPicker',
                        'help' => 'Le curseur horizontal règle le niveau de transparence.',							
                        'label' => 'Couleur du texte de légende',
                        'value' => $this->getData(['theme', $this->getUrl(0),'legendTextColor'])
                    ]); ?>	
                </div>
                <div class="col6">
                    <?php echo template::text('galleryThemeLegendBgColor', [
                        'class' => 'colorPicker',
                        'help' => 'Le curseur horizontal règle le niveau de transparence.',							
                        'label' => 'Couleur du fond de la légende',
                        'value' => $this->getData(['theme', $this->getUrl(0),'legendBgColor'])
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