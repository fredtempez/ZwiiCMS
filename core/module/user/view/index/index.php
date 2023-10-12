<div class="row">
	<div class="col1">
		<?php echo template::button('userAddBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl(false),
			'value' => template::ico('home')
		]); ?>
	</div>
	<div class="col1">
		<?php /**echo template::button('userHelp', [
			'href' => 'https://doc.zwiicms.fr/gestion-des-utilisateurs',
			'target' => '_blank',
			'value' => template::ico('help'),
			'class' => 'buttonHelp',
			'help' => 'Consulter l\'aide en ligne'
		]);*/ ?>
	</div>
	<div class="col1 offset7">
		<?php echo template::button('userImport', [
			'href' => helper::baseUrl() . 'user/import',
			'value' => template::ico('upload'),
			'help' => 'Importer des utilisateurs en masse'
		]); ?>
	</div>
	<div class="col1">
		<?php echo template::button('userGroup', [
			'href' => helper::baseUrl() . 'user/profil',
			'value' => template::ico('lock'),
			'help' => 'Profils'
		]); ?>
	</div>
	<div class="col1">
		<?php echo template::button('userAdd', [
			'href' => helper::baseUrl() . 'user/add',
			'value' => template::ico('plus'),
			'class' => 'buttonGreen',
			'help' => 'Ajouter un utilisateur'
		]); ?>
	</div>
</div>
<?php echo template::formOpen('userFilterUserForm'); ?>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php echo helper::translate('Filtres'); ?>
            </h4>
            <div class="row">
                <div class="col3">
                    <?php echo template::select('userFilterGroup', $module::$courseGroups, [
                        'label' => 'Groupes / Profils',
                        'selected' => isset($_POST['userFilterGroup']) ? $_POST['userFilterGroup'] : 'all',
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('userFilterFirstName', $module::$alphabet, [
                        'label' => 'Prénom commence par',
                        'selected' => isset($_POST['userFilterFirstName']) ? $_POST['userFilterFirstName'] : 'all',
                    ]); ?>
                </div>
                <div class="col3">
                    <?php echo template::select('userFilterLastName', $module::$alphabet, [
                        'label' => 'Nom commence par',
                        'selected' => isset($_POST['userFilterLastName']) ? $_POST['userFilterLastName'] : 'all',
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>
<?php echo template::table([2, 2 , 3, 3, 1, 1], $module::$users, ['Identifiant', 'Nom', 'Groupe', 'Profil', '', '']); ?>