/**
 * This file is part of Zwii.
 *
 * For full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 *
 * @author Frédéric Tempez <frederic.tempez@outlook.com>
 * @copyright Copyright (C) 2018-2026, Frédéric Tempez
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @Copyright (C) 2026, Frédéric Tempez
 * @Licensed under the GNU General Public License v3.0 or later.
 * @link http://zwiicms.fr/
 */

$(document).ready(function() {

    // --- Graphiques Plotly ---
    var cfg = {responsive: true, displayModeBar: false};
    var layoutBase = {
        paper_bgcolor: 'rgba(0,0,0,0)',
        plot_bgcolor:  'rgba(0,0,0,0)',
        font: {size: 11}
    };

    // Mémoire PHP (demi camembert)
    <?php
$_jsInfos = \Zwii\Cms\dashboard::$infos; $_memUsed = $_jsInfos['php']['memUsedMb']; $_memLimit = $_jsInfos['php']['memLimitMb']; $_memPct = $_memLimit > 0 ? round($_memUsed / $_memLimit * 100, 1) : 0; ?>
    var memPct   = <?php
echo $_memPct; ?>;
    var memUsed  = <?php
echo $_memUsed; ?>;
    var memLimit = <?php
echo $_memLimit; ?>;
    var memColor = memPct > 85 ? '#ea4335' : (memPct > 60 ? '#fbbc04' : '#34a853');
    Plotly.newPlot('chartMemory', [{
        type: 'pie',
        values: [memPct, 100 - memPct, 100],
        labels: ['Utilisé', 'Libre', ''],
        marker: {
            colors: [memColor, '#e8eaf6', 'rgba(0,0,0,0)'],
            line: {width: 0}
        },
        hole: 0.6,
        rotation: 270,
        direction: 'clockwise',
        textinfo: 'none',
        hoverinfo: 'skip',
        showlegend: false,
        sort: false
    }], $.extend({}, layoutBase, {
        margin: {t: 10, b: 10, l: 10, r: 10},
        annotations: [
            {
                text: '<b>' + memPct + '%</b>',
                x: 0.5, y: 0.48,
                xanchor: 'center', yanchor: 'middle',
                showarrow: false,
                font: {size: 22, color: memColor}
            },
            {
                text: memUsed + ' / ' + memLimit + ' Mo',
                x: 0.5, y: 0.32,
                xanchor: 'center', yanchor: 'middle',
                showarrow: false,
                font: {size: 12, color: '#666'}
            }
        ]
    }), cfg);

    // Tailles des fichiers BD (barres horizontales)
    <?php
if (!empty($_jsInfos['db'])): $dbAll = $_jsInfos['db']; $dbNames = array_column($dbAll, 'name'); $dbSizes = array_column($dbAll, 'size'); $dbLabels = array_map(function($s) { return $s >= 1048576 ? round($s/1048576, 1).' Mo' : ($s >= 1024 ? round($s/1024, 1).' Ko' : $s.' o'); }, $dbSizes); $dbColors = array_map(function($f) { return $f['scope'] === 'global' ? '#1a73e8' : '#34a853'; }, $dbAll); $maxLen = max(array_map('strlen', $dbNames)); $marginL = $maxLen < 15 ? 100 : ($maxLen < 25 ? 140 : 170); ?>
    Plotly.newPlot('chartDbSizes', [{
        type: 'bar', orientation: 'h',
        x: <?php
echo json_encode(array_values($dbSizes)); ?>,
        y: <?php
echo json_encode(array_values($dbNames)); ?>,
        text: <?php
echo json_encode(array_values($dbLabels)); ?>,
        textposition: 'outside',
        hovertemplate: '%{y}: %{text}<extra></extra>',
        marker: {color: <?php
echo json_encode(array_values($dbColors)); ?>, opacity: 0.85}
    }], $.extend({}, layoutBase, {
        margin: {t: 4, b: 18, l: <?php
echo $marginL; ?>, r: 55},
        xaxis: {visible: false, fixedrange: true},
        yaxis: {fixedrange: true},
        bargap: 0.25
    }), cfg);
    <?php
endif; ?>

});
