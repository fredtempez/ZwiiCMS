<?php
echo Template::formOpen('translateUIForm'); ?>
<div class="row">
    <div class="col1">
        <?php
echo Template::button('translateUIFormBack', [ 'class' => 'buttonGrey', 'href' => Helper::baseUrl() . 'language', 'value' => Template::ico('left') ]); ?>
    </div>
    <div class="col2 offset9">
        <?php
echo Template::submit('translateUIFormSubmit'); ?>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <h4>
                <?php
echo Helper::translate('Paramètres'); ?>
            </h4>
            <div class="row">
                <div class="col6">
                    <?php
echo Template::text('translateEditVersion', [ 'label' => 'Version n°', 'value' => $this->getData(['language', $this->getUrl(2), 'version']) ]); ?>
                </div>
                <div class="col6">
						<?php
echo Template::date('translateEditDate', [ 'label' => 'Date de publication', 'type' => 'datetime-local', 'value' => $this->getData(['language', $this->getUrl(2), 'date']) ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col12">
        <div class="block">
            <div class="row">
                <?php
foreach (language::$dialogues as $key => $value) : ?>
                    <div class="col6">
                        <?php
echo sprintf('%g -', $key); ?>
                        <?php
echo $value['source']; ?>
                    </div>
                    <div class="col6">
                        <?php
echo Template::text('translateString' . $key, [ 'label' => '', 'value' => $value['target'] ]); ?>
                    </div>
                <?php
endforeach; ?>
            </div>
            <?php
echo language::$pages; ?>
        </div>
    </div>
</div>
<?php
echo Template::formClose(); ?>
