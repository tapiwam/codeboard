<div class="container">
	<pre><?php // print_r($programs);  ?></pre>
	
	<div class="row">
	
		<div class="col-md-8">
			<?php echo form_open("gradebk/update_scale/$class_id"); ?>
			
			<h2>Gradebook Scale</h2>
			<h4><?php echo "class name and term here"; ?></h4>
			
			<table class="table table-bordered table-striped">
				<th>Program ID</th>
				<th>Program Name</th>
				<th>Session Name</th>
				<th>Status</th>
				
				<?php foreach($programs as $prog): ?>
					<tr>
						<td><?php echo $prog->id; ?></td>
						<td><?php echo $prog->prog_name; ?></td>
						<td><?php echo $prog->session_name; ?></td>
						<td><?php 
								$options = array(
									'0' => 'Non-graded',
									'1' => 'Graded' ,
									'2' => 'Extra Credit',                 
								);
								echo form_dropdown($prog->id, $options, $prog->graded);
						 	?>
						 </td>
						
					</tr>
				<?php endforeach; ?>
			</table>
			
			<hr>
			<?php echo form_submit('submit', 'Update', 'class="btn btn-primary btn-large"'); echo "     "; ?>
			<?php echo anchor("gradebk/index/$class_id" , 'Gradebook', 'class="btn btn-primary btn-large"' ); echo "    "; ?>
			<?php echo anchor("gradebk/grade_scale/$class_id" , 'Back', 'class="btn btn-primary btn-large"' ); ?>
			
			
			<?php echo form_close(); ?>
		</div>
	
		<div class="col-md-4">
			<?php 
				$data['class_id'] = $class_id;
				$this->load->view('includes/class_sidebar', $data); 
			?>
		</div>
	
	</div>
</div>