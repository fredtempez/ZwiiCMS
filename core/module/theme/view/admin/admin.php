<?php
echo Template::formOpen('configAdminForm'); ?>
<div class="row">
	<div class="col1">
		<?php
echo Template::button('configAdminBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl() . 'theme', 'value' => Template::ico('left') ]); ?>
	</div>
	<!--
		<div class="col2 offset5">
			<?php
echo Template::button('configAdminTest', [ 'value' => 'Bouton standard' ]); ?>
		</div>
		-->
	<div class="col1 offset8">
		<?php
echo Template::button('configAdminReset', [ 'class' => 'buttonRed', 'href' => Helper::baseUrl() . 'theme/reset/admin', 'value' => Template::ico('cancel'), 'help' => 'Réinitialiser avec le thème par défaut' ]); ?>
	</div>
	<div class="col2">
		<?php
echo Template::submit('configAdminSubmit', [ 'value' => 'Valider', 'ico' => 'check' ]); ?>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php
echo Helper::translate('Paramètres'); ?>
			</h4>
			<div class="row">
			<div class="col3">
					<?php
echo Template::select('adminSiteWidth', theme::$siteWidths, [ 'label' => 'Largeur', 'selected' => $this->getData(['admin', 'width']) ]); ?>
				</div>
				<div class="col3">
					<?php
echo Template::text('adminBackgroundColor', [ 'class' => 'colorPicker', 'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.', 'label' => 'Arrière plan', 'value' => $this->getData(['admin', 'backgroundColor']) ]); ?>
				</div>
				<div class="col3">
					<?php
echo Template::text('adminColorTitle', [ 'class' => 'colorPicker', 'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.', 'label' => 'Titres', 'value' => $this->getData(['admin', 'colorTitle']) ]); ?>
				</div>
				<div class="col3">
					<?php
echo Template::text('adminColorText', [ 'class' => 'colorPicker', 'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.', 'label' => 'Texte', 'value' => $this->getData(['admin', 'colorText']) ]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col4">
					<?php
echo Template::text('adminBackGroundBlockColor', [ 'class' => 'colorPicker', 'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence. La couleur du texte est automatique.', 'label' => 'Arrière plan des champs', 'value' => $this->getData(['admin', 'backgroundBlockColor']) ]); ?>
				</div>
				<div class="col4">
					<?php
echo Template::text('adminBorderBlockColor', [ 'class' => 'colorPicker', 'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.', 'label' => 'Bordure des champs', 'value' => $this->getData(['admin', 'borderBlockColor']) ]); ?>
				</div>
				<div class="col4">
					<?php
echo Template::text('adminColorHelp', [ 'class' => 'colorPicker', 'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.', 'label' => 'Bouton Aide', 'value' => $this->getData(['admin', 'backgroundColorButtonHelp']) ]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col3">
					<?php
echo Template::text('adminColorGrey', [ 'class' => 'colorPicker', 'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.', 'label' => 'Bouton retour', 'value' => $this->getData(['admin', 'backgroundColorButtonGrey']) ]); ?>
				</div>
				<div class="col3">
					<?php
echo Template::text('adminColorButton', [ 'class' => 'colorPicker', 'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.', 'label' => 'Bouton standard', 'value' => $this->getData(['admin', 'backgroundColorButton']) ]); ?>
				</div>
				<div class="col3">
					<?php
echo Template::text('adminColorRed', [ 'class' => 'colorPicker', 'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.', 'label' => 'Bouton effacement', 'value' => $this->getData(['admin', 'backgroundColorButtonRed']) ]); ?>
				</div>
				<div class="col3">
					<?php
echo Template::text('adminColorGreen', [ 'class' => 'colorPicker', 'help' => 'Couleur visible en l\'absence d\'une image.<br />Le curseur horizontal règle le niveau de transparence.', 'label' => 'Bouton validation', 'value' => $this->getData(['admin', 'backgroundColorButtonGreen']) ]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block">
			<h4><?php
echo Helper::translate('Mise en forme du texte'); ?>
			</h4>
			<div class="row">
				<div class="col4">
					<?php
echo Template::select('adminFontText', theme::$fonts['name'], [ 'label' => 'Police du texte', 'selected' => $this->getData(['admin', 'fontText']), 'font' => theme::$fonts['family'] ]); ?>
				</div>
				<div class="col4">
					<?php
echo Template::select('adminFontTextSize', theme::$siteFontSizes, [ 'label' => 'Taille', 'selected' => $this->getData(['admin', 'fontSize']) ]); ?>
				</div>
				<div class="col4">
					<?php
echo Template::select('adminFontTitle', theme::$fonts['name'], [ 'label' => 'Police des titres', 'selected' => $this->getData(['admin', 'fontTitle']), 'font' => theme::$fonts['family'] ]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php
echo Template::formClose(); ?>
