<?php
$infos     = $module::$infos;
$memPct    = $infos['php']['memLimitMb'] > 0
    ? round($infos['php']['memUsedMb'] / $infos['php']['memLimitMb'] * 100, 1)
    : 0;
$diskPct   = $infos['disk']['totalGb'] > 0
    ? round($infos['disk']['usedGb'] / $infos['disk']['totalGb'] * 100, 1)
    : 0;
$isUpdate  = $infos['cms']['updateAvailable'] === 'Oui';
$hasErrors = $infos['logs']['errors'] > 0;
?>

<!-- En-tête -->
<div class="row">
	<div class="col1">
		<?php echo Template::button('userAddBack', [
			'class' => 'buttonGrey',
			'href' => Helper::baseUrl() . 'config',
			'value' => Template::ico('left')
		]); ?>
	</div>
    <div class="col11 db-subtitle">
        <span><?php echo Helper::translate('Site'); ?> : <strong><?php echo htmlspecialchars($infos['cms']['siteTitle']); ?></strong></span>
        &nbsp;|&nbsp;
        <span>ZwiiCMS <?php echo $infos['cms']['version']; ?></span>
        &nbsp;|&nbsp;
        <span><?php echo $infos['os']; ?></span>
        &nbsp;&nbsp;
        <span class="db-badge <?php echo $isUpdate ? 'warn' : 'ok'; ?>">
            <?php echo $isUpdate ? '&#8593; ' . Helper::translate('Mise à jour dispo') : '&#10003; ' . Helper::translate('À jour'); ?>
        </span>
        <span class="db-badge <?php echo $hasErrors ? 'red' : 'ok'; ?>">
            <?php echo $infos['logs']['errors']; ?> <?php echo Helper::translate('erreur(s) 30j'); ?>
        </span>
        <span class="db-badge">
            <?php echo Helper::translate('Backup'); ?> : <?php echo $infos['cms']['lastBackup']; ?>
        </span>
    </div>
</div>

<!-- Graphes -->
<div class="row">
    <div class="col6">
        <div class="block">
            <h4><?php echo Helper::translate('Mémoire PHP'); ?></h4>
            <div id="chartMemory" style="height:220px;"></div>
        </div>
    </div>
    <div class="col6">
        <div class="block">
            <h4><?php echo Helper::translate('Environnement'); ?></h4>
            <table class="db-table">
                <tr><th><?php echo Helper::translate('Paramètre'); ?></th><th><?php echo Helper::translate('Valeur'); ?></th></tr>
                <tr><td>Serveur web</td><td><?php echo htmlspecialchars($infos['server']); ?></td></tr>
                <tr><td>PHP</td><td><?php echo $infos['php']['version']; ?></td></tr>
                <tr><td>Mémoire PHP</td><td><?php echo $infos['php']['memUsedMb']; ?> Mo / pic <?php echo $infos['php']['memPeakMb']; ?> Mo / limite <?php echo $infos['php']['memLimit']; ?></td></tr>
                <tr><td>Upload max</td><td><?php echo $infos['php']['uploadMax']; ?></td></tr>
                <tr><td>Exec. max</td><td><?php echo $infos['php']['execTime']; ?></td></tr>
                <tr><td>Charge (1/5/15 min)</td><td><?php echo $infos['system']['load1']; ?> / <?php echo $infos['system']['load5']; ?> / <?php echo $infos['system']['load15']; ?></td></tr>
            </table>
        </div>
    </div>
</div>

<!-- Tableau des sites localisés -->
<div class="row">
    <div class="col12">
        <div class="block">
            <h4><?php echo Helper::translate('Sites localisés'); ?></h4>
            <?php if (empty($infos['sites'])): ?>
                <p style="color:#aaa;text-align:center;padding:1.5rem 0;"><?php echo Helper::translate('Aucun site localisé'); ?></p>
            <?php else: ?>
            <table class="db-table">
                <tr>
                    <th><?php echo Helper::translate('Site'); ?></th>
                    <th style="text-align:center"><?php echo Helper::translate('Pages'); ?></th>
                    <th style="text-align:center"><?php echo Helper::translate('Modules'); ?></th>
                </tr>
                <?php foreach ($infos['sites'] as $site): ?>
                <tr>
                    <td><?php echo htmlspecialchars($site['title']); ?> <small style="color:#aaa">(<?php echo htmlspecialchars($site['id']); ?>)</small></td>
                    <td style="text-align:center"><?php echo $site['pages']; ?></td>
                    <td style="text-align:center"><?php echo $site['modules']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Erreurs journal -->
