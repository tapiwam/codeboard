<div class="container"> <?php $page=1; ?>

<?php if(isset($info) ) : ?>	
	
<?php $this->load->view('tc/bread_crumbs', array('info' => $info)); ?>

<?php endif; ?>
	
<h3>Create an Assignment</h3>
<p>Please enter in basic information for the assignment.</p>

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

// echo '<pre>'; print_r($info[0]); echo '</pre><hr />'; 


if(isset($class_id))
{
	echo form_open('class_admin/tc/basicinfo/'.$class_id.'/'.$session_id, 'class="form-horizontal well"');
} else {
	echo form_open('class_admin/tc/basicinfo/'.$this->uri->segment(3).'/'.$this->uri->segment(4), 'class="form-horizontal well"');
}

// Basic Information on file structure 
echo  '<fieldset>';

echo '<h2>Program Information</h2>';

echo '<div class="control-group">';
echo form_label('Assignment Name ( e.g. hello_world )', 'prog_name', 'class="control-label"');
echo '<div class="controls">';
if ( isset($info[0]) )  $a = $info[0]->prog_name; else $a = set_value('prog_name');
echo form_input('prog_name', $a);
echo '</div></div>';

echo '<div class="control-group">';
echo form_label('Asignment Type', 'type', 'class="control-label"');
echo '<div class="controls">';
$types = array( 
	'cpp' => 'cpp',
	'c' => 'c',
	);
echo form_dropdown('type', $types, 'cpp');
echo '</div></div>';

echo '<div class="control-group">';
echo form_label('Number of Test Cases', 'num_tc', 'class="control-label"');
echo '<div class="controls">';
if ( isset($info[0]) )  $c = $info[0]->num_tc; else $c = set_value('num_tc');
echo form_input('num_tc', $c);
echo '</div></div>';

/**************************************************************************/
echo '<h2>File and Data Layout</h2>';
/**************************************************************************/

$g = TRUE; 
if ( isset($info[0]) )  {
	if ($info[0]->input == 0){
		$g = FALSE; 
	}
}
	
$h = TRUE; 
if ( isset($info[0]) )  {
	if ($info[0]->output == 0){
		$h = FALSE; 
	}
}
?>

<div class="control-group">
	<?php echo form_label('Does the assignment require any Standard Input (cin)?', 'stream_type');?>
	<div class="controls">
		<table>
			<tr>
				<td><?php echo form_checkbox("cin", TRUE, $g ); ?></td>
				<td>Input required</td>
			</tr>
		</table>
	</div>
</div>

<div class="control-group">
	<?php echo form_label('Does the assignment produce any Standard Output (cout)?', 'stream_type');?>
	<div class="controls">
		<table>
			<tr>
				<td><?php echo form_checkbox("cout", TRUE, $h ); ?></td>
				<td>Output produced</td>
			</tr>
		</table>
	</div>
</div>

<?php 

/*
echo '<div class="control-group">';
echo form_label('Number of files (e.g. header/text/C++ Files)', 'num_addition', 'class="control-label"');
echo '<div class="controls">';
if ( isset($info[0]) )  $b = $info[0]->num_addition; else $b = set_value('num_addition');
echo form_input('num_addition', $b );
echo '</div></div>';
*/

/**************************************************************************/

echo '<div class="control-group">';
echo form_label('Number of Source Files (e.g. .cpp, .c and .h Files)', 'num_source', 'class="control-label"');
echo '<div class="controls">';
if ( isset($info[0]) )  $b = $info[0]->num_source; else $b = set_value('num_source');
echo form_input('num_source', $b );
echo '</div></div>';

echo '<div class="control-group">';
echo form_label('Number of Input Files (excluding standard input)', 'num_input', 'class="control-label"');
echo '<div class="controls">';
if ( isset($info[0]) )  $b = $info[0]->num_input; else $b = set_value('num_input');
echo form_input('num_input', $b );
echo '</div></div>';

echo '<div class="control-group">';
echo form_label('Number of Output Files (excluding standard output)', 'num_output', 'class="control-label"');
echo '<div class="controls">';
if ( isset($info[0]) )  $b = $info[0]->num_output; else $b = set_value('num_output');
echo form_input('num_output', $b );
echo '</div></div>';

/**************************************************************************/
echo '<h2>Point Structure</h2>';
/**************************************************************************/

echo '<div class="control-group">';
echo form_label('Points for Submission','s_points', 'class="control-label"');
echo '<div class="controls">';
if ( isset($info[0]) )  $d = $info[0]->s_points; else $d = set_value('s_points');
echo form_input('s_points', $d);
echo '</div></div>';

echo '<div class="control-group">';
echo form_label('Points for Compiling','c_points', 'class="control-label"');
echo '<div class="controls">';
if ( isset($info[0]) )  $f = $info[0]->c_points; else $f = set_value('c_points');
echo form_input('c_points', $f);
echo '</div></div>';

echo '<div class="control-group">';
echo form_label('Points for Documentation','d_points', 'class="control-label"');
echo '<div class="controls">';
if ( isset($info[0]) )  $e = $info[0]->d_points; else $e = set_value('d_points');
echo form_input('d_points', $e);
echo '</div></div>';

echo '<div class="control-group">';
echo form_label('Points for Execution','e_points', 'class="control-label"');
echo '<div class="controls">';
if ( isset($info[0]) )  $f = $info[0]->e_points; else $f = set_value('e_points');
echo form_input('e_points', $f);
echo '</div></div>';

echo '<div class="control-group">';
echo form_label('Late Submission Deduction','late', 'class="control-label"');
echo '<div class="controls">';
if ( isset($info[0]) )  $f = $info[0]->late; else $f = set_value('late');
echo form_input('late', $f);
echo '</div></div>';

?>

<div class="form-actions">
 <?php echo form_submit('submit', 'Next step...', 'class = "btn btn-large btn-primary"');?>
 
</div>

</fieldset>

<?php echo form_close(); ?>

	
</div>
