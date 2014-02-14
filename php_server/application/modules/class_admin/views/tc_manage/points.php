<div class="container">
    <h2>Point Structure: <?php echo $prog_name ." ({$sessioninfo[0]->session_name})"; ?></h2>

    <div class="row">
        <div class="col-md-8 well">

            <?php echo form_open("class_admin/tc_manage/update_points/$class_id/$session_id/$prog_name", 'role="form"'); ?>

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
            
            <div class="control-group">
                <?php echo form_label('Compile Points', 'c_points'); ?>
                <div class="controls">
                    <?php
                    $d4 = array(
                        'name' => 'c_points',
                        'value' => $info[0]->c_points,
                        'class' => "form-control",
                    );

                    echo form_input($d4);
                    ?>
                </div>
            </div>

            <div class="control-group">
                <?php echo form_label('Submission Points', 's_points'); ?>
                <div class="controls">
                    <?php
                    $d3 = array(
                        'name' => 's_points',
                        'value' => $info[0]->s_points,
                        'class' => "form-control",
                    );

                    echo form_input($d3);
                    ?>
                </div>
            </div>

            <div class="control-group">
                <?php echo form_label('Documentation Points', 'd_points'); ?>
                <div class="controls">
                    <?php
                    $d2 = array(
                        'name' => 'd_points',
                        'value' => $info[0]->d_points,
                        'class' => "form-control",
                    );

                    echo form_input($d2);
                    ?>
                </div>
            </div>

            <div class="control-group">
                <?php echo form_label('Execution Points', 'e_points'); ?>
                <div class="controls">
                    <?php
                    $d1 = array(
                        'name' => 'e_points',
                        'value' => $info[0]->e_points,
                        'class' => "form-control",
                    );

                    echo form_input($d1);
                    ?>
                </div>
            </div>

            <div class="control-group">
                <?php echo form_label('Late Deduction Points', 'late'); ?>
                <div class="controls">
                    <?php
                    $d = array(
                        'name' => 'late',
                        'value' => $info[0]->late,
                        'class' => "form-control",
                    );

                    echo form_input($d);
                    ?>
                </div>
            </div>
            <br />

            <div class="form-actions">
                <?php echo anchor("class_admin/manage/assignments/$class_id/$session_id", 'Back', 'class = "btn btn-large btn-primary"'); ?>
                <?php echo form_submit('submit', 'Update points', 'class = "btn btn-large btn-primary"'); ?>

            </div>

            <?php echo form_close(); ?>

        </div>

        <div class="col-md-4">
            <?php $this->load->view('includes/student_sidebar'); ?>
            <?php $this->load->view('includes/student_class_sidebar', array('class_id' => $class_id)); ?>
        </div>
    </div>
</div>