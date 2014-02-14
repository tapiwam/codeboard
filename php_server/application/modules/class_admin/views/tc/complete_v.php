<!--
<pre><?php print_r($classinfo) ;?></pre>
<pre><?php print_r($sessioninfo) ;?></pre>
<pre><?php print_r($info) ;?></pre>
-->

<div class="jumbotron">
	<div class="container">
		<h2>Class: <?php echo $classinfo[0]->class_name;?></h2>
		<h4>Session: <?php echo $sessioninfo[0]->session_name ;?></h4>
		<hr />
		<h5>Start Date: <?php echo date( "D F j, Y", strtotime($sessioninfo[0]->start) ) ;?></h5>
		<h5>Due Date: <?php echo date( "D F j, Y", strtotime($sessioninfo[0]->end)) ;?></h5>
		<h5>Late Submission: <?php echo date( "D F j, Y", strtotime($sessioninfo[0]->late)) ;?></h5>
	</div>
</div>

<div class="container"><?php $page=6; ?>

<div class="row">

<!-- ----------------------------------------------------->
	
	<div class="col-md-6">
		<h2>Assignment Details</h2>
		<table  class="table table-bordered table-striped table-hover">
			<tr>
				<td>Number of Files</td>
				<td><?php echo $info[0]->num_addition; ?></td>
			</tr>
			<tr>
				<td>Number of Test Cases</td>
				<td><?php echo $info[0]->num_tc; ?></td>
			</tr>
			<tr>
				<td>Standard input Required</td>
				<td><?php  
					if ( $info[0]->input == 1 )
						echo 'Yes';
					else {
						echo 'No';
					}
					?></td>
			</tr>
			<tr>
				<td>Standard output produced</td>
				<td><?php  
					if ( $info[0]->output == 1 )
						echo 'Yes';
					else {
						echo 'No';
					}
					?></td>
			</tr>
		</table>
		
	</div>

<!-- ----------------------------------------------------->
	
	<div class="col-md-6">
		<h2>Grading Scheme</h2>
		<table  class="table table-bordered table-striped table-hover">
			<tr>
				<td>Submission Points</td>
				<td><?php echo $info[0]->s_points; ?></td>
			</tr>
			<tr>
				<td>Documentation Points</td>
				<td><?php echo $info[0]->d_points; ?></td>
			</tr>
			<tr>
				<td>Execution Points</td>
				<td><?php echo $info[0]->e_points; ?></td>
			</tr>
			<tr>
				<td>Total Points</td>
				<td><?php
				 	$total = $info[0]->s_points + $info[0]->d_points + $info[0]->e_points;
				 	echo $total;
				 	?>
				</td>
			</tr>
		</table>
		
	</div>
	
</div> <?php // end row fluid ?>

<!-- ----------------------------------------------------->

<h2>Program Description</h2>
<div class="row">
	<div class="col-md-12">
		<pre><?php echo $info[0]->description; ?></pre>
	</div>
</div>

<!-- ----------------------------------------------------->

<h2>Source and Input Files</h2>
<?php 
$i=1;
foreach ($fileinfo as $file)
{
	if ( $file->stream_type == "source" || ( $file->stream_type == 'input' && $file->multi_part == 0 ) )
	{
		if( ($i%2) == 1 )
		echo '<div class="row">';
		
		echo '<div class="col-md-6">';
		echo "Source: " . $file->file_name;
		echo '<pre>'. $file->file_content . '</pre>';
		echo '</div>';
		$i++;
			
		if( ($i%2) == 1 )
			echo '</div>';
	}
}

if( ($i%2) == 0 )
	echo '</div>';
?>

<!-- ----------------------------------------------------->

<?php 
/// Count up all single input files
$count=0;
foreach ($fileinfo as $fi) {
	if( $file->stream_type == "input" && $file->multi_part == 0 )
		$count++; 
}
?>

<?php if($count > 0): ?>
<h2>Input</h2>
<?php 
$i=1;
foreach ($fileinfo as $file)
{
	if ( $file->stream_type == "input" && $file_multi==0)
	{
		if( ($i%2) == 1 )
			echo '<div class="row">';
		
		echo '<div class="col-md-6">';
		echo "Input: " . $file->file_name;
		echo '<pre>'. htmlentities($file->file_content) . '</pre>';
		echo '</div>';
		$i++;
			
		if( ($i%2) == 1 )
			echo '</div>';
	}
}

if( ($i%2) == 0 )
	echo '</div>';
?>
<?php endif; ?>
<!-- ----------------------------------------------------->

<h2>Test Cases</h2>
<?php 
// get file names for test cases
$files = array();
foreach ($fileinfo as $file)
	if($file->multi_part == 1 && $file->stream_type != "source")
		$files[] = $file->file_name; 

$files = array_unique($files);

for($i=1; $i<=$info[0]->num_tc; $i++ )
{
	echo "<h4>Test Case #$i</h4>";
	// Display Input first
	$g=1; $hdr=0; $ftr=0;
	foreach($files as $file)
	{	
		foreach($fileinfo as $f)
		{
			if( $f->file_name == $file && $f->meta == $i && $f->stream_type=="input")
			{
				if( ($g%2) == 1 ) { echo '<div class="row">'; $hdr++; }
				echo '<div class="col-md-6">';
				echo "Input: " . $f->file_name . '<br />';
				echo '<pre>'. htmlentities($f->file_content) . '</pre>';
				echo '</div>';
				if( ($g%2) == 0 ){ echo '</div>'; $ftr++; }
				$g++;
			}	
		}
	}
	if( $hdr != $ftr ) echo '</div>';
	
	// Display Output here -> same code as above
	$g=1; $hdr=0; $ftr=0;
	foreach($files as $file)
	{	
		foreach($fileinfo as $f)
		{
			if( $f->file_name == $file && $f->meta == $i && $f->stream_type=="output")
			{
				if( ($g%2) == 1 ) { echo '<div class="row">'; $hdr++; }
				echo '<div class="col-md-6">';
				echo "Output: " . $f->file_name . '<br />';
				echo '<pre>'. htmlentities($f->file_content) . '</pre>';
				echo '</div>';
				if( ($g%2) == 0 ){ echo '</div>'; $ftr++; }
				$g++;
			}	
		}
	}
	if( $hdr != $ftr ) echo '</div>';
		echo '<hr />';	
}
?>

<a href="javascript:history.go(-1)" class="btn btn-primary btn-large">Back</a>

</div>




