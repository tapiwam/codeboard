<div class="container"><?php $page=6; ?>

<?php $this->load->view('tc/bread_crumbs', array('info' => $info, 'page'=>$page)); ?>
	
<?php echo form_open('class_admin/tc/description/'.$info[0]->class_id.'/'.$info[0]->session_id.'/'.$info[0]->prog_name, 'class="form-horizontal well"'); ?>

<fieldset>
	
<h2>Assignment Description</h2>
<p>
	The program description is what is displayed to the student describing what they need to do for the current assignment. 
</p>

<p>
	Please fill out the program's discription and hints here. 
</p>
<hr />

<?php if(validation_errors() != false) : ?>
	<div class="alert alert-danger alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<br />
		<fieldset id="error">
		<legend><h2>Sorry, something went wrong...</h2></legend>
		<?php echo validation_errors('<p class="error">'); ?>
		</fieldset>
	</div>	
<?php  endif; ?>

<?php
	//echo '<div class="control-group">';
	//echo form_label('Program Description' , 'd');
	//echo '<div class="controls">';
	if ( trim($info[0]->description) != "" ) $r_val = $info[0]->description; else $r_val = set_value('d');  
	echo form_textarea('d' , $r_val , 'class="input-xlarge" rows="8" col="14" id="d"');
	//echo '</div></div>';
	
?>


<div class="form-actions">
<?php echo anchor("class_admin/tc/stage5/".$info[0]->class_id.'/'.$info[0]->session_id.'/'.$info[0]->prog_name, 'Back', 'class = "btn btn-large btn-primary"'); ?>
<?php echo "     "; ?>
<?php echo form_submit('submit', 'Finish', 'class = "btn btn-large btn-primary"');?>
 
</div>

</fieldset>

<?php echo form_close(); ?>

</div>

<script type="text/javascript" src="<?php echo base_url(); ?>js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
        selector: "textarea"
});
</script>

