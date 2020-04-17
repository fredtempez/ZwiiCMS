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
            <h4>Vignettes</h4>
                <div class="row">
                    <div class="col4">
                        <?php echo template::select('galleryThemeThumbAlign', $module::$galleryThemeAlign, [
							'label' => 'Alignement des vignettes sur la page :',
							'selected' => $this->getData(['module', $this->getUrl(0),$this->getUrl(1),'theme','thumbAlign'])
						]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('galleryThemeThumbWidth', $module::$galleryThemeSize, [
                            'label' => 'Largeur des vignettes :',
                            'selected' => $this->getData(['module', $this->getUrl(0),$this->getUrl(1),'theme','thumbWidth'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('galleryThemeThumbHeight', $module::$galleryThemeSize, [
                            'label' => 'Hauteur des vignettes :',
                            'selected' => $this->getData(['module', $this->getUrl(0),$this->getUrl(1),'theme','legendHeight'])
                        ]); ?>
                    </div>                  
                </div>
                <div class="row">
                    <div class="col4">
                        <?php echo template::select('galleryThemeThumbMargin', $module::$galleryThemeMargin, [
                            'label' => 'Marges autour des vignettes :',
                            'selected' => $this->getData(['module', $this->getUrl(0),$this->getUrl(1),'theme','thumbMargin'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('galleryThemeThumbBorder', $module::$galleryThemeBorder, [
                            'label' => 'Epaisseur des bordures',
                            'selected' => $this->getData(['module', $this->getUrl(0),$this->getUrl(1),'theme','thumbBorder'])
                        ]); ?>
                    </div>
                    <div class="col4">
                        <?php echo template::select('galleryThemeThumbOpacity', $module::$galleryThemeOpacity, [
                            'label' => 'Effet d\'opacité au survol',
                            'selected' => $this->getData(['module', $this->getUrl(0),$this->getUrl(1),'theme','thumbOpacity'])
                        ]); ?>
                    </div>
                </div>
            </div>           
        </div>        
    </div>
    <div class="row">
        <div class="col12">
            <div class="block">
            <h4>Légendes</h4>
            <div class="row">
                <div class="col6">
                    <?php echo template::select('galleryThemelegendHeight', $module::$galleryThemeLegendHeight, [
                        'label' => 'Hauteur des légendes :',
                        'selected' => $this->getData(['module', $this->getUrl(0),$this->getUrl(1),'theme','legendHeight'])
                    ]); ?>
                </div>
                <div class="col6">
                        <?php echo template::select('galleryThemeThumbAlign', $module::$galleryThemeAlign, [
							'label' => 'Alignement des légendes dans les vignettes :',
							'selected' => $this->getData(['module', $this->getUrl(0),$this->getUrl(1),'theme','legendAlign'])
						]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col4">
                    <?php echo template::text('galleryThemelegendTextcolor', [
                        'class' => 'colorPicker',
                        'help' => 'Le curseur horizontal règle le niveau de transparence.',							
                        'label' => 'Couleur du texte de légende',
                        'value' => $this->getData(['module', $this->getUrl(0),$this->getUrl(1),'theme','legendTextColor'])
                    ]); ?>	
                </div>
                <div class="col4">
                    <?php echo template::text('galleryThemelegendBgcolor', [
                        'class' => 'colorPicker',
                        'help' => 'Le curseur horizontal règle le niveau de transparence.',							
                        'label' => 'Couleur du fond de la légende',
                        'value' => $this->getData(['module', $this->getUrl(0),$this->getUrl(1),'theme','legendBgColor'])
                    ]); ?>	
                </div>
                <div class="col4">
                    <?php echo template::select('galleryThemelegendOpacity', $module::$galleryThemeOpacity, [
                        'label' => 'Opacité du fond des légendes :',
                        'selected' => $this->getData(['module', $this->getUrl(0),$this->getUrl(1),'theme','legendOpacity'])
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