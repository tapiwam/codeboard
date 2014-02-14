<?php
$template = '
// ---------------------------------
// File name:   
// Due date:        mm/dd/yy
// Purpose:   
// Author:          yourUnixLogin Firstname Lastname
// ---------------------------------
#include <iostream>
#include <string>
using namespace std;
int main()
{
	// Copyright notice
        cout << "(c)2013, yourUnixLogin Firstname Lastname" << endl;
	
	// Code
	
	// Copyright notice
	cout << "(c)2013, yourUnixLogin Firstname Lastname" << endl;
	
	return 0;
} //main';
?>
<div class="container">

    <h2><?php echo $sessioninfo[0]->session_name; ?></h2>
    <h4><?php echo $prog[0]->prog_name; ?></h4>
    <hr />

    <?php
    $sfiles = array();
    if (isset($student)) {
        foreach ($student as $item) {
            $sfiles[] = $item->file_name;
        }
    }
    ?>

    <div class="row">
        <div class="col-md-8">

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
                        <p><?php echo $error; ?></p>
                    </fieldset>
                </div>	
            <?php endif; ?>

            <p><?php echo $prog[0]->description; ?></p>
            <hr />

            <div id="content">
                <fieldset>
                        <?php echo form_open('student/submit/' . $prog[0]->class_id . '/' . $prog[0]->session_id . '/' . $prog[0]->prog_name, 'class="form-horizontal"'); ?>

                    <ul  id="tabs" class="nav nav-tabs" data-tabs="tabs">
                            <?php foreach ($fileinfo as $key => $file): ?>
                                <?php if ($file->admin_file == 0 && $file->stream_type != "output" && $file->multi_part == 0): ?>
                                <li <?php
                            if ($key == 0) {
                                echo 'class="active"';
                            }
                            ?>><a href="#<?php echo $file->file_name; ?>" data-toggle="tab"><?php echo $file->file_name; ?></a></li>                           
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>   

                    <div id="my-tab-content" class="tab-content">
                            <?php foreach ($fileinfo as $file): ?>
                                <?php if ($file->admin_file == 0 && $file->stream_type != "output" && $file->multi_part == 0): ?>

                                <div class="tab-pane active" id="code">
                                    <h2><?php echo $file->file_name; ?></h2>

                                    <?php
                                    $l = $file->id;
                                    $l1 = set_value($l);
                                    $fn = $file->file_name;

                                    if (!empty($sfiles) && in_array($fn, $sfiles)) {
                                        foreach ($student as $fx) {
                                            if ($fn == $fx->file_name) {
                                                $r = $fx->content;
                                                break;
                                            }
                                        }
                                    } else if (!empty($l1)) {
                                        $r = set_value($file->id);
                                    } else {
                                        $r = $template;
                                    }

                                    echo form_textarea($file->id, $r, "id=\"$l\"");
                                    ?>
                                </div>

                        <?php endif; ?>
                    <?php endforeach; ?>
                    </div>
                    <br />
<?php echo anchor("student/assignment/$class_id/$session_id/" . $prog[0]->prog_name, 'Back', 'class="btn btn-primary"') . "   " ?>
            <?php
            //if (strtotime($sessioninfo[0]->late) > time() )  {
                echo form_submit('submit', 'Submit', 'class = "btn btn-primary"');
            //}
            ?>
                </fieldset>
            </div>
<?php echo form_close(); ?>
        </div>

        <div class="col-md-4">
<?php $this->load->view('includes/student_sidebar'); ?>
<?php $this->load->view('includes/student_class_sidebar', array('class_id' => $class_id)); ?>
        </div>

    </div>


    <script>
<?php foreach ($fileinfo as $key => $file): ?>
    <?php if ($file->admin_file == 0 && $file->stream_type != "output" && $file->multi_part == 0): ?>
                var editor = CodeMirror.fromTextArea(document.getElementById("<?php echo $file->id; ?>"), {
                    lineNumbers: true,
                    matchBrackets: true,
                    mode: "text/x-c++src",
                    theme: "ambiance"
                }).setSize(700, 500);
    <?php endif; ?>
<?php endforeach; ?>

        jQuery(document).ready(function($) {
            $('#tabs').tabs();
        });

    </script>

</div>