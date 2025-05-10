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
 * Visualiseur de fichiers TRK et WP : affichage des répertoires possibles dans le formulaire d'upload.
*/

// Liste des répertoires possibles
var listeRep = [
					"<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'dirTrkSelect']); ?>",
					"<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'dirDocTrkSelect']); ?>",
					"<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'dirWPSelect']); ?>",
					"<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'dirDocWPSelect']); ?>"
				];

var dirUpload = document.getElementById('visualizerUploadDir');

// Vider le sélecteur
dirUpload.innerHTML = "";

// Remplir le sélecteur
for (let i = 0; i < listeRep.length ; i++) {	
		const option = document.createElement('option');
		option.text = listeRep[i];
		option.value = listeRep[i];
		dirUpload.add(option);	
}