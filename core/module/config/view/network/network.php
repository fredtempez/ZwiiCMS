<div id="network">
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>Réseau</h4>
				<div class="row">
					<div class="col2">
						<?php echo template::select('ProxyType', $module::$proxyType, [
							'label' => 'Type de proxy',
							'selected' => $this->getData(['config', 'proxyType'])
							]); ?>
						</div>
					<div  class="col8">
						<?php echo template::text('ProxyUrl', [
							'label' => 'Adresse du proxy',
							'placeholder' => 'cache.proxy.fr',
							'value' => $this->getData(['config', 'proxyUrl'])
						]); ?>
					</div>
					<div  class="col2">
						<?php echo template::text('ProxyPort', [
							'label' => 'Port du proxy',
							'placeholder' => '6060',
							'value' => $this->getData(['config', 'proxyPort'])
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col12">
			<div class="block">
				<h4>SMTP</h4>
				<div class="row">
					<div class="col12">
						<?php echo template::checkbox('SmtpEnable', true, 'Activer SMTP', [
								'checked' => $this->getData(['config', 'smtp','enable']),
								'help' => 'Paramètres à utiliser lorsque votre hébergeur ne propose pas la fonctionnalité d\'envoi de mail.'
							]); ?>
					</div>
				</div>
				<div id="SmtpParam">
					<div class="row">
						<div class="col8">
							<?php echo template::text('SmtpHost', [
								'label' => 'Adresse SMTP',
								'placeholder' => 'smtp.fr',
								'value' => $this->getData(['config', 'smtp','host'])
							]); ?>
						</div>
						<div  class="col2">
							<?php echo template::text('SmtpPort', [
									'label' => 'Port SMTP',
									'placeholder' => '589',
									'value' => $this->getData(['config', 'smtp','port'])
							]); ?>
						</div>
						<div  class="col2">
							<?php echo template::select('SmtpAuth', $module::$SMTPauth, [
								'label' => 'Authentification',
								'selected' => $this->getData(['config', 'smtp','auth'])
							]); ?>
						</div>
					</div>
					<div id="SmtpAuthParam">
						<div class="row">
							<div  class="col5">
								<?php echo template::text('SmtpUsername', [
									'label' => 'Nom utilisateur',
									'value' => $this->getData(['config', 'smtp','username' ])
								]); ?>
							</div>
							<div  class="col5">
								<?php echo template::password('SmtpPassword', [
									'label' => 'Mot de passe',
									'autocomplete' => 'off',
									'value' => $this->getData(['config', 'smtp','username' ]) ? helper::decrypt ($this->getData(['config', 'smtp','username' ]),$this->getData(['config','smtp','password'])) : ''
								]); ?>
							</div>
							<div  class="col2">
								<?php echo template::select('SmtpSecure', $module::$SMTPEnc	, [
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
