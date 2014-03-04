<div class="modal modal-<?= $type ?>" <?= $id ? 'id="'.$id.'"' : '' ?>>
	
	<?php if ($form_action !== FALSE) echo form($form_action,'',$form_hidden); ?>
			    
	    <div class="modal-header">
		    <?= $close_button ?>
		    <h3><?= $title ?></h3>
	    </div>
	    
	<?php if ($form_action !== FALSE) echo validation_errors(); ?>
	    
	    <div class="modal-body">
	    	<?= $body ?>
	    </div>
	    
	    <div class="modal-footer">
	    	
	    	<?= $buttons ?>
	    
	    </div>
	    
   </form>
    
</div>

<div class="modal-backdrop"></div>