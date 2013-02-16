<div class="modal modal-<?= $type ?>" <?= $id ?: '' ?>>
	
	<? if ($type == 'form') echo form($form_action,'',$form_hidden); ?>
			    
	    <div class="modal-header">
		    <a href="<?= $close_btn_url ?>" class="btn close" data-dismiss="modal">&times;</a>
		    <h3><?= $title ?></h3>
	    </div>
	    
	<? if ($type == 'form') echo validation_errors(); ?>
	    
	    <div class="modal-body">
	    	<?= $body ?>
	    </div>
	    
	    <div class="modal-footer">
	    	
	    	<?= $buttons ?>
	    
	    </div>
	    
   </form>
    
</div>

<div class="modal-backdrop fade in"></div>