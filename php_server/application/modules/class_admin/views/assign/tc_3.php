<div class="container"><?php $page=3; ?>

<style type="text/css" media="screen">
    #editor { 
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
</style>

<?php $this->load->view('tc/bread_crumbs', array('info' => $info, 'page'=>$page)); ?>

<?php echo form_open('class_admin/tc/filecontent_single/'.$info[0]->class_id.'/'.$info[0]->session_id.'/'.$info[0]->prog_name, 'class="form-horizontal well"'); ?>

<fieldset>
	
<h2>Content for Sources and Input files</h2>
<p>In this section you can enter in the content for any source files and any additional input files that that only need to be entered in once.</p>

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

<?php if(isset($error)) : ?>
	<div class="alert">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
		<br />
		<fieldset id="error">
		<legend><h2>Sorry, something went wrong...</h2></legend>
		<pre class="error"><?php echo $error; ?></pre>
		</fieldset>
	</div>	
<?php  endif; ?>


<?php
//echo '<pre>'; print_r($fileinfo); echo '</pre><hr />'; print_r($info[0]); echo '<hr />';
// var_dump($fileinfo);

// pick the single part files and display
if( isset($fileinfo) )
{
	
	foreach ($fileinfo as $file)
	{
		if ( $file->multi_part == 0 || $file->stream_type == "source" && $file->stream_type != "output" )
		{
			echo  '<fieldset>';
			echo "<legend>File: ".$file->file_name ."</legend>";
			if ( $file->file_content != "" ) $r_val = $file->file_content; else $r_val = set_value($file->id);
			echo form_textarea($file->id , $r_val, 'id="'.$file->id.'"');
			echo  '</fieldset>';
		}
	}
} else {
	echo '<h4>If you need any additional files, please go back and simply specify the number that you need</h4>';
}
?>

<div class="form-actions">
<?php echo anchor("class_admin/tc/stage2/".$info[0]->class_id.'/'.$info[0]->session_id.'/'.$info[0]->prog_name, 'Back', 'class = "btn btn-large btn-primary"'); ?>
 <?php echo form_submit('submit', 'Next step...', 'class = "btn btn-large btn-primary"');?>
 
</div>

</fieldset>

<?php echo form_close(); ?>

</div>

<?php if( isset($fileinfo) ) : ?>
<script>
 	<?php foreach ($fileinfo as $file): ?>
	<?php if ( $file->admin_file == 0 && $file->stream_type != "output" && $file->multi_part == 0 ): ?>
  	var editor = CodeMirror.fromTextArea(document.getElementById("<?php echo $file->id; ?>"), {
    	lineNumbers: true,
    	matchBrackets: true,
    	<?php if($file->stream_type=="source") { echo 'mode: "text/x-c++src",'; } ?>
    	theme: "ambiance"
  	});
  	<?php endif; ?>
  	<?php endforeach; ?>
</script>
<?php endif; ?>

