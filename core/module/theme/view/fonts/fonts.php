<?php if($module::$fontsList): ?>
  <?php echo template::table([6, 6], $module::$fontsList, ['Family Name', 'Font Id']); ?>
<?php else: ?>
  <?php echo template::speech('Aucune news.'); ?>
<?php endif; ?>