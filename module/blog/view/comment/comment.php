<div class="row">
	<div class="col2">
		<?php echo template::button('blogCommentBack', [
			'class' => 'buttonGrey',
			'href' => helper::baseUrl() . $this->getUrl(0) . '/config',
			'ico' => 'left',
			'value' => 'Retour'
		]); ?>
	</div>

<?php if($module::$comments): ?>
	<div class="col2 offset8">
			<?php echo $module::$commentsDelete; ?>
	</div>

</div>
	<?php echo template::table([3, 5, 2, 1, 1], $module::$comments, ['Date', 'Contenu', 'Auteur', '', '']); ?>
	<?php echo $module::$pages.'<br/>'; ?>
<?php else: ?>
</div>
	<?php echo template::speech('Aucun commentaire'); ?>
<?php endif; ?>
