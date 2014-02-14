<div class="container">

    <h2>Settings</h2>

    <?php if (validation_errors() != false) : ?>
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <br />
            <fieldset id="error">
                <legend><h2>Sorry, something went wrong...</h2></legend>
                <?php echo validation_errors('<p class="error">'); ?>
            </fieldset>
        </div>	
    <?php endif; ?>

    <?php 
    if (isset($success)) {
        echo "<p class=\"alert alert-success\">$success</p>";
    }
    if (isset($fail)) {
        echo "<p class=\"alert alert-warning\">$fail</p>";
    }
    ?>

    <div class="row-fluid">
        <div class="span6 well">
            <h3>Email</h3>
            <hr />
            <p>Preferred Email: <?php echo $this->session->userdata('email'); ?></p>	
            <?php // echo anchor('login/update_email_option', 'edit');  ?>
            <a href="#update_email" role="button" class="btn btn-primary" data-toggle="modal">Update email</a>
        </div>

        <div class="span6 well">
            <h3>Password</h3>  
            <hr />
            <p>Last Changed: <?php echo '{{date}}'; ?></p>
            <?php // echo anchor('login/update_password_option', 'Manage Security');  ?>
            <a href="#update_password" role="button" class="btn btn-primary" data-toggle="modal">Update password</a>
        </div>
    </div>
    
    <div class="row-fluid">
        <div class="span6 well">
            <h3>Student ID</h3>
            <hr />
            <p>Current Student ID: <?php echo $this->session->userdata('student_id'); ?></p>	
            <a href="#update_sid" role="button" class="btn btn-primary" data-toggle="modal">Update Student ID</a>
        </div>

        <div class="span6 well">
            <h3>Username</h3>  
            <hr />
            <p>Current username: <?php echo $this->session->userdata('username');; ?></p>
            <a href="#update_username" role="button" class="btn btn-primary" data-toggle="modal">Update username</a>
        </div>
    </div>
    

    <hr />
    <button onclick="history.go(-1);" class="btn btn-primary btn-large">Back</button>
</div>

<!-- Email Modal -->
<div class="modal fade" id="update_email" tabindex="-1" role="dialog" aria-labelledby="Update email" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Update Email</h4>
      </div>
      <div class="modal-body">
        <p>For security reason, please enter in your current password along with your new email address</p>

    <?php echo form_open('user/settings_update', 'id="email_form"'); ?>    

        <div class="form-group">
            <label for="pass_email">Password</label>
            
    <?php echo form_password('pass_email', '', 'class="form-control input-lg" id="password"'); ?>
                <span id="validatePassword"></span>
            
        </div>

        <div class="form-group">
            <label for="username">New email</label>
            
    <?php echo form_input('email', set_value('email'), 'class="form-control input-lg", id="email"'); ?>
                <span id="validateEmail"></span>
            
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <?php echo form_submit('submit', 'Save email', 'class = "btn btn-primary" id="sendit"'); ?>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!-- Password Modal -->
<div class="modal fade" id="update_password" tabindex="-1" role="dialog" aria-labelledby="Update password" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Update password</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('user/settings_update', 'id="pass_form"'); ?>    

        <div class="form-group">
            <label for="old_pass">Current password</label>
            
    <?php echo form_password('old_pass', '', 'class="form-control input-lg" id="old_pass"'); ?>
                <span id="validatePassword1"></span>
           
        </div>

        <div class="form-group">
            <label for="new_pass">New password</label>
            
    <?php echo form_password('new_pass', '', 'class="form-control input-lg" id="new_pass"'); ?>
                <span id="validatePassword2"></span>
            
        </div>

        <div class="form-group">
            <label for="new_pass1">Confirm password</label>
            
    <?php echo form_password('new_pass1', '', 'class="form-control input-lg" id="new_pass1"'); ?>
                <span id="validatePassword3"></span>
            
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <?php echo form_submit('submit', 'Save password', 'class = "btn btn-primary" id="sendit1"'); ?>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- SID Modal -->
<div class="modal fade" id="update_sid" tabindex="-1" role="dialog" aria-labelledby="Update Student ID" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Update Student ID</h4>
      </div>
      <div class="modal-body">
        <p>For security reason, please enter in your current password along with your new Student ID</p>

        <?php echo form_open('user/settings_update', 'id="student_id"'); ?>    

        <div class="form-group">
            <label for="pass_sid">Password</label>
                <?php echo form_password('pass_sid', '', 'class="form-control input-lg" id="password"'); ?>
                <span id="validatePassword"></span>
        </div>

        <div class="form-group">
            <label for="student_id">Student ID</label>
                <?php echo form_input('student_id', set_value('student_id'), 'class="form-control input-lg", id="student_id"'); ?>
                <span id=""></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <?php echo form_submit('submit', 'Save sid', 'class = "btn btn-primary" id="sendit"'); ?>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Username Modal -->
<div class="modal fade" id="update_username" tabindex="-1" role="dialog" aria-labelledby="Update username" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Update Username</h4>
      </div>
      <div class="modal-body">
        <p>For security reason, please enter in your current password along with your new username</p>

        <?php echo form_open('user/settings_update', 'id="username"'); ?>    

        <div class="form-group">
            <label for="pass_uname">Password</label>
                <?php echo form_password('pass_uname', '', 'class="form-control input-lg" id="password"'); ?>
                <span id="validatePassword"></span>
        </div>

        <div class="form-group">
            <label for="username">New username</label>
                <?php echo form_input('username', set_value('username'), 'class="form-control input-lg", id="username"'); ?>
                <span id=""></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <?php echo form_submit('submit', 'Save username', 'class = "btn btn-primary" id="sendit"'); ?>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<script>
        function validateEmail($email) {
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            if (!emailReg.test($email)) {
                return false;
            } else {
                return true;
            }
        }

        $(document).ready(function() {

            $('#password').keyup(function() {
                var password = $('#password').val();
                $.post(
                        '/codeboard/index.php/login/check_password',
                        {'password': password},
                function(result) {
                    // clear any message that may have already been written
                    $('#validatePassword').replaceWith('');

                    if (result) {
                        $('#validatePassword').html(result);
                        if (result == '<span class="badge badge-success">Password ok.</span>') {
                            $('#sendit').prop('disabled', false);
                        }

                    }
                }
                );
            });

            $('#old_pass').keyup(function() {
                var password = $('#old_pass').val();
                $.post(
                        '/codeboard/index.php/login/check_password',
                        {'password': password},
                function(result) {
                    // clear any message that may have already been written
                    $('#validatePassword1').replaceWith('');

                    if (result) {
                        $('#validatePassword1').html(result);
                        if (result == '<span class="badge badge-success">Password ok.</span>') {
                            //$('#sendit').prop('disabled', false);
                        }

                    }
                }
                );
            });

        });
</script>

