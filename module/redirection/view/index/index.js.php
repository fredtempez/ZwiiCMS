

/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 *
 **/ 

 if (document.referrer.indexOf("edit") === -1)
 {
	var message = "<?php echo helper::translate('OUI : éditer la page | NON : tester la redirection'); ?>";
  	core.confirm(
  		message,
   		function() {
  			$(location).attr("href", "<?php echo helper::baseUrl(); ?>page/edit/<?php echo $this->getUrl(0); ?>");
  		},
  		function() {
  			$(location).attr("href", "<?php echo helper::baseUrl() . $this->getUrl(); ?>/force");
  		}
  	);
  }
  else
  {
  	$(location).attr("href", "<?php echo helper::baseUrl(); ?>");
  }
