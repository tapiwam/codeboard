<div class="container">

<h2>Class: <?php echo $classinfo[0]->class_name;?></h2>
<h4>Session: <?php echo $sessioninfo[0]->session_name ;?></h4>

<div class="row">
	<div class="col-md-8">
		<div class="well">
			<h5>Start Date: <?php echo date( "F j, Y", strtotime($sessioninfo[0]->start_date) ) ;?></h5>
			<h5>Due Date: <?php echo date( "F j, Y", strtotime($sessioninfo[0]->end_date)) ;?></h5>
			<h5>Late Submission: <?php echo date( "F j, Y", strtotime($sessioninfo[0]->late_date)) ;?></h5>
		</div>
		
		
		<p>In order to activate an assignment Test cases must first be created</p>
		<hr />
		
		<h2>Program Key</h2>
		<div class="well"><pre><?php echo $prog[0]->prog_key; ?></pre></div>
	</div>

	<div id="col-md-4 well">
		<ul class="nav nav-list">
			<li><h2 class="nav-header">Assignment Management</h2></li>
			 <?php 
			 echo anchor('class_admin/manage/deactivate_assignment/'. $classinfo[0]->id. '/'.$sessioninfo[0]->id.'/'.$prog[0]->id, '<li>Deactivate Assignment</li>'); 
			 echo anchor('class_admin/admin_delete/delete_assignment/'. $classinfo[0]->id. '/'.$sessioninfo[0]->id.'/'.$prog[0]->id, '<li>Delete Assignment</li>'); 
			 ?>
		</ul>
		
		<?php 
			$data['class_id'] = $class_id;
			$this->load->view('includes/class_sidebar', $data); 
		?>
	</div>

</div>

</div>