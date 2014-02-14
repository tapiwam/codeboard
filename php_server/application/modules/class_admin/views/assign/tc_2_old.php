<div class="container"><?php $page=2; ?>

<ul class="breadcrumb">
	<?php for ( $i=1; $i<=$info[0]->stage;$i++ ) : ?>
		
		<?php if ( $i <= 6) : ?>
			
			<?php if ( $page != $i) : ?>
				<li><?php echo anchor("class_admin/tc/stage$i/".$info[0]->class_id.'/'.$info[0]->session_id.'/'.$info[0]->prog_name , "Step $i") ?> <span class="divider">/</span> </li>
			<?php else: ?>
				<li class="active">Step <?php echo $i?> <span class="divider">/</span> </li>
			<?php endif; ?>
			
		<?php else: if($i == 7) : ?>
				<?php if ( $page != $i) : ?>
				<li><?php echo anchor("class_admin/tc/review/".$info[0]->class_id.'/'.$info[0]->session_id.'/'.$info[0]->prog_name , "Review") ?> </li>
				<?php else: ?>
					<li class="active">Review</li>
				<?php endif; ?>
			<?php endif ?>
		<?php endif ?>	
			
	<?php endfor;?>
</ul>

<?php 
echo '<pre>'; print_r($fileinfo); echo '</pre><hr />'; print_r($info[0]); echo '<hr />';
$num_addition = $info[0]->num_addition;

$files = array();

// get all the files I own
if ( isset($fileinfo) ){
	foreach ($fileinfo as $key=>$file )
		$files[] = $file->file_name;
}

// remove duplicates
$files = array_unique($files);

// unset the cin file
if ( $key = array_search('cin', $files) ) unset($files[$key]); // remove cin

		//echo '<pre>'; print_r($files); echo '</pre><hr />'; // die();

// ==========================================

echo form_open('class_admin/tc/filedetails/'.$info[0]->class_id.'/'.$info[0]->session_id.'/'.$info[0]->prog_name, 'class="form-horizontal well"');

?>

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

// Basic Information on file structure 
echo  '<fieldset>';
echo '<h2>File Names and Details</h2>';

if ($num_addition == 1)
{
	echo '<p>I noticed you mentioned having '.$num_addition.' additional files. Please enter in the name and details here.';
} else {
	echo '<p>I noticed you mentioned having '.$num_addition.' additional files. Please enter in their names and details here.';
}

?>

<?php if(validation_errors() != false) : ?>
	<div class="alert">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
		<br />
		<fieldset id="error">
		<legend><h2>Sorry, something went wrong...</h2></legend>
		<?php echo validation_errors('<p class="error">'); ?>
		</fieldset>
	</div>	
<?php  endif; ?>

<?php
for ( $i=1; $i<=$num_addition; $i++ )
{
	
	// =================================================
	// See if the file name already exists
	// =================================================
	if ( isset($files[$i-1]) ) {
		$fname = $files[$i-1];
		foreach ($fileinfo as $key=>$file)
		{
			if($file->file_name == $fname)
			{
				$r = $file->file_name;
				echo $r . '<br />';
				break;
			}
		}
		if ( !isset($r) ) $r = set_value('add'.$i);
	} else {
		$r = set_value('add'.$i);
	}
	// =================================================
	
	echo '<div class="control-group">';
	echo "<legend>File $i</legend>";
	echo form_label('File Name', 'add'.$i );
	echo '<div class="controls">';
	echo form_input('add'.$i, $r ) ;
	echo '</div></div>'; 
	
	// =================================================
	// set the previous permissions
	// =================================================
	$a1 = FALSE; $a2 = FALSE;  // INITIALIZE VARIABLES
	$s1 = FALSE; $s2 = FALSE; $s3=FALSE;
	$m1 = FALSE; $m2 = FALSE;  // INITIALIZE VARIABLES
	
	if ( isset($files[$i-1]) ) {
		$fname = $files[$i-1];
		foreach ($fileinfo as $key=>$file)
		{
			if($file->file_name == $fname)
			{
				$r = $file->file_name;
				
				// ===================================
				
				if ( $file->admin_file == 1) 
				{  $a2 = TRUE; } else { $a1 = TRUE; } // REPLACE PREVIOUS 
				
				// ===================================
				
				if ( $file->stream_type == "input") {
					  $s2 = TRUE; 
				} else if ( $file->stream_type == "output") {
					 $s3 = TRUE; 
				} else if ( $file->stream_type == "source") {
					 $s1 = TRUE; 
				}
				
				// ===================================
				
				if ( $file->multi_part == 1) 
				{  $m2 = TRUE; } else { $m1 = TRUE; } // REPLACE PREVIOUS 
				
				// ===================================
				break;
			}
		}
		if ( !isset($r) ) $r = set_value('add'.$i);
	} else {
		$r = set_value('add'.$i);
	}
	// =================================================
	
	?>
	
	<div class="control-group">
		<?php echo form_label('Is this an Input/Output/Source file?', 'stream_type');?>
		<div class="controls">
			<table>
				<tr>
					<td><?php echo form_radio('stream_type'.$i, 'source', $s1) ; ?></td>
					<td>Source file</td>
				</tr>
				<tr>
					
					<td><?php echo form_radio('stream_type'.$i, 'input', $s2); ?></td>
					<td>Input file</td>
				</tr>
				<tr>
					<td><?php echo form_radio('stream_type'.$i, 'output', $s3) ; ?></td>
					<td>Output file</td>
				</tr>
			</table>
		</div>
	</div>
	
	<div class="control-group">
		<?php echo form_label('Will this file be submitted by the student or provided by the Administrator?' , 'admin'.$i ); ?>
		<div class="controls">
			<table>
				<tr>
					<td><?php echo form_radio('admin'.$i, '0', $a1); ?></td>
					<td>Student Submits file</td>
				</tr>
				<tr>
					<td><?php echo form_radio('admin'.$i, '1', $a2) ; ?></td>
					<td>Administrator provided file</td>
				</tr>
			</table>
		</div>
	</div>
	
	<div class="control-group">
		<?php echo form_label('Is this a multi-instance file? (i.e. A different file is used/created for every test case)', 'multi'.$i); ?>
		<div class="controls">
			<table>
				<tr>
					<td><?php echo form_radio('multi'.$i, '0', $m1); ?></td>
					<td>Single-instance file</td>
				</tr>
				<tr>
					<td><?php echo form_radio('multi'.$i, '1', $m2) ; ?></td>
					<td>Multi-instance file</td>
				</tr>
			</table>
		</div>
	</div>
	<?php
}
?>

<div class="form-actions">
<?php echo anchor("tc/stage1/".$info[0]->class_id.'/'.$info[0]->session_id.'/'.$info[0]->prog_name, 'Back', 'class = "btn btn-large btn-primary"'); ?>
 <?php echo form_submit('submit', 'Next step...', 'class = "btn btn-large btn-primary"');?>
 
</div>

</fieldset>

<?php echo form_close(); ?>



<p>Note: For single part files these will be included along with the main file since only one version of the file is required.</p>
<p>For Muli-part, since these are all conected to individual questions, these question will be attached to each test case</p>

</div>