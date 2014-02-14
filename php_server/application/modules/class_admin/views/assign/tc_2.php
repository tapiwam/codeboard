<div class="container"><?php $page=2; ?>
	
<?php $this->load->view('tc/bread_crumbs', array('info' => $info, 'page'=>$page)); ?>

<?php 
$files = array();
if ( isset($fileinfo) ){
	foreach ($fileinfo as $key=>$file )
		$files[] = $file->file_name;
}
$files = array_unique($files);

if ( $key = array_search('cin', $files) ) unset($files[$key]); // remove cin
	//echo '<pre>'; print_r($fileinfo); echo '</pre><hr />'; print_r($info[0]); echo '<hr />';
	//echo '<pre>'; print_r($files); echo '</pre><hr />'; // die()
echo form_open('class_admin/tc/filedetails/'.$info[0]->class_id.'/'.$info[0]->session_id.'/'.$info[0]->prog_name, 'class="form-horizontal well"');
?>

<?php if(validation_errors() != false) : ?>
	<div class="alert alert-warning alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<br />
		<fieldset id="error">
		<legend><h2>Sorry, something went wrong...</h2></legend>
		<?php echo validation_errors('<p class="error">'); ?>
		</fieldset>
	</div>	
<?php  endif; ?>

<?php if(isset($error)) : ?>
	<div class="alert alert-warning alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<br />
		<fieldset id="error">
		<legend><h2>Sorry, something went wrong...</h2></legend>
		<p class="error"><?php echo $error; ?></p>
		</fieldset>
	</div>	
<?php  endif; ?>

<?php

// Basic Information on file structure 
echo  '<fieldset>';
echo '<h2>File Names and Details</h2>';

if ($info[0]->num_addition > 0)
	echo '<p>I noticed you mentioned having some additional files. Please enter in the name and details here.';

$ix = 1;

// go through source files
if( $info[0]->num_source > 0)
{
	//echo '<h3>Source files</h3>';
	for( $i=1; $i<=$info[0]->num_source; $i++ )
	{
		// See if the file name already exists
		if ( isset($files[$i-1]) ) {
			$fname = $files[$i-1];
			foreach ($fileinfo as $key=>$file){
				if($file->file_name == $fname){
					$r = $file->file_name;
					break;
				}
			}
			if ( !isset($r) ) $r = set_value('add'.$i);
		} else { $r = set_value('add'.$i);}
		
		// Print file info to screen
		echo '<div class="control-group">';
		echo "<legend>Source File $i</legend>";
		echo form_label('File Name', 'add'.$ix );
		echo '<div class="controls">';
		echo form_input('add'.$ix, $r ) ;
		echo '</div></div>'; 
		
		// =================================================
		// set the previous permissions
		// =================================================
		$a1 = FALSE; $a2 = FALSE;  // INITIALIZE VARIABLES
		$s1 = FALSE; $s2 = FALSE; $s3=FALSE;
		$m1 = FALSE; $m2 = FALSE; 
		
		if ( isset($files[$i-1]) ) {
			$fname = $files[$i-1];
			foreach ($fileinfo as $key=>$file){
				if($file->file_name == $fname){
					$r = $file->file_name;
					
					// admin properties
					if ( $file->admin_file == 1) 
					{  $a2 = TRUE; } else { $a1 = TRUE; } // REPLACE PREVIOUS 
					
					// strem type properties
					if ( $file->stream_type == "input") {
						  $s2 = TRUE; 
					} else if ( $file->stream_type == "output") {
						 $s3 = TRUE; 
					} else if ( $file->stream_type == "source") {
						 $s1 = TRUE; 
					}
					
					// multi properties
					if ( $file->multi_part == 1) 
					{  $m2 = TRUE; } else { $m1 = TRUE; } // REPLACE PREVIOUS 
					
					break;
				}
			}
			if ( !isset($r) ) $r = set_value('add'.$i);
		} else { $r = set_value('add'.$i); }
		// =================================================
		
		echo form_hidden('stream_type'.$ix, 'source');
		echo form_hidden('multi'.$ix, '0');
		?>
		<div class="control-group">
			<?php echo form_label('Will this source file be submitted by the student or provided by the Administrator?' , 'admin'.$ix ); ?>
			<div class="controls">
				<table>
					<tr>
						<td><?php echo form_radio('admin'.$ix, '0', $a1); ?></td>
						<td>Student Submits file</td>
					</tr>
					<tr>
						<td><?php echo form_radio('admin'.$ix, '1', $a2) ; ?></td>
						<td>Administrator provided file</td>
					</tr>
				</table>
			</div>
		</div>
		<?php
		$ix++;
	}
}



