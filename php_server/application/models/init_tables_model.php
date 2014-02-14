<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tables_model extends CI_Model {

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
    // BASIC INITIALIZATION TABLES
    // *************************************

    function init_classes_tbl() {
        $fields = array(
            'term' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
            ),
            'class_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
            ),
            'section' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
            ),
            'instructor' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
            ),
            'lang' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
            ),
            'passcode' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
            ),
            'active' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 1,
            ),
        );

        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('classes');
    }
    
    function init_class_instructors_tbl() {
        $fields = array(
            'class_id' => array(
                'type' => 'INT',
            ),
            'user_id' => array(
                'type' => 'INT',
            ),
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
            ),
            'owner' => array(
                'type' => 'INT',
            ),
        );

        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('class_instructors');
    }

    function init_reg_tbl() {
        $fields = array(
            'user_id' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'class_id' => array(
                'type' => 'INT',
                'constraint' => '6',
            ),
            'active' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 1,
            ),
        );

        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('registration');
    }

    function init_users_tbl() {
        $fields = array(
            'first_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
            ),
            'last_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
            ),
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'level' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
            ),
            'student_id' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'active' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 1,
            ),
        );

        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('users');
    }

    function init_user_sessions_tbl() {
        $fields = array(
            'session_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '40',
                'default' => 0,
            ),
            'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => '45',
                'default' => 0,
            ),
            'user_agent' => array(
                'type' => 'VARCHAR',
                'constraint' => '120',
            ),
            'last_activity' => array(
                'type' => 'INT',
                'constraint' => '10',
                'default' => 0,
            ),
            'user_data' => array(
                'type' => 'TEXT',
            ),
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('codeboard_sessions');
    }
    
    function init_login_log() {
        $fields = array(
            'user_id' => array(
                'type' => 'INT',
            ),
            'time TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('codeboard_sessions');
    }


}
