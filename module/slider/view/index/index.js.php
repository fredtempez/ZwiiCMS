/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Rémi Jean <remi.jean@outlook.com>
 * @copyright Copyright (C) 2008-2018, Rémi Jean
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2024, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link http://zwiicms.fr/
 */

/**
 *   
  auto: true,             // Boolean: Animate automatically, true or false
  speed: 500,            // Integer: Speed of the transition, in milliseconds
  timeout: 4000,          // Integer: Time between slide transitions, in milliseconds
  pager: false,           // Boolean: Show pager, true or false
  nav: false,             // Boolean: Show navigation, true or false
  random: false,          // Boolean: Randomize the order of the slides, true or false
  pause: false,           // Boolean: Pause on hover, true or false
  pauseControls: true,    // Boolean: Pause when hovering controls, true or false
  prevText: "Previous",   // String: Text for the "previous" button
  nextText: "Next",       // String: Text for the "next" button
  width: "",           // Integer: Max-width of the slideshow, in pixels
  navContainer: "",       // Selector: Where controls should be appended to, default is after the 'ul'
  manualControls: "",     // Selector: Declare custom pager navigation
  namespace: "rslides",   // String: Change the default namespace used
  before: function(){},   // Function: Before callback
  after: function(){}     // Function: After callback
 */


$(document).ready(function () {

    var maxwidth = "<?php echo $this->getData(['module', $this->getUrl(0),'theme', 'maxWidth']); ?>";
    var screenwidth = "<?php echo intval(trim($this->getData(['theme', 'site', 'width']), 'px')); ?>";
    var sort = "<?php echo $this->getData(['module', $this->getUrl(0),'theme', 'sort']); ?>";
    // Réduction de la taille maximale selon la largeur de la section

    // Limiter à la largeur de l'écran
    if (
        screenwidth !== '100%' &&
        maxwidth > screenwidth
    ) {
        mawwidth = screenwidth - 40;
    }
    // Largeur 100%
    maxwidth = $("#site").width();


    console.log(maxwidth);
    $("#wrapper").css('width', "100%");
    $(function () {
        $("#sliders").responsiveSlides({
            pager: "<?php echo (bool)$this->getData(['module', $this->getUrl(0),  'theme', 'pager']); ?>",
            auto: "<?php echo (bool)$this->getData(['module', $this->getUrl(0), 'theme', 'auto']); ?>",
            maxwidth: maxwidth,
            speed: "<?php echo $this->getData(['module', $this->getUrl(0),  'theme', 'speed']); ?>",
            timeout: "<?php echo $this->getData(['module', $this->getUrl(0), 'theme', 'timeout']); ?>",
            namespace: "<?php echo $this->getData(['module', $this->getUrl(0), 'theme', 'namespace']); ?>",
            nav: true,
            random: sort == "random" ? true : false,
        });
    });

});