// ============================================================
// go through input files
if( $info[0]->num_input > 0)
{
	//echo '<h3>Input files</h3>';
	for( $i=1; $i<=$info[0]->num_input; $i++ )
	{
		echo '<div class="control-group">';
		echo "<legend>Input File $i</legend>";
		echo form_label('File Name', 'add'.$ix );
		echo '<div class="controls">';
		echo form_input('add'.$ix, $r ) ;
		echo '</div></div>'; 
		
		// =================================================
		// set the previous permissions
		// =================================================
		$a1 = FALSE; $a2 = FALSE;  // INITIALIZE VARIABLES
		$s1 = FALSE; $s2 = FALSE; $s3=FALSE;
		$m1 = FALSE; $m2 = FALSE; 
		
		if ( isset($files[$i-1]) ) {
			$fname = $files[$i-1];
			foreach ($fileinfo as $key=>$file){
				if($file->file_name == $fname){
					$r = $file->file_name;
					
					// admin properties
					if ( $file->admin_file == 1) 
					{  $a2 = TRUE; } else { $a1 = TRUE; } // REPLACE PREVIOUS 
					
					// strem type properties
					if ( $file->stream_type == "input") {
						  $s2 = TRUE; 
					} else if ( $file->stream_type == "output") {
						 $s3 = TRUE; 
					} else if ( $file->stream_type == "source") {
						 $s1 = TRUE; 
					}
					
					// multi properties
					if ( $file->multi_part == 1) 
					{  $m2 = TRUE; } else { $m1 = TRUE; } // REPLACE PREVIOUS 
					
					break;
				}
			}
			if ( !isset($r) ) $r = set_value('add'.$i);
		} else { $r = set_value('add'.$i); }
		// =================================================
		
		// indicate that it is an input file
		echo form_hidden('stream_type'.$ix, 'input');
		
		// additional info
		?>
		<div class="control-group">
			<?php echo form_label('Will this input file be submitted by the student or provided by the Administrator?' , 'admin'.$ix ); ?>
			<div class="controls">
				<table>
					<tr>
						<td><?php echo form_radio('admin'.$ix, '0', $a1); ?></td>
						<td>Student Submits file</td>
					</tr>
					<tr>
						<td><?php echo form_radio('admin'.$ix, '1', $a2) ; ?></td>
						<td>Administrator provided file</td>
					</tr>
				</table>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo form_label('Will this file need fresh/different content for every test case?' , 'multi'.$ix); ?>
			<div class="controls">
				<table>
					<tr>
						<td><?php echo form_radio('multi'.$ix, '0', $m1); ?></td>
						<td>No, I will only need this file once.</td>
					</tr>
					<tr>
						<td><?php echo form_radio('multi'.$ix, '1', $m2) ; ?></td>
						<td>Yes, I will need the file forevery test case.</td>
					</tr>
				</table>
			</div>
		</div>
		
		<?php
		$ix++;
	}
}

?>

<div class="form-actions">
<?php echo anchor("class_admin/tc/stage1/".$info[0]->class_id.'/'.$info[0]->session_id.'/'.$info[0]->prog_name, 'Back', 'class = "btn btn-large btn-primary"'); ?>
 <?php echo form_submit('submit', 'Next step...', 'class = "btn btn-large btn-primary"');?>
 
</div>

</fieldset>

<?php echo form_close(); ?>



<p>Note: For single part files these will be included along with the main file since only one version of the file is required.</p>
<p>For Muli-part, since these are all conected to individual questions, these question will be attached to each test case</p>

</div>