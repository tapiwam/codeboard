<div class="container">

    <?php echo form_open('user/validate_credentials', 'class="form-horizontal well"'); ?>

    <fieldset>
        <legend>Login here</legend>

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

        <?php if (isset($error)) : ?>
            <div class="alert alert-warning alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <br />
                <fieldset id="error">
                    <legend><h2>Sorry, something went wrong...</h2></legend>
                    <p class="error"><?php echo $error; ?></p>
                    <?php
                    if (stristr($error, "active")) {
                        echo safe_mailto("cop3014.lab@gmail.com", 'Email admin', 'class="btn btn-primary"');
                    }
                    ?>
                </fieldset>
            </div>	
        <?php endif; ?>


        <div class="form-group">
            <label for="username" class="col-lg-2 control-label">Username</label>
            <div class="col-lg-8">
                <?php echo form_input('username', set_value('username'), 'class="form-control input-xlarge"'); ?>
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="col-lg-2 control-label">Password</label>
            <div class="col-lg-8">
                <?php echo form_password('password', '', 'class="form-control input-xlarge"'); ?>
            </div>
        </div>

        <div class="col-lg-10 col-lg-offset-2">
            <?php echo form_submit('submit', 'login', 'class = "btn btn-primary"'); ?>
            <?php echo anchor('site/register', 'Register', 'class = "btn btn-default"'); ?>
        </div>

    </fieldset>

    <?php echo form_close(); ?>


</div>
