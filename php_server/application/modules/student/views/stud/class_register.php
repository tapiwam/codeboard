<div class="container">	

    <h2>Register for a Class</h2>

    <div class="row">
        <div class="col-md-8">

            <?php if (isset($error)) : ?>
                <div class="alert">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <br />
                    <fieldset id="error">
                        <legend><h2>Sorry, something went wrong...</h2></legend>
                        <?php echo $error; ?>
                    </fieldset>
                </div>	
            <?php endif; ?>

            <p>To register for a class you will need a passcode. Please refer to your syllabus or contact your Instructor for the class you are trying to register for to get the passcode.</p>

            <hr />

            <?php
            // Prepare data for dropdown
            $options = array();

            if (isset($classes)) {
                foreach ($classes as $row) {
                    $options[$row->id] = $row->class_name . ' - ' . $row->term . ' - Instructor:' . $row->instructor;
                }
            }

            // Form
            echo form_open('student/register', 'role="form"');

            echo '<fieldset>';

            echo '<div class="form-group">';
            echo form_label('Class List', 'class_id');
            if (!empty($options)) {
                echo form_dropdown('class_id', $options, '','class="form-control"');
            } else {
                echo 'No available classes at the moment.';
            }
            echo '</div>';

            if (!empty($options)) {
                echo '<div class="form-group">';
                echo form_label('Passcode', 'passcode');
                echo form_password('passcode', '', 'class="form-control"');
                echo '</div>';
            }
            ?>

            <div class="form-actions">
            <?php echo form_submit('submit', 'Register', 'class = "btn btn-large btn-primary"'); ?>

            </div>

            </fieldset>

            <?php echo form_close(); ?>		

        </div>

        <div class="col-md-4">
            <?php $this->load->view('includes/student_sidebar'); ?> 
        </div>
    </div>
</div>

