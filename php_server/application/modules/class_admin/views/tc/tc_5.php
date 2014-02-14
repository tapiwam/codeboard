<div class="container"><?php $page = 5; ?>

    <?php $this->load->view('tc/bread_crumbs', array('info' => $info, 'page' => $page)); ?>

    <h2>Content for Individual Test Cases</h2>	

    <?php echo form_open('class_admin/tc/output/' . $info[0]->class_id . '/' . $info[0]->session_id . '/' . $info[0]->prog_name, 'class="form-horizontal well"'); ?>

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

        // This part will display any output after the testcases have been compiled and run. 
        $num_tc = $info[0]->num_tc;

        for ($i = 1; $i <= $num_tc; $i++) {
            echo "<legend>Test Case $i</legend>";

            if ($info[0]->output == 1) { // Check to see if cin is needed and provide space for that
                echo '<div class="form-group">';
                echo form_label('Standard Output for ' . $info[0]->prog_name);
                echo '<div class="controls">';

                // check if cin was previously set
                if (isset($fileinfo)) {
                    foreach ($fileinfo as $key => $file) {
                        if ($file->file_name == 'cout' && $file->meta == $i && $file->file_content != "")
                            $r_check = $key;
                    }
                }
                if (isset($r_check))
                    $r_val = $fileinfo[$r_check]->file_content;
                else
                    $r_val = set_value("output_$i");

                echo form_textarea("output_$i", $r_val, 'class="input-xlarge" rows="8" col="14" id="' . "output_$i" . '"');
                echo '</div></div>';
                echo '
		<script>var editor = CodeMirror.fromTextArea(document.getElementById("' . "output_$i" . '"), {
				lineNumbers: true, matchBrackets: true, theme: "ambiance",
				mode: "text/x-c++src" 
				}); 
		</script>';
            }

            if (isset($fileinfo)) {
                foreach ($fileinfo as $file) {
                    if ($file->multi_part == 1 && $file->stream_type != 'source' && $file->file_name != 'cout' && $file->meta == $i && $file->stream_type == "output") {
                        echo '<div class="form-group">';
                        echo form_label('File: ' . $file->file_name, $file->id . '_' . $i);
                        echo '<div class="controls">';
                        if ($file->file_content != "")
                            $r_val = $file->file_content;
                        else
                            $r_val = set_value($file->id . '_' . $i);
                        echo form_textarea($file->id . '_' . $i, $r_val, 'class="input-xlarge" rows="8" col="14" id="' . $file->id . '_' . $i . '"');
                        echo '</div></div>';
                        echo '
			<script>var editor = CodeMirror.fromTextArea(document.getElementById("' . $file->id . '_' . $i . '"), {
				lineNumbers: true, matchBrackets: true, theme: "ambiance",
				mode: "text/x-c++src" 
				}); 
			</script>';
                    }
                }
            }
            echo '<br />';
        }
        ?>

        <div class="form-actions">
            <?php echo anchor("class_admin/tc/stage4/" . $info[0]->class_id . '/' . $info[0]->session_id . '/' . $info[0]->prog_name, 'Back', 'class = "btn btn-large btn-primary"'); ?>
            <?php echo form_submit('submit', 'Next step...', 'class = "btn btn-large btn-primary"'); ?>
        </div>

    </fieldset>

<?php echo form_close(); ?>

</div>

