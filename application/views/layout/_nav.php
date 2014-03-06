<div class="navbar-inner">
	
	<div class="container-fluid">
	
	  <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	 
	  <a class="brand" href="#">Winx</a>
	
	  <div class="nav-collapse collapse">

			<?php foreach($nav as $section) : 

			 	 if (!empty($section->pages)) :
			?>
					<li class="dropdown<?= !empty($section->active) ? ' active' : '' ?>">
					
						<a class="dropdown-toggle" href="#" data-toggle="dropdown">
							<?= $section->section_name ?>
							<b class="caret"></b>
						</a>
						
						<ul class="dropdown-menu">
							
							<?php foreach($section->pages as $page) : ?>
								
								<?php if (!empty($page->divider)) : ?>
								<li class="divider"></li>
								<?php endif; ?>
								
								<li<?= !empty($page->active) ? ' class="active"' : '' ?>><a href="/<?= $page->page_uri ?>"><?= $page->page_name ?></a></li>
									
							<?php endforeach; ?>
							
						</ul>
						
					</li>
					
				<?php else : ?>
					
					<li class="<?= !empty($section->active) ? ' active' : '' ?>"><a href="/<?= $section->section_uri ?>"><?= $section->section_name ?></a></li>
					
				<?php endif; ?>	
							
			<?php endforeach; ?>
		
	  </div>
	  
	</div>
	
</div>



