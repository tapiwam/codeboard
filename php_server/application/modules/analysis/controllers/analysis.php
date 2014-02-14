<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Analysis extends CLASS_Controller {

    function __construct() {
        parent::__construct();
        $this->user_model->is_logged_in();
        $this->user_model->is_student();
        // $this->load->model('class_admin/admin_model');
        // $this->load->model('student_model');
        $this->load->model('a_model');
        $this->load->model('class_admin/tc_model');
        $this->load->model('log_model');
        // $this->load->model('grader_m');
    }

    // list all student's classes
    

    // the window availbe so the student can take the assignment
    function prog_grades_time($sid, $class_id, $session_id, $prog_name) {
        $data = new stdClass();
        $result = $this->a_model->grades_assign_ind($sid, $class_id, $session_id, $prog_name);
        $series = array();
        
        $item1 = new stdClass();
        $item1->argumentField = "time";
        $item1->valueField = "avg";
        $item1->name = "Average";
        $item1->type = "line";
        $item1->color = "cornflowerblue";
        $series[] = $item1; 
        
        $constantLines = (object) array(
            'label' => (object) array(
                'text'=> 'Class Average'
            ),
            'width' => 2,
            'value'=> $this->a_model->prog_avg( $class_id, $session_id, $prog_name ),
            'color' => '#00ced1',
            'dashStyle' => 'dash'
        );
        
        $title = "Final scores over time";
        
        $legend = new stdClass();
        $legend->verticalAlignment = 'bottom';
        $legend->horizontalAlignment = 'center';
        
        $axis = (object) array(
            'argumentType' => 'datetime',
        );
        
        if($result != false){
            $data->success = 1;
            
            $r = new stdClass();
            $r->dataSource = $result;
            $r->series = $series;
            $r->title = $title;
            $r->legend = $legend;
            $r->tooltip = (object) array("enabled" => true);
            $r->commonSeriesSettings = (object) array( 'visible'=>true, 'right'=>false  );
            $r->argumentAxis = (object) array( 'argumentType' => 'datetime', 'title'=>'Date/Time');
            $r->valueAxis = (object) array('title'=>'Score  %', 'constantLines' => $constantLines);
            
            $data->result = $r;
        } else {
            $data->error = 1;
        }
        
        echo json_encode($data);
    }
    
    function prog_grades_events($sid, $class_id, $session_id, $prog_name) {
        $data = new stdClass();
        $result = $this->a_model->grades_assign_ind($sid, $class_id, $session_id, $prog_name);
        $series = array();
        
        $item1 = new stdClass();
        $item1->argumentField = "submits";
        $item1->valueField = "avg";
        $item1->name = "Average";
        $item1->type = "line";
        $item1->color = "cornflowerblue";
        $series[] = $item1;
        
        $constantLines = (object) array(
            'label' => (object) array(
                'text'=> 'Class Average'
            ),
            'width' => 2,
            'value'=> $this->a_model->prog_avg( $class_id, $session_id, $prog_name ),
            'color' => '#00ced1',
            'dashStyle' => 'dash'
        );
        
        $title = "Final scores for each submit";
        
        $legend = new stdClass();
        $legend->verticalAlignment = 'bottom';
        $legend->horizontalAlignment = 'center';
        
        if($result != false){
            $data->success = 1;
            
            $r = new stdClass();
            $r->dataSource = $result;
            $r->series = $series;
            $r->title = $title;
            $r->legend = $legend;
            $r->tooltip = (object) array("enabled" => true);
            $r->commonSeriesSettings = (object) array( 'visible'=>true, 'right'=>false  );
            $r->argumentAxis = (object) array( 'title'=>'Submit #');
            $r->valueAxis = (object) array('valueType' => 'numeric', 'title'=>'Score  %', 'constantLines' => $constantLines);
            
            $data->result = $r;
        } else {
            $data->error = 1;
        }
        
        echo json_encode($data);
    }
    
    // LIne graph showing grade breakdown over time
    function all_prog_grades_time($sid, $class_id, $session_id, $prog_name) {
        $data = new stdClass();
        $result = $this->a_model->grades_assign_ind($sid, $class_id, $session_id, $prog_name);
        $series = array();
        
        $commonSeriesSettings = array(
            'argumentField' => 'time',
            'type' => "line",
            'hoverMode' => "allArgumentPoints",
            'selectionMode' => "allArgumentPoints",
            'right'=>false,
            'label' => (object) array (
                'visible' => false,
                'format' => "fixedPoint",
                'precision' => 0
            )
        );
        
        $item2 = new stdClass();
        $item2->valueField = "score";
        $item2->name = "Final Score";
        $series[] = $item2;
    
        $item1 = new stdClass();
        $item1->valueField = "s_points";
        $item1->name = "Submission Points";
        $series[] = $item1;
        
        $item1 = new stdClass();
        $item1->valueField = "c_points";
        $item1->name = "Compile Points";
        $series[] = $item1;
        
        $item2 = new stdClass();
        $item2->valueField = "e_points";
        $item2->name = "Execution Points";
        $series[] = $item2; 
        
        $constantLines = (object) array(
            'label' => (object) array(
                'text'=> 'Class Average'
            ),
            'width' => 2,
            'value'=> $this->a_model->prog_avg( $class_id, $session_id, $prog_name ),
            'color' => '#00ced1',
            'dashStyle' => 'dash'
        );
        
        
        $title = "Progression of scores over time";
        
        $legend = new stdClass();
        $legend->verticalAlignment = 'bottom';
        $legend->horizontalAlignment = 'center';
        
        if($result != false){
            $data->success = 1;
            
            $r = new stdClass();
            $r->dataSource = $result;
            $r->series = $series;
            $r->title = $title;
            $r->legend = $legend;
            $r->tooltip = (object) array("enabled" => true);
            $r->commonSeriesSettings = $commonSeriesSettings;
            $r->argumentAxis = (object) array( 'argumentType' => 'datetime', 'title'=>'Submit #');
            $r->valueAxis = (object) array('title'=>'Score  %', 'constantLines' => $constantLines);
            
            $data->result = $r;
        } else {
            $data->error = 1;
        }
        
        echo json_encode($data);
    }
    
    // All grades over submits
    function all_prog_grades_submits_bar($sid, $class_id, $session_id, $prog_name) {
        $data = new stdClass();
        $result = $this->a_model->grades_assign_ind($sid, $class_id, $session_id, $prog_name);
        $series = array();
        
        $commonSeriesSettings = array(
            'argumentField' => 'submits',
            'type' => "bar",
            'hoverMode' => "allArgumentPoints",
            'selectionMode' => "allArgumentPoints",
            // 'visible'=>true, 
            'right'=>false,
            'label' => (object) array (
                'visible' => false,
                'format' => "fixedPoint",
                'precision' => 0
            )
        );
        
        $item2 = new stdClass();
        $item2->valueField = "score";
        $item2->name = "Score";
        $series[] = $item2;
    
        $item1 = new stdClass();
        $item1->valueField = "s_points";
        $item1->name = "Submission Points";
        $series[] = $item1;
        
        $item1 = new stdClass();
        $item1->valueField = "c_points";
        $item1->name = "Compile Points";
        $series[] = $item1;
        
        $item2 = new stdClass();
        $item2->valueField = "e_points";
        $item2->name = "Execution Points";
        $series[] = $item2; 
        
        $constantLines = (object) array(
            'label' => (object) array(
                'text'=> 'Class Average'
            ),
            'width' => 2,
            'value'=> $this->a_model->prog_avg( $class_id, $session_id, $prog_name ),
            'color' => '#00ced1',
            'dashStyle' => 'dash'
        );
        
        
        $title = "Progression of scores for each submit";
        
        $legend = new stdClass();
        $legend->verticalAlignment = 'bottom';
        $legend->horizontalAlignment = 'center';
        
        if($result != false){
            $data->success = 1;
            
            $r = new stdClass();
            $r->dataSource = $result;
            $r->series = $series;
            $r->title = $title;
            $r->legend = $legend;
            $r->tooltip = (object) array("enabled" => true);
            $r->commonSeriesSettings = $commonSeriesSettings;
            $r->argumentAxis = (object) array( 'title'=>'Submit #');
            $r->valueAxis = (object) array('valueType' => 'numeric', 'title'=>'Scores', 'constantLines' => $constantLines);
            
            $data->result = $r;
        } else {
            $data->error = 1;
        }
        
        echo json_encode($data);
    }
    
    // Display the code progression over time
    function prog_lines_time($sid, $class_id, $session_id, $prog_name) {
        $data = new stdClass();
        $result = $this->a_model->lines_assign_ind($sid, $class_id, $session_id, $prog_name);
        $series = array();
        
        $item1 = new stdClass();
        $item1->argumentField = "time";
        $item1->valueField = "lines";
        $item1->name = "lines";
        $item1->type = "line";
        $item1->color = "cornflowerblue";
        
        $series[] = $item1; 
        
        $title = "Code Growth over Time";
        
        $legend = new stdClass();
        $legend->verticalAlignment = 'bottom';
        $legend->horizontalAlignment = 'center';
        
        if($result != false){
            $data->success = 1;
            
            $r = new stdClass();
            $r->dataSource = $result;
            $r->series = $series;
            $r->title = $title;
            $r->legend = $legend;
            $r->argumentAxis = (object) array( 'argumentType' => 'datetime', 'title'=>'Date/Time');
            $r->valueAxis = (object) array('title'=>'# of Lines');
            // 'inverted' => true, 
            
            $data->result = $r;
        } else {
            $data->error = 1;
        }
        
        echo json_encode($data);
    }
    
    // Display the code progression over each submit
    function prog_lines_submits($sid, $class_id, $session_id, $prog_name) {
        $data = new stdClass();
        $result = $this->a_model->lines_assign_ind($sid, $class_id, $session_id, $prog_name);
        $series = array();
        
        $item1 = new stdClass();
        $item1->argumentField = "submits";
        $item1->valueField = "lines";
        $item1->name = "lines";
        $item1->type = "line";
        $item1->color = "cornflowerblue";
        
        $series[] = $item1; 
        
        $title = "Code Growth over Time";
        
        $legend = new stdClass();
        $legend->verticalAlignment = 'bottom';
        $legend->horizontalAlignment = 'center';
        
        if($result != false){
            $data->success = 1;
            
            $r = new stdClass();
            $r->dataSource = $result;
            $r->series = $series;
            $r->title = $title;
            $r->legend = $legend;
            $r->argumentAxis = (object) array( 'title'=>'Submits');
            $r->valueAxis = (object) array('valueType' => 'numeric','title'=>'# of Lines');
            // 'inverted' => true, 
            
            $data->result = $r;
        } else {
            $data->error = 1;
        }
        
        echo json_encode($data);
    }
    
    function test($class_id, $session_id, $prog_name){
        // result = $this->a_model->prog_avg( $class_id, $session_id, $prog_name);
        // echo $this->a_model->prog_sd( $class_id, $session_id, $prog_name);
    }
    
}
