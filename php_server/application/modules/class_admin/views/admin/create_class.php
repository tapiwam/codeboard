<div id="class_panel" class="container">

    <?php
    
    $terms = array(); $ts = array();
    $month = date('m');
    $year = date('Y');

    // Create terms for current season
    for ($y = $year; $y <= $year+1; $y++) {
        $terms["spring".$y] = "Spring ".$y;
        $terms["fall".$y] = "Fall ".$y;
    }
    
    // var_dump($terms); die();
    
    ?>
    <h2>Create Class</h2>

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

    <?php if (isset($error)) : ?>
        <div class="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <br />
            <fieldset id="error">
                <legend><h2>Sorry, something went wrong...</h2></legend>
                <pre class="error"><?php echo $error; ?></pre>
            </fieldset>
        </div>	
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <?php echo form_open('class_admin/admin_create/create_class', 'class="form-horizontal well"'); ?>

            <fieldset>
                <?php
                echo '<div class="form-group">';
                echo form_label('Class Name', 'class_name', 'class="control-label"');
                echo form_input('class_name', set_value('class_name'), 'class="form-control"');
                echo '</div>';

                echo '<div class="form-group">';
                echo form_label('Section Number (Optional)', 'section', 'class="control-label"');
                echo form_input('section', set_value('section'), 'class="form-control"');
                echo '</div>';

                echo '<div class="form-group">';
                echo form_label('Passcode to register', 'passcode', 'class="control-label"');
                echo form_input('passcode', set_value('passcode'), 'class="form-control"');
                echo '</div>';

                echo '<div class="form-group">';
                echo form_label('Class term', 'term', 'class="control-label"');
                $keys = array_keys($terms); // Pull the tird item form the list->approx current term
                echo form_dropdown('term', $terms, $keys[2], 'class="form-control"');
                echo '</div>';

                echo '<div class="form-group">';
                echo form_label('Language', 'lang', 'class="control-label"');
                $lang = array(
                    'cpp' => 'C++',
                    'c' => 'C',
                    'java' => 'Java',
                    'python' => 'Python',
                );
                echo form_dropdown('lang', $lang, 'cpp', 'class="form-control"');
                echo '</div>';
                ?>
                <div class="form-actions">
                    <?php echo form_submit('submit', 'Create Class', 'class = "btn btn-large btn-primary"'); ?>
                </div>

            </fieldset>

            <?php echo form_close(); ?>

        </div>

        <div class="col-md-4">
            <?php
            $this->load->view('includes/main_sidebar');
            ?>
        </div>
    </div>



</div>
