<div class="container"> <?php $page = 1; ?>

    <?php if (isset($info)) : ?>	
        <?php $this->load->view('tc/bread_crumbs', array('info' => $info, 'page' => $page)); ?>
    <?php endif; ?>

    <h2>Create an Assignment</h2>

    <div class="row">
        <div class="col-md-8 well">

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

            <?php
            if (isset($class_id)) {
                echo form_open('class_admin/tc/basicinfo/' . $class_id . '/' . $session_id, 'role="form"');
            } else {
                echo form_open('class_admin/tc/basicinfo/' . $this->uri->segment(3) . '/' . $this->uri->segment(4), 'role="form" class="well"');
            }

// Basic Information on file structure 
            echo '<fieldset>';

            echo '<h4>Program Information</h4><hr />';

            echo '<div class="form-group">';
            echo form_label('Assignment Name ( e.g. hello_world )', 'prog_name', 'class="control-label"');
            echo '<div class="controls">';
            if (isset($info[0]))
                $a = $info[0]->prog_name;
            else
                $a = set_value('prog_name');
            echo form_input('prog_name', $a, 'class="form-control"');
            echo '</div></div>';

            echo '<div class="form-group">';
            echo form_label('Asignment Type', 'type', 'class="control-label"');
            $types = array(
                'cpp' => 'cpp',
                'c' => 'c',
            );
            echo form_dropdown('type', $types, 'cpp', 'class="form-control"');
            echo '</div>';

            echo '<div class="form-group">';
            echo form_label('Number of Test Cases', 'num_tc', 'class="control-label"');
            echo '<div class="controls">';
            if (isset($info[0]))
                $c = $info[0]->num_tc;
            else
                $c = set_value('num_tc');
            echo form_input('num_tc', $c, 'class="form-control"');
            echo '</div></div>';

            /*             * *********************************************************************** */
            echo '<hr /><h4>File and Data Layout</h4><hr />';
            /*             * *********************************************************************** */

            $g = TRUE;
            if (isset($info[0])) {
                if ($info[0]->input == 0) {
                    $g = FALSE;
                }
            }

            $h = TRUE;
            if (isset($info[0])) {
                if ($info[0]->output == 0) {
                    $h = FALSE;
                }
            }
            ?>

            <!--
            <div class="form-group">
            <?php echo form_label('Does the assignment require any Standard Input (cin)?', 'cin'); ?>
        
                <table>
                    <tr>
                        <td><?php echo form_checkbox("cin", TRUE, $g); ?></td>
                        <td>Input required</td>
                    </tr>
                </table>
            </div>
            
            <div class="form-group">
            <?php echo form_label('Does the assignment produce any Standard Output (cout)?', 'cout'); ?>
                <table>
                    <tr>
                        <td><?php echo form_checkbox("cout", TRUE, $h); ?></td>
                        <td>Output produced</td>
                    </tr>
                </table>
            </div>
            -->

            <input name="cin" hidden="true" value="1">
            <input name="cout" hidden="true" value="1">

            <?php
            echo '<div class="form-group">';
            echo form_label('Number of Source Files (e.g. .cpp, .c and .h Files)', 'num_source', 'class="control-label"');

            if (isset($info[0]))
                $b = $info[0]->num_source;
            else
                $b = set_value('num_source');
            echo form_input('num_source', $b, 'class="form-control"');
            echo '</div>';

            echo '<div class="form-group">';
            echo form_label('Number of Input Files (excluding standard input)', 'num_input', 'class="control-label"');
            if (isset($info[0]))
                $b = $info[0]->num_input;
            else
                $b = set_value('num_input');
            echo form_input('num_input', $b, 'class="form-control"');
            echo '</div>';

            echo '<div class="form-group">';
            echo form_label('Number of Output Files (excluding standard output)', 'num_output', 'class="control-label"');
            if (isset($info[0]))
                $b = $info[0]->num_output;
            else
                $b = set_value('num_output');
            echo form_input('num_output', $b, 'class="form-control"');
            echo '</div>';

            /*             * *********************************************************************** */
            echo '<hr /><h4>Point Structure</h4><hr />';
            /*             * *********************************************************************** */

            echo '<div class="form-group">';
            echo form_label('Points for Submission', 's_points', 'class="control-label"');
            if (isset($info[0]))
                $d = $info[0]->s_points;
            else
                $d = set_value('s_points');
            echo form_input('s_points', $d, 'class="form-control"');
            echo '</div>';

            echo '<div class="form-group">';
            echo form_label('Points for Compiling', 'c_points', 'class="control-label"');
            if (isset($info[0]))
                $f = $info[0]->c_points;
            else
                $f = set_value('c_points');
            echo form_input('c_points', $f, 'class="form-control"');
            echo '</div>';

            echo '<div class="form-group">';
            echo form_label('Points for Documentation', 'd_points', 'class="control-label"');
            if (isset($info[0]))
                $e = $info[0]->d_points;
            else
                $e = set_value('d_points');
            echo form_input('d_points', $e, 'class="form-control"');
            echo '</div>';

            echo '<div class="form-group">';
            echo form_label('Points for Execution', 'e_points', 'class="control-label"');
            if (isset($info[0]))
                $f = $info[0]->e_points;
            else
                $f = set_value('e_points');
            echo form_input('e_points', $f, 'class="form-control"');
            echo '</div>';

            echo '<div class="form-group">';
            echo form_label('Late Submission Deduction', 'late', 'class="control-label"');
            if (isset($info[0]))
                $f = $info[0]->late;
            else
                $f = set_value('late');
            echo form_input('late', $f, 'class="form-control"');
            echo '</div>';
            ?>

            <div class="form-actions">
                <?php echo form_submit('submit', 'Next step...', 'class = "btn btn-large btn-primary"'); ?>

            </div>

            </fieldset>

            <?php echo form_close(); ?>

        </div>
    </div>
</div>
