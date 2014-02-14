<div class="container">
	<!--
	<pre><?php print_r($students);  ?></pre>
	<pre><?php print_r($programs); ?></pre>
	-->
	
	<div class="row">
		<h2>Gradebook</h2>
		<h4><?php echo "class name and term here"; ?></h4>
		
		<div class="col-md-8">
			<table class="table table-bordered table-striped">
				<th>Student</th>
				<th>Total Points</th>
				<th>Possible Points</th>
				<th>Average</th>
				<th>Grade</th>
				<?php foreach ($programs as $prog): ?>
					<th><?php echo $prog; ?> </th>
				<?php endforeach; ?>
				
				<!-- --------------------------------------->
				
				<?php foreach ($students as $stud): ?>
				<tr>
					
					<?php 
						echo '<td>'. $stud->name . '</td>' ;
						echo '<td>'. $stud->total . '</td>' ;
						echo '<td>'. $possible . '</td>' ;
						echo '<td>'. $stud->avg . '</td>';
						echo '<td>'. $stud->grade . '</td>';
						foreach($stud->scores as $score)
							{ echo '<td>'.$score.'<td>'; }
					?>
				</tr>
				<?php endforeach; ?>
				
			</table>
			
		</div>
	
		<div class="col-md-4">
			<?php 
				$data['class_id'] = $class_id;
				$this->load->view('includes/class_sidebar', $data); 
			?>
		</div>
	
	</div>
</div>