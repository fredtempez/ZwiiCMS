<?php echo template::formOpen('configAdvancedForm'); ?>
<div class="row">
	<div class="col2">
		<?php echo template::button('configBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(false),
			'ico' => 'home',
			'value' => 'Accueil'
		]); ?>
	</div>
	<div class="col2">
		<?php echo template::button('configAdvancedHelp', [
			'class' => 'buttonHelp',
			'ico' => 'help',
			'value' => 'Aide'
		]); ?>
	</div>
	<div class="col2 offset6">
		<?php echo template::submit('configAdvancedSubmit'); ?>
	</div>
</div>
<!-- Aide à propos de la configuration du site, view advanced -->
<div class="helpDisplayContent">
	<?php echo file_get_contents( 'core/module/config/view/advanced/advanced.help.html') ;?>
</div>	
<div class="row">
	<div class="col12">
		<div class="row textAlignCenter">
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/index',
					'value' => 'Bases'
				]); ?>
			</div>
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/locale',
					'value' => 'Localisation'
				]); ?>
			</div>
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/social',
					'value' => 'Référencement'
				]); ?>
			</div>
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/safety',
					'value' => 'Sécurité'
				]); ?>
			</div>
			<div class="col2">
				<?php echo template::button('configAdvancedButton', [
					'href' => helper::baseUrl() . 'config/network',
					'value' => 'Réseau'
				]); ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block" id="network">
			<h4>Réseau
				<div class="openClose">
					<?php
					echo template::ico('plus-circled','right');
					echo template::ico('minus-circled','right');
					?>
				</div>
			</h4>
			<div class="blockContainer">
				<div class="row">
					<div class="col2">
						<?php echo template::select('configAdvancedProxyType', $module::$proxyType, [
							'label' => 'Type de proxy',
							'selected' => $this->getData(['config', 'proxyType'])
							]); ?>
						</div>
					<div  class="col8">
						<?php echo template::text('configAdvancedProxyUrl', [
							'label' => 'Adresse du proxy',
							'placeholder' => 'cache.proxy.fr',
							'value' => $this->getData(['config', 'proxyUrl'])
						]); ?>
					</div>
					<div  class="col2">
						<?php echo template::text('configAdvancedProxyPort', [
							'label' => 'Port du proxy',
							'placeholder' => '6060',
							'value' => $this->getData(['config', 'proxyPort'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col12">
		<div class="block" id="smtp">
			<h4>Messagerie SMTP
				<div class="openClose">
					<?php
					echo template::ico('plus-circled','right');
					echo template::ico('minus-circled','right');
					?>
				</div>
			</h4>
			<div class="blockContainer">
				<div class="row">
					<div class="col12">
						<?php echo template::checkbox('configAdvancedSmtpEnable', true, 'Activer SMTP', [
								'checked' => $this->getData(['config', 'smtp','enable']),
								'help' => 'Paramètres à utiliser lorsque votre hébergeur ne propose pas la fonctionnalité d\'envoi de mail.'
							]); ?>
					</div>
				</div>
				<div id="configAdvancedSmtpParam">
					<div class="row">
						<div class="col8">
							<?php echo template::text('configAdvancedSmtpHost', [
								'label' => 'Adresse SMTP',
								'placeholder' => 'smtp.fr',
								'value' => $this->getData(['config', 'smtp','host'])
							]); ?>
						</div>
						<div  class="col2">
							<?php echo template::text('configAdvancedSmtpPort', [
									'label' => 'Port SMTP',
									'placeholder' => '589',
									'value' => $this->getData(['config', 'smtp','port'])
							]); ?>
						</div>
						<div  class="col2">
							<?php echo template::select('configAdvancedSmtpAuth', $module::$SMTPauth, [
								'label' => 'Authentification',
								'selected' => $this->getData(['config', 'smtp','auth'])
							]); ?>
						</div>
					</div>
					<div id="configAdvancedSmtpAuthParam">
						<div class="row">
							<div  class="col5">
								<?php echo template::text('configAdvancedSmtpUsername', [
									'label' => 'Nom utilisateur',
									'value' => $this->getData(['config', 'smtp','username' ])
								]); ?>
							</div>
							<div  class="col5">
								<?php echo template::password('configAdvancedSmtpPassword', [
									'label' => 'Mot de passe',
									'autocomplete' => 'off',
									'value' => $this->getData(['config', 'smtp','username' ]) ? helper::decrypt ($this->getData(['config', 'smtp','username' ]),$this->getData(['config','smtp','password'])) : ''
								]); ?>
							</div>
							<div  class="col2">
								<?php echo template::select('configAdvancedSmtpSecure', $module::$SMTPEnc	, [
									'label' => 'Sécurité',
									'selected' => $this->getData(['config', 'smtp','secure'])
								]); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php echo template::formClose(); ?>
