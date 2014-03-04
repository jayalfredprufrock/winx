<?php if ($breadcrumb) : ?>
<ul class="breadcrumb">
	
	<?php $i=0;
	
		foreach ($breadcrumb as $url=>$title) : 
			
			if (++$i == count($breadcrumb)) : ?>
	
				<li class="active"><?= $title ?></li>
				
			<?php else: ?>
			
				<li><a href="<?= $url ?>"><?= $title ?></a> <span class="divider">/</span></li>

			<?php 	endif;
	
		endforeach; ?>
	
</ul>
<?php endif; ?>
	
