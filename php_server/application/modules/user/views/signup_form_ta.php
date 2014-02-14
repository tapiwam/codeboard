<div class="container">
	
<h2> Create a Teaching Assitant Account</h2>

<p>Teaching assistants will have many....</p>

<?php echo form_open('user/create_ta', 'class="form-horizontal well"'); ?>

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
		<pre class="error"><?php echo $error; ?></pre>
		</fieldset>
	</div>	
<?php  endif; ?>

<fieldset>
	<legend>Personal Information</legend>
	
	<?php
	echo '<div class="control-group">';
	echo '<div class="controls">';
	echo form_label('First Name', 'first_name', 'class="control-label"');
	echo form_input('first_name', set_value('first_name'), 'class="input-xlarge"');
	echo '</div>';
	echo '</div>';
	
	echo '<div class="control-group">';
	echo '<div class="controls">';
	echo form_label('Last Name', 'last_name', 'class="control-label"');
	echo form_input('last_name', set_value('last_name'), 'class="input-xlarge"');
	echo '</div>';
	echo '</div>';
	
	echo '<div class="control-group">';
	echo '<div class="controls">';
	echo form_label('Email Address', 'email', 'class="control-label"');
	echo form_input('email', set_value('email'), 'class="input-xlarge"');
	echo '</div>';
	echo '</div>';
	?>	
	
</fieldset>

<fieldset>
	<legend>Login Info</legend>
	
	<hr />
	<?php
	echo '<div class="control-group">';
	echo '<div class="controls">';
	echo form_label('Username (e.g. john1.doe)', 'username', 'class="control-label"');
	echo form_input('username', set_value('username'), 'class="input-xlarge"');
	echo '</div>';
	echo '</div>';
	
	echo '<div class="control-group">';
	echo '<div class="controls">';
	echo form_label('Password', 'password', 'class="control-label"');
	echo form_password('password', set_value('password'), 'class="input-xlarge"');
	echo '</div>';
	echo '</div>';
	
	echo '<div class="control-group">';
	echo '<div class="controls">';
	echo form_label('Password Again', 'password2', 'class="control-label"');
	echo form_password('password2', set_value('password2'), 'class="input-xlarge"');
	echo '</div>';
	echo '</div>';
	
	// echo form_submit('submit', 'Create Account');
	?>
	
	<div class="form-actions">
	<?php 
	 echo form_submit('submit', 'Request Instructor Account', 'class = "btn btn-large btn-primary"');
	 ?>
	</div>
	
</fieldset>

</form>
</div>
