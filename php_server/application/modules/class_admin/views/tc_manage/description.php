<div class="container">

    <h2>Assignment Description: <?php echo $prog_name . " ({$sessioninfo[0]->session_name})"; ?></h2>
    
    <?php echo form_open('class_admin/tc_manage/update_description/' . $info[0]->class_id . '/' . $info[0]->session_id . '/' . $info[0]->prog_name, 'class="form-horizontal well"'); ?>

    <fieldset>
        <p>
            The program description is what is displayed to the student describing what they need to do for the current assignment. 
        </p>

        <p>
            Please fill out the program's description and hints here. 
        </p>
        <hr />

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
            $r_val = $info[0]->description;
            echo form_textarea('description', $r_val, 'class="input-xlarge" rows="8" col="14" id="description"');
        ?>
        
        <br />
        <div class="form-actions">
            <?php echo anchor("class_admin/manage/assignments/$class_id/$session_id", 'Back', 'class = "btn btn-large btn-primary"'); ?>
            <?php echo form_submit('submit', 'Update description', 'class = "btn btn-large btn-primary"'); ?>

        </div>

    </fieldset>

    <?php echo form_close(); ?>

</div>

<script type="text/javascript" src="<?php echo base_url(); ?>components/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>components/js/tiny.js"></script>
