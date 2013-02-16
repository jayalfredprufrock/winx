<?= $this->template->head->default_view('layout/_head') 
  . $this->template->nav->default_view('layout/_nav') 
?>

<div class="container-fluid">
  
  <div class="row-fluid">
  	
    <div class="span3">	
		<?= $this->template->sidenav->view('layout/_sidenav') 
		  . $this->template->lpanel
		?>
	</div>
	
	<div class="span9">
		<?= $this->template->messages . $this->template->content ?>
	</div>
	
  </div>
  
  <?= $this->template->footer->default_view('layout/_footer') ?>

</div>

<?= $this->template->foot->default_view('layout/_foot') ?>
