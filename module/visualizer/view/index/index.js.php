/**
 * This file is a module for Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Jean-Luc Crespel
 * 
 * @license GNU General Public License, version 3
 * @link http://zwiicms.com/
 */


/**
 * Visualiseur de fichiers TRK et WP
*/

// Les paramètres : début
var titre = "<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'nameCata']); ?>";
var repTraces = "<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'dirTrkSelect']); ?>";
var repServices = "<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'dirWPSelect']); ?>";
var repDocTraces = "<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'dirDocTrkSelect']); ?>";
var repDocServices = "<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'dirDocWPSelect']); ?>";
var repSys = "../../../../";//Chemin d'accès à la racine pour les paramètres des scripts PHP, ne pas oublier le "/" à la fin. à la fin.
if ("<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'MapW']); ?>" != '') {
	var mapWidth = "<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'MapW']); ?>" + "px";
} else {
	var mapWidth = 'auto';
}
var mapHeight = "<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'MapH']); ?>" + "px";
var cleAPI = "<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'KeyIGN']); ?>";
var affTracesCharg = "<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'DisplayTrk']); ?>";
var dep = "";//Numéro du département

if ("<?php echo $this->getData(['module', $this->getUrl(0),'listFilesTrk']); ?>" != '') {
	var fichiersTraces = "<?php echo $this->getData(['module', $this->getUrl(0),'listFilesTrk']); ?>".split(';');
} else {
	var fichiersTraces = [];
}
if ("<?php echo $this->getData(['module', $this->getUrl(0),'listFilesWP']); ?>" != '') {
	var fichiersServices = "<?php echo $this->getData(['module', $this->getUrl(0),'listFilesWP']); ?>".split(';');
} else {
	var fichiersServices = [];
}
if ("<?php echo $this->getData(['module', $this->getUrl(0),'listFilesDocTrk']); ?>" != '') {
	var fichiersDocItin = "<?php echo $this->getData(['module', $this->getUrl(0),'listFilesDocTrk']); ?>".split(';');
} else {
	var fichiersDocItin = [];
}
if ("<?php echo $this->getData(['module', $this->getUrl(0),'listFilesDocWP']); ?>" != '') {
	var fichiersDocServices = "<?php echo $this->getData(['module', $this->getUrl(0),'listFilesDocWP']); ?>".split(';');
} else {
	var fichiersDocServices = [];
}
// Les paramètres : fin

diplayFiles();
listImg();

window.onload = function () {
	loadCarto();
	$(window).off('beforeunload');// inhibition du message intempestif de demande de sauvegarder un formulaire
								//  qui ne doit pas être sauvegardé.
};
