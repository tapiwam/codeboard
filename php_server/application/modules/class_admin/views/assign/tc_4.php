<div class="container"><?php $page = 4; ?>

    <?php $this->load->view('tc/bread_crumbs', array('info' => $info, 'page' => $page)); ?>

    <?php echo form_open('class_admin/tc/filecontent_multi/' . $info[0]->class_id . '/' . $info[0]->session_id . '/' . $info[0]->prog_name, 'class="form-horizontal well"'); ?>

    <fieldset>

        <h2>Content for Individual Test Cases</h2>

        <?php if (validation_errors() != false) : ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
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


        <?php
// Multi-part segment
// ===============================================================================================

        $num_tc = $info[0]->num_tc;
// echo '<pre>'; print_r($fileinfo); echo '</pre><hr />'; print_r($info[0]); echo '<hr />';

        if ($num_tc > 0) {

            for ($i = 1; $i <= $num_tc; $i++) {
                echo "<h4>Test Case $i</h4>";

                if ($info[0]->input == 1) { // Check to see if cin is needed and provide space for that
                    echo '<div class="control-group">';
                    echo form_label('CIN for ' . $info[0]->prog_name);
                    echo '<div class="controls">';

                    // check if cin was previously set
                    if (isset($fileinfo)) {
                        foreach ($fileinfo as $key => $file) {
                            if ($file->file_name == 'cin' && $file->meta == $i && $file->file_content != "")
                                $r_check = $key;
                        }
                    }
                    if (isset($r_check))
                        $r_val = $fileinfo[$r_check]->file_content;
                    else
                        $r_val = set_value("cin_$i");
                    echo form_textarea("cin_$i", $r_val, 'class="input-xlarge" rows="8" col="14" id="cin_' . $i . '"');
                    echo '</div></div>';
                }

                if (isset($fileinfo)) {
                    foreach ($fileinfo as $file) {
                        
                        if ($file->multi_part == 1 && $file->stream_type != 'source' && $file->file_name != 'cin' && $file->meta != $i && $file->stream_type != "output") {
                            echo '<div class="control-group">';
                            echo form_label('File: ' . $file->file_name, $file->id . '_' . $i);
                            echo '<div class="controls">';
                            if ($file->file_content != "")
                                $r_val = $file->file_content;
                            else
                                $r_val = set_value($file->id . '_' . $i);
                            echo form_textarea($file->id . '_' . $i, $r_val, 'class="input-xlarge" rows="8" col="14" id="' . $file->id . '_' . $i . '"');
                            echo '</div></div>';
                        }
                    }
                }
                echo '<hr />';
            }
        } else {
            echo "<h4>You specified that you did NOT need any test cases. If you do please go back to stage 1 and re-specify this or please continue...</h4>";
        }
        ?>


        <div class="form-actions">
            <?php echo anchor("class_admin/tc/stage3/" . $info[0]->class_id . '/' . $info[0]->session_id . '/' . $info[0]->prog_name, 'Back', 'class = "btn btn-large btn-primary"'); ?>
            <?php echo form_submit('submit', 'Next step...', 'class = "btn btn-large btn-primary"'); ?>

        </div>

    </fieldset>

    <?php echo form_close(); ?>

</div>


<script>
<?php for ($i = 1; $i <= $num_tc; $i++): ?>
    <?php if ($info[0]->input == 1): ?>
            var editor = CodeMirror.fromTextArea(document.getElementById("<?php echo "cin_$i"; ?>"), {
            lineNumbers: true,
                    matchBrackets: true,
        <?php // if($file->stream_type=="source"){ echo 'mode: "text/x-c++src",'; }  ?>
            theme: "ambiance"
            });
    <?php endif; ?>

    <?php if (isset($fileinfo)) : ?>

        <?php foreach ($fileinfo as $file): ?>
            <?php if ($file->multi_part == 1 && $file->stream_type != 'source' && $file->file_name != 'cin' && $file->meta != $i && $file->stream_type != "output"): ?>
                    var editor = CodeMirror.fromTextArea(document.getElementById("<?php echo $file->id . '_' . $i; ?>"), {
                    lineNumbers: true,
                            matchBrackets: true,
                <?php if ($file->stream_type == "source") {
                    echo 'mode: "text/x-c++src",';
                } ?>
                    theme: "ambiance"
                    });
            <?php endif; ?>
        <?php endforeach; ?>

    <?php endif; ?>

<?php endfor; ?>
</script>


