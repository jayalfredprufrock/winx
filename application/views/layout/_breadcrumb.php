<? if ($breadcrumb) : ?>
<ul class="breadcrumb">
	
	<?  $i=0;
	
		foreach ($breadcrumb as $url=>$title) : 
			
			if (++$i == count($breadcrumb)) : ?>
	
				<li class="active"><?= $title ?></li>
				
			<? else: ?>
			
				<li><a href="<?= $url ?>"><?= $title ?></a> <span class="divider">/</span></li>

			<? 	endif;
	
		endforeach; ?>
	
</ul>
<? endif; ?>
	
