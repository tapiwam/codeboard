<div class="container"><?php $page = 2; ?>

    <?php $this->load->view('tc/bread_crumbs', array('info' => $info, 'page' => $page)); ?>

    <h2>File Names and Details</h2>
    <p>
        I noticed you mentioned having some additional files. Please enter in the name and details here.
    </p>

    <div class="row">
        <div class="col-md-8">


            <?php
            // ====================================
            // source and input files

            $sfiles = array();
            $ofiles = array();
            $ifiles = array();

            if (isset($fileinfo)) {
                foreach ($fileinfo as $key => $file) {

                    if ($file->stream_type == "source")
                        $sfiles[] = $file->file_name;

                    if ($file->stream_type == "input")
                        $ifiles[] = $file->file_name;

                    if ($file->stream_type == "output")
                        $ofiles[] = $file->file_name;
                }
            }
            $sfiles = array_unique($sfiles);
            $ifiles = array_unique($ifiles);
            $ofiles = array_unique($ofiles);

            // ====================================
            // extract cout and cin files since we dont need them

            if ($key = array_search('cin', $ifiles))
                unset($ifiles[$key]); // remove cin

            if ($key = array_search('cout', $ofiles))
                unset($ofiles[$key]); // remove cout

            // ====================================
            // Open form 


            echo form_open('class_admin/tc/filedetails/' . $info[0]->class_id . '/' . $info[0]->session_id . '/' . $info[0]->prog_name, 'role="form" class="well"');
            ?>

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

            <?php if (isset($error)) : ?>
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <br />
                    <fieldset id="error">
                        <legend><h2>Sorry, something went wrong...</h2></legend>
                        <pre class="error"><?php echo $error; ?></pre>
                    </fieldset>
                </div>	
            <?php endif; ?>

            <!--------------------------------------------------------------------------------->

            <?php
            echo '<fieldset>';
            $ix = 1;

            // =========================================
            // go through source files
            // =========================================

            if ($info[0]->num_source > 0) {
                //echo '<h3>Source files</h3>';
                for ($i = 1; $i <= $info[0]->num_source; $i++) {
                    // variable for previous permissions   
                    $a1 = FALSE;
                    $a2 = FALSE;
                    $s1 = FALSE;
                    $s2 = FALSE;
                    $s3 = FALSE;
                    $m1 = FALSE;
                    $m2 = FALSE;

                    // See if the file name already exists
                    if (isset($sfiles[$i - 1])) {
                        $fname = $sfiles[$i - 1];
                        foreach ($fileinfo as $key => $file) {
                            if ($file->file_name == $fname) {
                                $r = $file->file_name;

                                // admin properties
                                if ($file->admin_file == 1) {
                                    $a2 = TRUE;
                                } else {
                                    $a1 = TRUE;
                                } // REPLACE PREVIOUS 
                                // strem type properties
                                if ($file->stream_type == "input") {
                                    $s2 = TRUE;
                                } else if ($file->stream_type == "output") {
                                    $s3 = TRUE;
                                } else if ($file->stream_type == "source") {
                                    $s1 = TRUE;
                                }

                                // multi properties
                                if ($file->multi_part == 1) {
                                    $m2 = TRUE;
                                } else {
                                    $m1 = TRUE;
                                } // REPLACE PREVIOUS 

                                break;
                            }
                        }
                        if (!isset($r))
                            $r = set_value('add' . $i);
                    } else {
                        $r = set_value('add' . $i);
                    }

                    // Print file info to screen
                    echo '<div class="form-group">';
                    echo "<legend>Source File $i</legend>";
                    echo form_label('File Name', 'add' . $ix);
                    echo form_input('add' . $ix, $r, 'class="form-control"');
                    echo '</div>';


                    echo form_hidden('stream_type' . $ix, 'source');
                    echo form_hidden('multi' . $ix, '0');
                    ?>

                    <div class="form-group">
                        <?php echo form_label('Will this source file be submitted by the student or provided by the Administrator?', 'admin' . $ix); ?>
                        <table>
                            <tr>
                                <td><?php echo form_radio('admin' . $ix, '0', $a1); ?></td>
                                <td>Student Submits file</td>
                            </tr>
                            <tr>
                                <td><?php echo form_radio('admin' . $ix, '1', $a2); ?></td>
                                <td>Administrator provided file</td>
                            </tr>
                        </table>
                    </div>
                    <?php
                    $ix++;
                }
            }

            // =============================================
            // go through input files
            // =============================================
            if ($info[0]->num_input > 0) {
                //echo '<h3>Input files</h3>';
                for ($i = 1; $i <= $info[0]->num_input; $i++) {
                    // set the previous permissions
                    $a1 = FALSE;
                    $a2 = FALSE;
                    $s1 = FALSE;
                    $s2 = FALSE;
                    $s3 = FALSE;
                    $m1 = FALSE;
                    $m2 = FALSE;

                    if (isset($ifiles[$i - 1])) {
                        $fname = $files[$i - 1];
                        foreach ($fileinfo as $key => $file) {
                            if ($file->file_name == $fname) {
                                $r = $file->file_name;

                                // admin properties
                                if ($file->admin_file == 1) {
                                    $a2 = TRUE;
                                } else {
                                    $a1 = TRUE;
                                } // REPLACE PREVIOUS 
                                // strem type properties
                                if ($file->stream_type == "input") {
                                    $s2 = TRUE;
                                }

                                // multi properties
                                if ($file->multi_part == 1) {
                                    $m2 = TRUE;
                                } else {
                                    $m1 = TRUE;
                                } // REPLACE PREVIOUS 

                                break;
                            }
                        }
                        if (!isset($r))
                            $r = set_value('add' . $i);
                    } else {
                        $r = set_value('add' . $i);
                    }

                    echo '<div class="form-group">';
                    echo "<legend>Input File $i</legend>";
                    echo form_label('File Name', 'add' . $ix);
                    echo form_input('add' . $ix, $r, 'class="form-control"');
                    echo '</div>';

                    // indicate that it is an input file
                    echo form_hidden('stream_type' . $ix, 'input');

                    // additional info
                    ?>
                    <div class="form-group">
                        <?php echo form_label('Will this input file be submitted by the student or provided by the Administrator?', 'admin' . $ix); ?>
                        <div class="controls">
                            <table>
                                <tr>
                                    <td><?php echo form_radio('admin' . $ix, '0', $a1); ?></td>
                                    <td>Student Submits file</td>
                                </tr>
                                <tr>
                                    <td><?php echo form_radio('admin' . $ix, '1', $a2); ?></td>
                                    <td>Administrator provided file</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="form-group">
                    <?php echo form_label('Will this file need fresh/different content for every test case?', 'multi' . $ix); ?>
                        <div class="controls">
                            <table>
                                <tr>
                                    <td><?php echo form_radio('multi' . $ix, '0', $m1); ?></td>
                                    <td>No, I will only need this file once.</td>
                                </tr>
                                <tr>
                                    <td><?php echo form_radio('multi' . $ix, '1', $m2); ?></td>
                                    <td>Yes, I will need the file for every test case.</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php
                    $ix++;
                }
            }

            if ($info[0]->num_output > 0) {
                for ($i = 1; $i <= $info[0]->num_output; $i++) {

                    if (!empty($ofiles)) {
                        $r1 = $ofiles[$i - 1];
                    } else {
                        $r1 = set_value('add' . $ix);
                    }
                    echo '<div class="form-group">';
                    echo "<legend>Output File $i</legend>";
                    echo form_label('File Name', 'add' . $ix);
                    echo form_input('add' . $ix, $r1, 'class="form-control"');
                    echo '</div>';

                    // hidden files
                    echo form_hidden('admin' . $ix, '0');
                    echo form_hidden('multi' . $ix, '1');
                    echo form_hidden('stream_type' . $ix, 'output');
                    $ix++;
                }
            }
            ?>

            <div class="form-actions">
                <?php echo anchor("class_admin/tc/stage1/" . $info[0]->class_id . '/' . $info[0]->session_id . '/' . $info[0]->prog_name, 'Back', 'class = "btn btn-large btn-primary"') . "     "; ?>
                <?php echo form_submit('submit', 'Next step...', 'class = "btn btn-large btn-primary"'); ?>

            </div>

            </fieldset>

        <?php echo form_close(); ?>
        </div>;
    </div>
    
    
    <div class="row">
        <div class="col-md-8">
            <p>Note: For single part files these will be included along with the main file since only one version of the file is required.</p>
            <p>For Multi-part, since these are all connected to individual questions, these question will be attached to each test case</p>
        </div>
    </div>

    

</div>