

<?php // print_r($classinfo) ; echo '<br />' ?>

<div class="container">

    <h2>Create a Lab</h2> 

    <div class="row">
        <div class="col-md-8">
            <?php echo form_open('class_admin/admin_create/create_session_api', 'class="form-horizontal well"'); ?>

            <fieldset>
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
                echo form_hidden('class_id', $classinfo[0]->id);
                //echo form_hidden('term', $classinfo[0]->term);
                ?>

                <div class="form-group">
                    <label class="control-label" for="session_name">Lab Name</label>
                    <?php echo form_input('session_name', set_value('session_name'), 'class="form-control" type="text" '); ?>
                </div>

                <div class="form-group">
                        <p style="color: #9d1e15">Please use the calendar icons to the right to set the times and dates for the lab. The time will also be displayed in <strong>24Hr notation</strong> so please be caution of this!</p>
                </div>

                <div class="form-group">
                    <label class="control-label" for="start">Start</label>
                    <div class="input-group date" class="dp">
                        <?php echo form_input('start', "", 'id="dpd1" class="form-control" type="text" '); ?>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label" for="session_name">On-time</label>
                    <div class="input-group date" class="dp">
                        <?php echo form_input('end', "", 'id="dpd2" class="form-control" type="text" '); ?>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label" for="session_name">Late</label>
                    <div class="input-group date" class="dp">
                        <?php echo form_input('late', "", 'id="dpd3" class="form-control" type="text" '); ?>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>


                <div class="form-actions">
                    <?php echo form_submit('submit', 'Create Session', 'class = "btn btn-large btn-primary"'); ?>

                </div>
            </fieldset>
            <?php echo form_close(); ?>
        </div>

        <div class="col-md-4">
            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>
        </div>
    </div>

    <script type="text/javascript">
            $(function () {
                $('#dpd1').datetimepicker();
            });
        </script>

</div>