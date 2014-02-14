<div class="container">
    
    <h2><?php echo $sessioninfo[0]->session_name; ?></h2>
    <h4><?php echo $prog[0]->prog_name; ?></h4>
    <hr />
    
    <div class="row">
        <div class="col-md-8">
            
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
                            <pre class="error"><?php if (is_array($error)) echo $error[0];
                else echo $error; ?></pre>
                        </fieldset>
                    </div>	
                <?php endif; ?>
            
            <p><?php echo $prog[0]->description; ?></p>
            <hr />
            
            <?php echo form_open('student/submit/' . $prog[0]->class_id . '/' . $prog[0]->session_id . '/' . $prog[0]->prog_name, 'class="form-horizontal well"'); ?>

            <fieldset>
                
                <?php
                $files = array();
                foreach ($fileinfo as $file) {
                    if ($file->admin_file == 0 && $file->stream_type != "output" && $file->multi_part == 0) {
                        echo '<h4>' . $file->file_name . '</h4>';
                        echo form_textarea($file->id, set_value($file->id), 'id="' . $file->id . '"');
                    }
                }
                ?>

                <div class="form-actions">
                    
                <?php 
                if ( strtotime($sessioninfo[0]->late) > time() ){
                    echo form_submit('submit', 'Submit', 'class = "btn btn-large btn-primary"');
                }
                ?>
                </div>

            </fieldset>

            <?php echo form_close(); ?>

            <script>
                <?php foreach ($fileinfo as $file): ?>
                    <?php if ($file->admin_file == 0 && $file->stream_type != "output" && $file->multi_part == 0): ?>
                        var editor = CodeMirror.fromTextArea(document.getElementById("<?php echo $file->id; ?>"), {
                            lineNumbers: true,
                            matchBrackets: true,
                            mode: "text/x-c++src",
                            theme: "ambiance"
                        });
                    <?php endif; ?>
                <?php endforeach; ?>
            </script>
        </div>

        <div class="col-md-4">
            <?php $this->load->view('includes/student_sidebar'); ?>
        </div>

    </div>

</div>