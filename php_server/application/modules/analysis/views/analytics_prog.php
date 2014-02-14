<script src="<?php echo base_url(); ?>components/js/apps/analytics.js"></script>
<script src="<?php echo base_url(); ?>components/js/apps/chartCtrl.js"></script>

<input type="hidden" name="class_id" id="class_id" value="<?php echo $class_id; ?>">
<input type="hidden" name="session_id" id="session_id" value="<?php echo $session_id; ?>">
<input type="hidden" name="prog_name" id="prog_name" value="<?php echo $prog_name; ?>">
<input type="hidden" name="base_url" id="base_url" value="<?php echo site_url(); ?>">
<input type="hidden" name="uid" id="uid" value="<?php echo $uid; // echo $this->session->userdata('id'); ?>">
<input type="hidden" name="uid" id="uid" value="<?php echo $name //$this->session->userdata('first_name'); ?>">


<div class="container">
    <h2>Program Analytics: <?php echo $name ?></h2>
    <div class="col-md-6 well">
        <h4>Class: <?php echo $classinfo[0]->class_name; ?></h4>
        <h4>Lab: <?php echo $sessioninfo[0]->session_name; ?></h4>
        <h4>Program: <?php echo $prog[0]->prog_name; ?></h4>
    </div>
    
    <div ng-app="analyticsApp" >
        <div class="col-md-8" ng-controller="chartCtrl">

            <div class="btn-group">
                <button type="button" class="btn btn-primary" ng-click="all_grades()" btn-radio="'all_grades'">All Grades</button>
                <button type="button" class="btn btn-primary" ng-click="grades()" btn-radio="'grades'">Grades</button>
                <button type="button" class="btn btn-primary" ng-click="lines()" btn-radio="'lines'">Code Growth</button>
            </div>


            <div id="chartContainer1" 
                 style="max-width: 900px; height: 400px;">
            </div>

            <div id="chartContainer2" 
                 style="max-width: 900px; height: 400px;">
            </div>

            <?php
            if ($this->session->userdata('level') == 0) {
                echo anchor("student/prog/" . $prog[0]->class_id . '/' . $prog[0]->session_id . '/' . $prog[0]->prog_name, "Program", 'class="btn btn-primary btn-large"') . "   ";
                echo anchor("student/sessions/" . $prog[0]->class_id . '/' . $prog[0]->session_id, "Lab", 'class="btn btn-primary btn-large"') . "   ";
                echo anchor("student/classes/".$prog[0]->class_id, "Class", 'class="btn btn-primary btn-large"'). "   ";
            } else {
                echo '<a href="javascript:history.go(-1)" class="btn btn-primary">Back</a>';
            }
            ?>

        </div>

        <div class="col-md-4">
            <?php $this->load->view('includes/student_sidebar'); ?>
            <?php $this->load->view('includes/student_class_sidebar', array('class_id' => $class_id)); ?>
        </div>
    </div>
</div>
