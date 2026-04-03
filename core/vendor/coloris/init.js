/**
 * Initialisation de la palette de couleurs (Coloris 0.25.0)
 */
$(function() {
    Coloris({
        el: '.colorPicker',
        format: 'rgb',
        alpha: true,
        forceAlpha: true,
        themeMode: 'dark',
        onChange: function(color, input) {
            $(input).trigger('change');
        }
    });
});
