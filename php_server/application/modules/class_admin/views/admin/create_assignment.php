

<div id="">
	<h1>Create Assignment</h1>
	
	<?php echo 'class data:  '; print_r($classinfo) ; echo '<br />' ?>
	<?php echo 'session data:  '; print_r($sessioninfo) ; echo '<br />' ?>
	
	<?php
	
	echo form_open('class_admin/admin_create/create_assignment');
	 
	echo form_input('class_id', $class_id);
	echo form_input('session_id', $session_id);
	
	
	
	echo form_label('Program Name', 'prog_name');
	echo form_input('prog_name', set_value('prog_name'));
	
	?>
	<!---
	<input type=hidden name="prog_key" id="prog_key" value="" />
	<div id="editor"><pre id="editor"><?php echo $template;?></pre></div>
	-->
	
	
	 
	 <?php

	
	 echo form_label('Program Key', 'prog_key');
	 echo '<br />';
	 echo form_textarea('prog_key', set_value('prog_key'));
	
	echo '<br />';
	
	echo form_label('Number of test cases', 'num_tc');
	echo form_input('num_tc', set_value('num_tc'));
	
	 echo form_submit('submit', 'Create');
	 
	 echo form_close();
	
	?>
	
</div>

<script>
	// 	This part setsup the ace IDE
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/xcode");
    editor.getSession().setMode("ace/mode/c_cpp");
    
    // This is the fuction that gets the ace code when submiting the form
	function getVal() {
		document.getElementById('prog_key').value = editor.getSession().getValue();
	}
</script>

