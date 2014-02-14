<div class="container">

    <h2>Create a Quiz</h2> 

    <h4>
        <em><strong>
                Class: <?php echo $classinfo[0]->class_name; ?><br />
                Lab session: <?php echo $sessioninfo[0]->session_name; ?>
            </strong></em>
    </h4>
    <hr />
    
    <div class="row-fluid">
        <div class="span8">
            <?php echo form_open("quiz/admin/make/$class_id/$session_id", 'class="form-horizontal well"'); ?>

            <fieldset>
                <?php if (validation_errors() != false) : ?>
                    <div class="alert">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <br />
                        <fieldset id="error">
                            <legend><h2>Sorry, something went wrong...</h2></legend>
                            <?php echo validation_errors('<p class="error">'); ?>
                        </fieldset>
                    </div>	
                <?php endif; ?>

                <?php
                echo form_hidden('class_id', $class_id);
                echo form_hidden('session_id', $session_id);
                ?>

                <div class="control-group">
                    <label class="control-label" for="title">Quiz title</label>
                    <div class="controls">
                        <?php echo form_input('title', set_value('title'), 'class="input-xlarge"'); ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="type">Quiz type</label>
                    <div class="controls">
                        <?php echo form_radio('type', 'multiple', true). " Multiple Choice"; ?><br />
                        <?php echo form_radio('type', 'program'). " Programming"; ?><br />
                        <?php echo form_radio('type', 'hybrid'). " Hybrid"; ?>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="description">Description</label>
                    <div class="controls">
                        <?php 
                        $data = array(
                            'name'  => 'description',
                            'id'    => 'description',
                            'value' => set_value('description'),
                            'rows'  => '3',
                            'cols'  => '50'
                        );
                        echo form_textarea($data); ?>
                    </div>
                </div>


                <div class="form-actions">
                    <?php echo form_submit('submit', 'Create Quiz', 'class = "btn btn-large btn-primary"'); ?>

                </div>
            </fieldset>
            <?php echo form_close(); ?>
        </div>

        <div class="span4">
            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>
        </div>
    </div>
</div>