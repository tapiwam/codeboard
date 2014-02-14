<div class="container">
	
<h2> Create an Instructor Account</h2>

<p>For this type of account, approval will first need to be granted by the site administrator before being active.</p>
<p>If approval is not granted in a timely manner please refer to the contact page for the webmaster's contact information. Thanks.</p>

<?php echo form_open('user/create_instructor', 'class="form-horizontal well"'); ?>

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
	echo '<div class="form-group">';
	//echo form_label('First Name', 'first_name', 'class="control-label"');
        echo '<label for="first_name" class="col-lg-2 control-label">First Name</label>';
        echo '<div class="col-lg-8">';
	echo form_input('first_name', set_value('first_name'), 'class="form-control input-xlarge"');
	echo '</div>';
	echo '</div>';
	
	echo '<div class="form-group">';
	//echo form_label('Last Name', 'last_name', 'class="control-label"');
        echo '<label for="last_name" class="col-lg-2 control-label">Last Name</label>';
        echo '<div class="col-lg-8">';
	echo form_input('last_name', set_value('last_name'), 'class="form-control input-xlarge"');
	echo '</div>';
	echo '</div>';
	
	echo '<div class="form-group">';
	// echo form_label('Email Address', 'email', 'class="control-label"');
        echo '<label for="email" class="col-lg-2 control-label">Email Adrress</label>';
        echo '<div class="col-lg-8">';
	echo form_input('email', set_value('email'), 'class="form-control input-xlarge"');
	echo '</div>';
	echo '</div>';
	?>	
	
</fieldset>

<fieldset>
	<legend>Login Info</legend>
	
	<?php
	echo '<div class="form-group">';
	// echo form_label('Username (e.g. john1.doe)', 'username', 'class="control-label"');
        echo '<label for="username" class="col-lg-2 control-label">Username</label>';
        echo '<div class="col-lg-8">';
	echo form_input('username', set_value('username'), 'class="form-control input-xlarge"');
	echo '</div>';
	echo '</div>';
	
	echo '<div class="form-group">';
	// echo form_label('Password', 'password', 'class="control-label"');
        echo '<label for="password" class="col-lg-2 control-label">Password</label>';
        echo '<div class="col-lg-8">';
	echo form_password('password', set_value('password'), 'class="form-control input-xlarge"');
	echo '</div>';
	echo '</div>';
	
	echo '<div class="form-group">';
	// echo form_label('Password Again', 'password2', 'class="control-label"');
        echo '<label for="password2" class="col-lg-2 control-label">Password Again</label>';
        echo '<div class="col-lg-8">';
	echo form_password('password2', set_value('password2'), 'class="form-control input-xlarge"');
	echo '</div>';
	echo '</div>';
	
	// echo form_submit('submit', 'Create Account');
	?>
	
	<div class="col-lg-10 col-lg-offset-2">
	<?php 
	 echo form_submit('submit', 'Request Instructor Account', 'class = "btn btn-primary"');
	 ?>
	</div>
	
</fieldset>

</form>
</div>
