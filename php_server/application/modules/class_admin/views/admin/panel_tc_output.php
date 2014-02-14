<div id="">
	

<h2>Test Case Results</h2>	

<?php echo "Class Info:  "; print_r($classinfo); ?>
<br /><br />
<?php echo "Session Info:  "; print_r($sessioninfo); ?>
<br /><br />
<?php echo "Prog Info:  ";  print_r($prog); ?>
<br /><br />
<hr />



<h4>Program Key</h4>
	<div style="border: 1px solid gray;">
		<pre><?php echo $prog[0]->prog_key; ?></pre>
	</div>

	<h4 style="margin-top: 25px;">Test Case Input</h4>
	<?php
	
	echo form_open("class_admin/admin_create/create_tc_output/$class_id/$session_id/".$prog[0]->id);
	
	
	echo '<br />';
	for($i=0; $i<$prog[0]->num_tc ; $i++ )
	{
		$v = $i+1;
		echo "<h4>Test Case $v</h4>";
		echo form_label('TC Input '.$v , 'input_'.$i);
		echo '<br />';
		echo form_textarea('input_'.$i , set_value('input_'.$i) );
		echo '<br />';
		
		echo form_label('Output '.$v , 'output_'.$i);
		echo '<br />';
		echo form_textarea('output_'.$i , set_value('output_'.$i) );
		echo '<br /><hr />';
	}
	echo form_submit('submit', 'Submit Test Case Input');
	echo form_close();
	
	?>
</div>