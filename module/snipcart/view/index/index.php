<?php
	// Chargement de la clé d'API
	echo '<script async src="https://cdn.snipcart.com/themes/v3.0.25/default/snipcart.js"></script>';
	echo '<div hidden id="snipcart" data-api-key="' . $this->getData(['module', $this->getUrl(0), 'config','key']) . '"></div>';
?>

<link rel="stylesheet" href="<?php echo helper::baseUrl(false).$module::DATADIRECTORY.'/'.$this->getUrl(0) ?>.css">
<?php 
$valid =true;
if($this->getData(['module', $this->getUrl(0), 'config','valid']) !== true){
	echo'Snipcart n\'est pas activé !'.'<br/>';
	$valid = false;
}
if($this->getData(['module', $this->getUrl(0), 'config','key']) === ''){
	echo'La clef snipcart n\'est pas renseignée !';
	$valid = false;
}
else if( strlen($this->getData(['module', $this->getUrl(0), 'config','key'])) < 20){
	echo'La clef snipcart est incorrecte !';
	$valid = false;
}
if($valid){
	?>
	<div class="row">
		<div class="col2 offset10">
			<button class="snipcart-checkout" style="background-color:<?php echo $this->getData(['module', $this->getUrl(0), 'config','buttonBgColor']);?>; color:<?php echo $this->getData(['module', $this->getUrl(0), 'config','buttonColor']);?>">Panier</button>
		</div>
	</div>
	<?php
}
?>
