<div class="container">

    <h2>Manage lab dates and times</h2> 
    <hr />

    <div class="row">
        <div class="col-md-8">

            <div class="row">
                <div class="col-md-2"><h4>Lab: </h4></div>
                <div class="col-md-6"><h4><?php echo $sessioninfo[0]->session_name; ?> </h4></div>
            </div>

            <div class="row">
                <div class="col-md-2"><h4>Starts on: </h4></div>
                <div class="col-md-6"><h4><?php echo date("D F j, Y", strtotime($sessioninfo[0]->start)); ?> at <?php echo date("h:i", strtotime($sessioninfo[0]->start)); ?></h4></div>
            </div>

            <div class="row">
                <div class="col-md-2"><h4>Is late after:</h4></div>
                <div class="col-md-6"><h4><?php echo date("D F j, Y", strtotime($sessioninfo[0]->end)); ?> at <?php echo date("h:i", strtotime($sessioninfo[0]->end)); ?></h4></div>
            </div>

            <div class="row">
                <div class="col-md-2"><h4>late deadline:</h4></div>
                <div class="col-md-6"><h4><?php echo date("D F j, Y", strtotime($sessioninfo[0]->late)); ?> at <?php echo date("h:i", strtotime($sessioninfo[0]->late)); ?></h4></div>
            </div>
            <hr />

            <?php echo form_open("class_admin/manage/set_lab_time/$class_id/$session_id", 'class="form-horizontal well"'); ?>

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
                echo form_hidden('class_id', $class_id);
                //echo form_hidden('term', $classinfo[0]->term);
                ?>

                <div class="form-group">
                    <label class="control-label" for="session_name">Start</label>
                    
                        <div class="datetimepicker" class="input-append">
                            <?php echo form_input('start', $sessioninfo[0]->start, 'id="dpd1" data-format="yyyy-MM-dd hh:mm:ss" type="text" '); ?>
                            <span class="add-on">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                </i>
                            </span>

                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label" for="session_name">On-time submission</label>
                        <div class="datetimepicker" class="input-append">
                            <?php echo form_input('end', $sessioninfo[0]->end, 'id="dpd1" data-format="yyyy-MM-dd hh:mm:ss" type="text" '); ?>
                            <span class="add-on">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                </i>
                            </span>
                        </div>
                </div>

                <div class="form-group">
                    <label class="control-label" for="session_name">Late submission deadline</label>

                        <div class="datetimepicker" class="input-append">
                            <?php echo form_input('late', $sessioninfo[0]->late, 'id="dpd1" data-format="yyyy-MM-dd hh:mm:ss" type="text" '); ?>
                            <span class="add-on">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                </i>
                            </span>

                    </div>
                </div>


                <div class="form-actions">
                    <?php echo anchor("class_admin/sessions/$class_id/$session_id", "Back", 'class = "btn btn-primary"') . "  "; ?>
                    <?php echo form_submit('submit', 'Update', 'class = "btn btn-primary"'); ?>

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
        $(function() {
            $('.datetimepicker').datetimepicker({
                language: 'en',
                pick12HourFormat: false,
                pickSeconds: false
            });
        });
    </script>

</div>