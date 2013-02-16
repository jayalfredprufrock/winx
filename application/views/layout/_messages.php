<? if ($messages) : 
		
		foreach($messages as $msg) : ?>

			<div class="alert alert-block alert-<?= $msg->type?>">
				
				<button typoe="button" class="close" data-dismiss="alert">&times;</button>
				
				<? if ($msg->title) : ?><h4><?= $msg->title ?></h4><? endif; ?>
				
				<?= $msg->text ?>
				
			</div>

<? 		endforeach;
			
endif; ?>
