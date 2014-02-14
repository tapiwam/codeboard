<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function email(){
        
    }
    
    function schedule(){
        
    }
    
    function all_schedule(){
        
    }
    
    function email_class($class_id){
        
    }
    
    function email_all(){
        
    }

    function email_tbl() {
        $fields = array(
            'from' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'to' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'subject' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'message' => array(
                'type' => 'TEXT',
            ),
            'attachment' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
            ),
            'sent' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 0,
            ),
            'sent_time' => array(
                'type' => 'DATETIME',
            ),
            'delay' => array(
                'type' => 'DATETIME',
            ),
            'active' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 1,
            ),
            
        );

        $name = 'email';
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($name);
    }

}

