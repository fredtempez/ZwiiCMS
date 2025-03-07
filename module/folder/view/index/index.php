<div id="dirindex">
    <article>
        <?php if ($this->getData(['module', $this->getUrl(0), 'expandcontrol']) === true): ?>
            <div class="titlecontainer">
                <div class="titletag">
                    <h2>
                        <?php echo $this->getData(['module', $this->getUrl(0), 'title']); ?>
                    </h2>
                </div>
                <div class="titleicons">
                    <span id="expand">
                        <?php echo template::ico('plus', ['margin' => 'all']) ?>
                    </span>
                    <span id="collapse">
                        <?php echo template::ico('minus', ['margin' => 'all']) ?>
                    </span>
                </div>
            </div>
        <?php else: ?>
            <h2>
                <?php echo $this->getData(['module', $this->getUrl(0), 'title']); ?>
            </h2>
        <?php endif; ?>
        <?php echo $module::$folders; ?>
    </article>
</div>