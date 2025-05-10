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
 * Visualiseur de fichiers TRK et WP : lister les répertoires, et affichage dans le formulaire de configuration.
*/

var listeRepOld = [];

function listDir() {
    var url = "<?php echo helper::baseUrl() . $this->getUrl(0); ?>/dirs";
    console.log(url);
    fetch(url)
        .then(res => res.json())
        .then(listeRep => {
            var tracesRepListe = document.getElementById('visualizerDirTrk');
            var wpRepListe = document.getElementById('visualizerDirWP');
            var tracesRepDocListe = document.getElementById('visualizerDirDocTrk');
            var wpRepDocListe = document.getElementById('visualizerDirDocWP');

            if (JSON.stringify(listeRepOld) != JSON.stringify(listeRep)) {
                // Configuration des sélecteurs et leurs valeurs PHP
                const selectors = [
                    { element: tracesRepListe, value: "<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'dirTrkSelect']); ?>" },
                    { element: wpRepListe, value: "<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'dirWPSelect']); ?>" },
                    { element: tracesRepDocListe, value: "<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'dirDocTrkSelect']); ?>" },
                    { element: wpRepDocListe, value: "<?php echo $this->getData(['module', $this->getUrl(0), 'config', 'dirDocWPSelect']); ?>" }
                ];

                // Vider tous les sélecteurs
                selectors.forEach(selector => selector.element.innerHTML = "");

                // Remplir les sélecteurs et définir la sélection
                listeRep.forEach((rep, index) => {
                    selectors.forEach(selector => {
                        const option = document.createElement('option');
                        option.text = rep.name;
                        option.value = rep.path;
                        selector.element.add(option);

                        if(selector.value === rep.path) {
                            selector.element.selectedIndex = index;
                        }
                    });
                });

                listeRepOld = listeRep;
            }
        })
        .catch(err => console.error(err));
}

listDir();
setInterval(listDir, 3000);