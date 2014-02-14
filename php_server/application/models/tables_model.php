<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tables_model extends CLASS_Model {

    function __construct() {
        parent::__construct();
        $this->load->dbforge();
    }

    /*     * **************************************************
      function template_tbl($term, $class_name)
      {
      $fields = array(

      );

      $name = $term. '_'.$class_name.'_testcases' ;
      $this->dbforge->add_field('id');
      $this->dbforge->add_field($fields);
      $this->dbforge->create_table($name);
      }
     * ************************************************** */

    // *************************************
    // TESTCASE TABLES
    // *************************************

    function programs_tbl($term, $class_name) {
        $fields = array(
            'class_id' => array(
                'type' => 'INT',
                'constraint' => '6',
            ),
            'session_id' => array(
                'type' => 'INT',
                'constraint' => '6',
            ),
            'prog_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
            ),
            'type' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
            ),
            'num_tc' => array(
                'type' => 'INT',
                'constraint' => '3',
                'default' => 0
            ),
            // add here
            'num_source' => array(
                'type' => 'INT',
                'constraint' => '3',
                'default' => 0
            ),
            'num_input' => array(
                'type' => 'INT',
                'constraint' => '3',
                'default' => 0
            ),
            'num_output' => array(
                'type' => 'INT',
                'constraint' => '3',
                'default' => 0
            ),
            'num_addition' => array(
                'type' => 'INT',
                'constraint' => '3',
                'default' => 0
            ),
            's_points' => array(
                'type' => 'INT',
                'constraint' => '3',
                'default' => 0
            ),
            'd_points' => array(
                'type' => 'INT',
                'constraint' => '3',
                'default' => 0
            ),
            'e_points' => array(
                'type' => 'INT',
                'constraint' => '3',
                'default' => 0
            ),
            'c_points' => array(
                'type' => 'INT',
                'constraint' => '3',
                'default' => 0
            ),
            'late' => array(
                'type' => 'INT',
                'constraint' => '3',
                'default' => 0
            ),
            'input' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 1
            ),
            'output' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 1
            ),
            'stage' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 1
            ),
            'published' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 0
            ),
            'description' => array(
                'type' => 'TEXT',
            ),
            'active' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 1
            ),
            'graded' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 1
            ),
            'time TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        );

        $name = $term . '_' . $class_name . '_programs';
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($name);
    }
    
    function files_tbl($term, $class_name) {
        $fields = array(
            'prog_id' => array(
                'type' => 'INT',
                'constraint' => '6',
            ),
            'file_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
            ),
            'main' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 0,
            ),
            'file_content' => array(
                'type' => 'TEXT',
            ),
            'admin_file' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 0
            ),
            'multi_part' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 0
            ),
            'stream_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
            // 'default' => 0
            ),
            'meta' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
            ),
        );

        $name = $term . '_' . $class_name . '_files';
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($name);
    }

    function student_files_tbl($term, $class_name) {
        $fields = array(
            'user_id' => array(
                'type' => 'INT',
                'constraint' => '6',
            ),
            'prog_id' => array(
                'type' => 'INT',
                'constraint' => '6',
            ),
            'file_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
            ),
            'content' => array(
                'type' => 'TEXT',
            ),
            'stream_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
            ),
            'version' => array(
                'type' => 'INT',
                'constraint' => '6',
                'default' => 1,
            ),
            'meta' => array(
                'type' => 'TEXT',
            ),
        );

        $name = $term . '_' . $class_name . '_student_files';
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($name);
    }

    function testcases_tbl($term, $class_name) {
        $fields = array(
            'prog_id' => array(
                'type' => 'INT',
                'constraint' => '6'
            ),
            'tc_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '25'
            ),
            'tc_num' => array(
                'type' => 'INT',
                'constraint' => '3'
            ),
            'tcf' => array(
                'type' => 'TEXT',
            ),
            'expf' => array(
                'type' => 'TEXT',
            ),
            'active' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 0
            ),
            'version' => array(
                'type' => 'INT',
                'constraint' => '3',
                'default' => 1
            ),
        );

        $name = $term . '_' . $class_name . '_testcases';
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($name);
    }

    // **********************************************
    // General purpose CLASS TABLES
    // **********************************************
    // class sessions table
    function sessions_tbl($term, $class_name) {
        $fields = array(
            'session_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '25'
            ),
            'start' => array(
                'type' => 'DATETIME'
            ),
            'end' => array(
                'type' => 'DATETIME'
            ),
            'late' => array(
                'type' => 'DATETIME'
            ),
            
            'active' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 1
            ),
        );

        $name = $term . '_' . $class_name . '_sessions';
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($name);
    }

    // Class Announcements
    function announce_tbl($term, $class_name) {
        $fields = array(
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'description' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'content' => array(
                'type' => 'TEXT'
            ),
            'author' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'date' => array(
                'type' => 'DATE'
            ),
            'active' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 1,
            ),
        );

        $name = $term . '_' . $class_name . '_announcements';
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($name);
    }

    // Class assignment results table
    function results_tbl($term, $class_name) {
        $fields = array(
            'prog_id' => array(
                'type' => 'INT'
            ),
            'user_id' => array(
                'type' => 'INT'
            ),
            'score' => array(
                'type' => 'INT'
            ),
            'c_points' => array(
                'type' => 'INT'
            ),
            'e_points' => array(
                'type' => 'INT'
            ),
            'd_points' => array(
                'type' => 'INT'
            ),
            's_points' => array(
                'type' => 'INT'
            ),
            'late' => array(
                'type' => 'INT'
            ),
            'e_report' => array(
                'type' => 'TEXT'
            ),
            'd_report' => array(
                'type' => 'TEXT'
            ),
            'report' => array(
                'type' => 'TEXT'
            ),
            'submits' => array(
                'type' => 'INT',
                'constraint' => '3',
                'default' => 1,
            ),
            'hash' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'lines' => array(
                'type' => 'INT',
                'constraint' => '4',
                'default' => 0
            ),
            'complexity' => array(
                'type' => 'INT',
                'constraint' => '4',
                'default' => 0
            ),
            'elapsed_time' => array(
                'type' => 'TIME',
            ),
            'time TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        );

        $name = $term . '_' . $class_name . '_results';
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($name);
    }

    function gradebk_tbl($term, $classname) {
        $fields = array(
            'user_id' => array(
                'type' => 'INT',
                'constraint' => '9'
            ),
            'prog_id' => array(
                'type' => 'INT',
                'constraint' => '9'
            ),
            'total' => array(
                'type' => 'INT',
                'constraint' => '9'
            ),
            'avg' => array(
                'type' => 'DOUBLE',
            ),
            'grade' => array(
                'type' => 'VARCHAR',
                'constraint' => '3'
            ),
        );

        $name = $term . '_' . $classname . '_gradebook';
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($name);
    }

    // Calendar table
    function calendar_tbl() {
        /*
          $fields = array(

          );

          $name = $term. '_'.$class_name.'_calendar' ;
          $this->dbforge->add_field('id');
          $this->dbforge->add_field($fields);
          $this->dbforge->create_table($name);

         */
    }

    function student_results_tbl($term, $class_name) {
        $fields = array(
            'prog_id' => array(
                'type' => 'INT',
                'constraint' => 6,
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 6,
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 30,
            ),
            'file' => array(
                'type' => 'TEXT'
            ),
            'version' => array(
                'type' => 'INT',
                'constraint' => 6,
                'default' => 1
            ),
        );

        $name = $term . '_' . $class_name . '_sr';
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($name);
    }

    // Class Announcements
    function lab_blogs_tbl($term, $class_name) {
        $fields = array(
            'session_id' => array(
                'type' => 'INT',
                'constraint' => '6',
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'description' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'content' => array(
                'type' => 'TEXT'
            ),
            'author' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'created' => array(
                'type' => 'DATETIME'
            ),
            'updated' => array(
                'type' => 'DATETIME'
            ),
            'active' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 1,
            ),
        );

        $name = $term . '_' . $class_name . '_blogs';
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($name);
    }
    
    // ====================================
    // Logs
    // ====================================
    function time_log($term, $class_name) {
        $fields = array(
            'user_id' => array(
                'type' => 'INT',
            ),
            'item_id' => array(
                'type' => 'INT',
            ),
            'type' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
            ),
            'hash' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'time TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        );

        $name = $term . '_' . $class_name . '_log';
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($name);
    }
    
    function check_time_log($class_id){
        $class = $this->load_class_info($class_id);
        $term = $class[0]->term;
        $class_name = $class[0]->class_name;
        
        $name = $term . '_' . $class_name . '_log';
        if(!$this->db->table_exists($name)){
            $this->time_log($term, $class_name);
        }
        
    }
    
    function submit_log($term, $class_name) {
        $fields = array(
            'prog_id' => array(
                'type' => 'INT'
            ),
            'user_id' => array(
                'type' => 'INT'
            ),
            'score' => array(
                'type' => 'INT'
            ),
            'c_points' => array(
                'type' => 'INT'
            ),
            'e_points' => array(
                'type' => 'INT'
            ),
            'd_points' => array(
                'type' => 'INT'
            ),
            's_points' => array(
                'type' => 'INT'
            ),
            'late' => array(
                'type' => 'INT'
            ),
            'e_report' => array(
                'type' => 'TEXT'
            ),
            'd_report' => array(
                'type' => 'TEXT'
            ),
            'report' => array(
                'type' => 'TEXT'
            ),
            'submits' => array(
                'type' => 'INT',
                'constraint' => '4',
                'default' => 1,
            ),
            'hash' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'lines' => array(
                'type' => 'INT',
                'constraint' => '4',
                'default' => 0
            ),
            'complexity' => array(
                'type' => 'INT',
                'constraint' => '4',
                'default' => 0
            ),
            'elapsed_time' => array(
                'type' => 'TIME',
            ),
            'time TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'notes' => array(
                'type' => 'TEXT'
            ),
        );

        $name = $term . '_' . $class_name . '_log_submits';
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($name);
    }
    
    function check_submit_log($class_id){
        $class = $this->load_class_info($class_id);
        $term = $class[0]->term;
        $class_name = $class[0]->class_name;
        $name = $term . '_' . $class_name . '_log_submits';
        
        if(!$this->db->table_exists($name)){
            $this->submit_log($term, $class_name);
        }
        
    }
    
    /*
    function check_analytics($class_id){
        $class = $this->load_class_info($class_id);
        $term = $class[0]->term;
        $class_name = $class[0]->class_name;
        $name = $term . '_' . $class_name . '_results';
        
        if ($this->db->field_exists('field_name', $name)){
            
        }
    }
     * 
     */

}
