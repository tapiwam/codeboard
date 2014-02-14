<div class="container">
	<h2>Success...</h2>
	
	<div class="row">
		<div class="col-md-8">
			<p>Session created successfully. You can start adding assignments to this session.</p>
			<?php // echo anchor('admin', 'Classes', 'class = "btn btn-large btn-primary"');?>
		</div>
		
		<div class="col-md-4">
			<?php 
				$data['class_id'] = $class_id;
				$this->load->view('includes/class_sidebar', $data); 
			?>
		</div>
	</div>
 
</div> 