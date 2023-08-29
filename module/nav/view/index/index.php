<div class="row navButton">
    <div class="col1 textAlignCenter">
        <?php if ($module::$previousPage !== null) {
            echo template::button('navPreviousButton', [
                'href' => helper::baseUrl() . $module::$previousPage,
                'value' => template::ico($module::$leftButton)
            ]);
        } ?>
    </div>
    <div class="col1 offset10 textAlignCenter">
        <?php if ($module::$nextPage !== null) {
            echo template::button('nabNextButton', [
                'href' => helper::baseUrl() . $module::$nextPage,
                'value' => template::ico($module::$rightButton)
            ]);
        } ?>
    </div>
</div>