<ul>
	<?php foreach($this->getHierarchy(null,true,null) as $parentId => $childIds): ?>
		<li>
			<?php 			
			if ($this->getData(['page', $parentId, 'disable']) === false  && $this->getUser('group') >= $this->getData(['page', $parentId, 'group']))
			{ ?>	
				<a href="<?php echo helper::baseUrl() . $parentId; ?>"><?php echo $this->getData(['page', $parentId, 'title']); ?></a>
				<?php 
			} else { 
				// page désactivée
				echo $this->getData(['page', $parentId, 'title']); 
			} ?>
			<ul>
				<?php foreach($childIds as $childId): ?>
					<li>
					<!-- Sous-page -->
					<?php if ($this->getData(['page', $childId, 'disable']) === false && $this->getUser('group') >= $this->getData(['page', $parentId, 'group'])) 
					{ ?>	
						<a href="<?php echo helper::baseUrl() . $childId; ?>"><?php echo $this->getData(['page', $childId, 'title']); ?></a>
					<?php } else { ?>
							<!-- page désactivée -->
							<?php echo $this->getData(['page', $childId, 'title']); }?>	
							
						<!-- articles d'une sous-page blog-->
						<ul>
						<?php if ($this->getData(['page', $childId, 'moduleId']) === 'blog') { ?>
							
							<?php
								foreach($this->getData(['module',$childId]) as $articleId => $article): ?>
								<?php if($this->getData(['module',$childId,$articleId,'state']) === true) {?>								
									<li>
										<a href="<?php echo helper::baseUrl() . $childId . '/' . $articleId;?>"><?php echo $article['title']; ?></a>
									</li>
								<?php } ?>								
								<?php endforeach;
							} ?>
						</ul>					
					</li>
				<?php endforeach; ?>
				<!-- ou articles d'un blog-->

				<?php if ($this->getData(['page', $parentId, 'moduleId']) === 'blog'  &&
				   			!empty($this->getData(['module',$parentId])) ) { ?>
					<?php foreach($this->getData(['module',$parentId]) as $articleId => $article): ?>
						<?php if($this->getData(['module',$parentId,$articleId,'state']) === true ): ?>
							<li>
								<a href="<?php echo helper::baseUrl() .	$parentId. '/' . $articleId;?>"><?php echo $article['title']; ?></a>
							</li>
						<?php endif; ?>
					<?php endforeach;
				} ?>
			</ul>
		</li>
	<?php endforeach; ?>
</ul>