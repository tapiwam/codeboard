<?php
$template = '
// =================================
// Header Information
// -| More header information here 
//==================================
#include <iostream>
#include <string>
using namespace std;
int main()
{
	// Copyright notice
	
	// Code
	
	// Copyright notice
	
	return 0;
} //main';
?>
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
                        <pre class="error"><?php if (is_array($error))echo $error[0]; else echo $error; ?>
                    </fieldset>
                </div>	
            <?php endif; ?>

            <p><?php echo html_entity_decode( $prog[0]->description ); ?></p>
            <hr />
            
            
            <?php echo anchor("student/assignment/".$info[0]->class_id.'/'.$info[0]->session_id.'/'.$info[0]->prog_name , "Back", 'class="btn btn-primary"') ?>

        </div>

        <div class="col-md-4">
            <?php $this->load->view('includes/student_sidebar'); ?>
            <?php $this->load->view('includes/student_class_sidebar', array('class_id'=>$class_id)); ?>
        </div>

    </div>

    
<script>
    <?php foreach ($fileinfo as $key=>$file): ?>
        <?php if ($file->admin_file == 0 && $file->stream_type != "output" && $file->multi_part == 0): ?>
            var editor = CodeMirror.fromTextArea(document.getElementById("<?php echo $file->file_name; ?>"), {
                lineNumbers: true,
                matchBrackets: true,
                mode: "text/x-c++src",
                theme: "ambiance"
            }).setSize(700, 500);;
        <?php endif; ?>
    <?php endforeach; ?>

    jQuery(document).ready(function($) {
        $('#tabs').tabs();
    });

</script>
    
</div>