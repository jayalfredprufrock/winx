<?= $this->template->head->default_view('layout/_head') 
  . $this->template->nav->default_view('layout/_nav')
?>

<div class="container">
	
	<?= $this->template->breadcrumb
	  . $this->template->messages
	  .	$this->template->content
	  . $this->template->footer->default_view('layout/_footer') 
	?>
	
</div> 

<?= $this->template->foot->default_view('layout/_foot') ?>

