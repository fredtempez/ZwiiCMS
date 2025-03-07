<?php echo template::formOpen('registrationValidForm'); ?>
<h3>Email confirmé</h3>
<div class="<?php echo $this->getData(['module',$this->getUrl(0),'config','layout']); ?>">
    <?php echo template::password('registrationValidPassword', [
        'autocomplete' => 'off',
        'label' => 'Mot de passe'
    ]); ?>
    <?php echo template::password('registrationValidConfirmPassword', [
        'autocomplete' => 'off',
        'label' => 'Confirmation du mot de passe'
    ]); ?>
</div>
<div class='submitContainer'>
    <div class="row">
        <div class="col2 offset10">
            <?php echo template::submit('registrationValidSubmit', [
                'value' => 'Envoyer',
                'class' => 'green'
            ]); ?>
        </div>
    </div>
</div>
<?php echo template::formClose(); ?>