<?php if (!empty($infos['logs']['errorLines'])): ?>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4 style="color:#c00"><?php echo Helper::translate('Erreurs journal (30 derniers jours)'); ?> <span style="font-size:.8rem;font-weight:normal;color:#888">(<?php echo count($infos['logs']['errorLines']); ?>)</span></h4>
            <table class="db-table">
                <tr>
                    <th><?php echo Helper::translate('Date'); ?></th>
                    <th><?php echo Helper::translate('Heure'); ?></th>
                    <th><?php echo Helper::translate('Utilisateur'); ?></th>
                    <th><?php echo Helper::translate('URL'); ?></th>
                    <th><?php echo Helper::translate('Message'); ?></th>
                </tr>
                <?php foreach ($infos['logs']['errorLines'] as $err): ?>
                <tr style="background:#fff5f5">
                    <td><?php echo htmlspecialchars($err['date']); ?></td>
                    <td><?php echo htmlspecialchars($err['time']); ?></td>
                    <td><?php echo htmlspecialchars($err['user']); ?></td>
                    <td><?php echo htmlspecialchars($err['url']); ?></td>
                    <td style="color:#c00"><?php echo htmlspecialchars($err['msg']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Tableaux -->
<div class="row">
    <div class="col4">
        <div class="block">
            <h4><?php echo Helper::translate('Stockage'); ?></h4>
            <?php
            $freePct   = $infos['disk']['totalGb'] > 0 ? round($infos['disk']['freeGb'] / $infos['disk']['totalGb'] * 100) : 0;
            $freeColor = $freePct < 10 ? '#ea4335' : ($freePct < 25 ? '#fbbc04' : '#34a853');
            ?>
            <table class="db-table">
                <tr><td colspan="2" style="padding-top:.4rem"><strong style="font-size:.8rem;color:#555"><?php echo Helper::translate('Applicatif'); ?></strong></td></tr>
                <tr><td>Core</td><td><strong style="color:#34a853"><?php echo $infos['disk']['coreSize']; ?></strong></td></tr>
                <tr><td>Modules</td><td><strong style="color:#f9ab00"><?php echo $infos['disk']['moduleSize']; ?></strong></td></tr>
                <tr><td colspan="2" style="padding-top:.4rem"><strong style="font-size:.8rem;color:#555"><?php echo Helper::translate('Données du site'); ?></strong></td></tr>
                <tr><td>site/backup</td><td><?php echo $infos['disk']['backSize']; ?></td></tr>
                <tr><td>site/file/source</td><td><?php echo $infos['disk']['sourceSize']; ?></td></tr>
                <tr><td>site/file/thumb</td><td><?php echo $infos['disk']['thumbSize']; ?></td></tr>
                <tr><td>site/tmp</td><td><?php echo $infos['disk']['tmpSize']; ?></td></tr>
                <tr><td colspan="2" style="padding-top:.4rem"><strong style="font-size:.8rem;color:#555"><?php echo Helper::translate('Bases de données'); ?></strong></td></tr>
                <tr><td>site/data</td><td><?php echo $infos['disk']['dataSize']; ?></td></tr>
                <tr><td colspan="2" style="padding-top:.4rem"><strong style="font-size:.8rem;color:#555"><?php echo Helper::translate('Disque'); ?></strong></td></tr>
                <tr><td>Espace libre</td><td><strong style="color:<?php echo $freeColor; ?>"><?php echo $infos['disk']['freeGb']; ?> Go (<?php echo $freePct; ?>%)</strong></td></tr>
                <tr><td>Total</td><td><?php echo $infos['disk']['totalGb']; ?> Go</td></tr>
            </table>
        </div>
    </div>
    <div class="col8">
        <div class="block">
            <h4><?php echo Helper::translate('Santé des bases de données'); ?></h4>
            <div id="chartDbSizes" style="height:160px;"></div>
            <?php
            $dbGlobal = array_filter($infos['db'], fn($f) => $f['scope'] === 'global');
            $dbSites  = array_filter($infos['db'], fn($f) => $f['scope'] !== 'global');
            ?>
            <div class="row">
                <div class="col6">
                    <strong style="font-size:.8rem;color:#555;display:block;margin-bottom:.3rem"><?php echo Helper::translate('Bases générales'); ?></strong>
                    <table class="db-table">
                        <tr>
                            <th><?php echo Helper::translate('Fichier'); ?></th>
                            <th><?php echo Helper::translate('Poids'); ?></th>
                            <th title="Minification" style="text-align:center">M</th>
                            <th title="Compression" style="text-align:center">C</th>
                            <th title="Chiffrement" style="text-align:center">E</th>
                        </tr>
                        <?php foreach ($dbGlobal as $f): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($f['name']); ?></td>
                            <td><?php $s=$f['size']; echo $s>=1048576?round($s/1048576,1).' Mo':($s>=1024?round($s/1024,1).' Ko':$s.' o'); ?></td>
                            <td style="text-align:center"><span class="db-tag <?php echo $f['minify']?'ok':''; ?>" style="<?php echo $f['minify']?'':'opacity:.25'; ?>"><?php echo $f['minify']?'✓':'✗'; ?></span></td>
                            <td style="text-align:center"><span class="db-tag <?php echo $f['compress']?'ok':''; ?>" style="<?php echo $f['compress']?'':'opacity:.25'; ?>"><?php echo $f['compress']?'✓':'✗'; ?></span></td>
                            <td style="text-align:center"><span class="db-tag <?php echo $f['encrypt']?'ok':''; ?>" style="<?php echo $f['encrypt']?'':'opacity:.25'; ?>"><?php echo $f['encrypt']?'✓':'✗'; ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <div class="col6">
                    <strong style="font-size:.8rem;color:#555;display:block;margin-bottom:.3rem"><?php echo Helper::translate('Bases des sites localisés'); ?></strong>
                    <?php if (empty($dbSites)): ?>
                        <p style="color:#aaa;font-size:.85rem"><?php echo Helper::translate('Aucun site localisé'); ?></p>
                    <?php else: ?>
                    <table class="db-table">
                        <tr>
                            <th><?php echo Helper::translate('Fichier'); ?></th>
                            <th><?php echo Helper::translate('Poids'); ?></th>
                            <th title="Minification" style="text-align:center">M</th>
                            <th title="Compression" style="text-align:center">C</th>
                            <th title="Chiffrement" style="text-align:center">E</th>
                        </tr>
                        <?php foreach ($dbSites as $f): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($f['name']); ?></td>
                            <td><?php $s=$f['size']; echo $s>=1048576?round($s/1048576,1).' Mo':($s>=1024?round($s/1024,1).' Ko':$s.' o'); ?></td>
                            <td style="text-align:center"><span class="db-tag <?php echo $f['minify']?'ok':''; ?>" style="<?php echo $f['minify']?'':'opacity:.25'; ?>"><?php echo $f['minify']?'✓':'✗'; ?></span></td>
                            <td style="text-align:center"><span class="db-tag <?php echo $f['compress']?'ok':''; ?>" style="<?php echo $f['compress']?'':'opacity:.25'; ?>"><?php echo $f['compress']?'✓':'✗'; ?></span></td>
                            <td style="text-align:center"><span class="db-tag <?php echo $f['encrypt']?'ok':''; ?>" style="<?php echo $f['encrypt']?'':'opacity:.25'; ?>"><?php echo $f['encrypt']?'✓':'✗'; ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
