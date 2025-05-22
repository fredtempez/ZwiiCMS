<div id="resourceIndex">
    <article>
        <?php if ($this->getData(['module', $this->getUrl(0), 'expandcontrol']) === true): ?>
            <div class="resourcetitlecontainer">
                <div class="resourcetitletag">
                    <h2>
                        <?php echo $this->getData(['module', $this->getUrl(0), 'title']); ?>
                    </h2>
                </div>
                <div class="resourcetitleicons">
                    <span id="resourceExpand">
                        <?php echo template::ico('plus', ['margin' => 'all']) ?>
                    </span>
                    <span id="resourceCollapse">
                        <?php echo template::ico('minus', ['margin' => 'all']) ?>
                    </span>
                </div>
            </div>
        <?php else: ?>
            <h2>
                <?php echo $this->getData(['module', $this->getUrl(0), 'title']); ?>
            </h2>
        <?php endif; ?>
        <?php echo resource::$folders; ?>
    </article>
</div>