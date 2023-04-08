<!-- Chargement du script et de la clé d'API -->
<script async src="https://cdn.snipcart.com/themes/v3.0.25/default/snipcart.js"></script>
<div hidden id="snipcart" data-api-key="' . <?php echo $this->getData(['module', $this->getUrl(0), 'config','key']);?>'"></div>

<link rel="stylesheet" href="<?php echo helper::baseUrl(false).$module::DATADIRECTORY.'/'.$this->getUrl(0) ?>.css">
<?php  if(empty($module::$checkMessage)): ?>
	<div class="row">
		<div class="col2 offset10">
			<button class="snipcart-checkout" style="background-color:<?php echo $this->getData(['module', $this->getUrl(0), 'config','buttonBgColor']);?>; color:<?php echo $this->getData(['module', $this->getUrl(0), 'config','buttonColor']);?>">Panier</button>
		</div>
	</div>
<?php else: ?>
	<?php echo template::speech($module::$checkMessage); ?>
<?php endif; ?>
