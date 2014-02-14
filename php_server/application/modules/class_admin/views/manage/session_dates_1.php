<script>
  $(function() {
   var nowTemp = new Date();
var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
 
var checkin = $('#dpd1').datepicker({
  	format: 'yyyy-mm-dd',
  	todayBtn: true,
  	todayHighlight: true
  	
}).on('changeDate', function(ev) {
  if (ev.date.valueOf() > checkout.date.valueOf()) {
    var newDate = new Date(ev.date)
    newDate.setDate(newDate.getDate() + 1);
    checkout.setValue(newDate);
  }
  checkin.hide();
  $('#dpd2')[0].focus();
}).data('datepicker');

var checkout = $('#dpd2').datepicker({
 	format: 'yyyy-mm-dd',
  	onRender: function(date) {
    	return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
  	}
}).on('changeDate', function(ev) {
	if (ev.date.valueOf() > late.date.valueOf()) {
    var newDate = new Date(ev.date)
    newDate.setDate(newDate.getDate() + 1);
    late.setValue(newDate);
    }
  checkout.hide();
  $('#dpd3')[0].focus();
}).data('datepicker');
  
var late = $('#dpd3').datepicker({
 	format: 'yyyy-mm-dd',
  	onRender: function(date) {
    	return date.valueOf() <= checkout.date.valueOf() ? 'disabled' : '';
  	}
}).on('changeDate', function(ev) {
  late.hide();
}).data('datepicker');
  
var t_start = $('#tp1').timepicker();
var t_end = $('#tp2').timepicker();
var t_late = $('#tp3').timepicker();
        
});
</script>

<div class="container">
	
	<h2>Manage lab dates and times</h2> 
	<hr />
	
	<div class="row">
		<div class="span2"><h4>Lab: </h4></div>
		<div class="span6"><h4><?php echo $sessioninfo[0]->session_name; ?> </h4></div>
	</div>
	
	<div class="row">
		<div class="span2"><h4>Starts on: </h4></div>
		<div class="span6"><h4><?php echo date( "D F j, Y", strtotime($sessioninfo[0]->start_date)) ;?> at <?php echo $sessioninfo[0]->start_time ;?></h4></div>
	</div>
	
	<div class="row">
		<div class="span2"><h4>Is late after:</h4></div>
		<div class="span6"><h4><?php echo date( "D F j, Y", strtotime($sessioninfo[0]->end_date)) ;?> at <?php echo $sessioninfo[0]->end_time ;?></h4></div>
	</div>
	
	<div class="row">
		<div class="span2"><h4>late deadline:</h4></div>
		<div class="span6"><h4><?php echo date( "D F j, Y", strtotime($sessioninfo[0]->late_date)) ;?> at <?php echo $sessioninfo[0]->late_time ;?></h4></div>
	</div>
	
	<hr />
	
	<div class="row-fluid">
		<div class="span8">
			<?php echo form_open("class_admin/manage/set_lab_time/$class_id/$session_id" , 'class="form-horizontal well"');?>
	
			<fieldset>
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
			
			echo form_hidden('class_id', $class_id);
			//echo form_hidden('term', $classinfo[0]->term);
			?>
			
			<div class="control-group">
			<label class="control-label" for="session_name">Start</label>
			<div class="controls">
				<div class="row">
					<div class="span9">
						<div class="span6">
							<div class="span2"></div>Date:
							<div class="input-append bootstrap-datepicker">
								<span class="add-on"><i class="icon-calendar"></i></span>
								<?php echo form_input('start_date', $sessioninfo[0]->start_date, 'id="dpd1" class="input-small" size="16" type="text" ');  ?>
					        </div>
						</div>
						
						<div class="span6">
							<div class="span2"></div>Time:
							<div class="input-append bootstrap-timepicker">
					            <?php echo form_input('start_time', $sessioninfo[0]->start_time, 'id="tp1" class="input-small" size="16" type="text" ');  ?>
					            <span class="add-on"><i class="icon-time"></i></span>
					        </div>
							
						</div>
					</div>
				</div>
			 
			</div>
			</div>
			
			
			<div class="control-group">
			<label class="control-label" for="session_name">On-time</label>
			<div class="controls">
				<div class="row">
					<div class="span9">
						<div class="span6">
							<div class="span2"></div>Date:
							
							<div class="input-append bootstrap-datepicker">
								<span class="add-on"><i class="icon-calendar"></i></span>
								<?php echo form_input('end_date', $sessioninfo[0]->end_date, 'id="dpd2" class="input-small" size="16" type="text" ');  ?>
					        </div>
						</div>
						
						<div class="span6">
							<div class="span2"></div>Time:
							<div class="input-append bootstrap-timepicker">
					            <?php echo form_input('end_time', $sessioninfo[0]->end_time, 'id="tp2" class="input-small" size="16" type="text" ');  ?>
					            <span class="add-on"><i class="icon-time"></i></span>
					        </div>
							
						</div>
					</div>
				</div>
			 
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="session_name">Late</label>
			<div class="controls">
			 <?php // echo form_input('late_date', "", 'id="dpd3" class="span2" size="16" type="text" ');  ?>
				<div class="row">
					<div class="span9">
						<div class="span6">
							<div class="span2"></div>Date:
								<div class="input-append bootstrap-datepicker">
									<span class="add-on"><i class="icon-calendar"></i></span>
									<?php echo form_input('late_date', $sessioninfo[0]->late_date, 'id="dpd3" class="input-small" size="16" type="text" ');  ?>
					     	</div>
						</div>
						
						<div class="span6">
							<div class="span2"></div>Time:
							<div class="input-append bootstrap-timepicker">
					            <?php echo form_input('late_time', $sessioninfo[0]->late_time, 'id="tp3" class="input-small" size="16" type="text" ');  ?>
					            <span class="add-on"><i class="icon-time"></i></span>
					        </div>
							
						</div>
					</div>
				</div>
			
			</div>
			</div>
			
			
			<div class="form-actions">
			 <?php echo form_submit('submit', 'Update', 'class = "btn btn-large btn-primary"');?>
			 
			</div>
			</fieldset>
			<?php echo form_close(); ?>
		</div>
		
		<div class="span4">
			<?php 
				$data['class_id'] = $class_id;
				$this->load->view('includes/class_sidebar', $data); 
			?>
		</div>
	</div>
	
	
	
</div>