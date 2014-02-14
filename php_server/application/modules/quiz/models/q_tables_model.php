<?php

class Q_tables_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->dbforge();
    }

    function init($term, $class_name) {

        if (!$this->db->table_exists($term . '_' . $class_name . '_quiz')) {
            $this->quizinfo_tbl($term, $class_name);
        }

        if (!$this->db->table_exists($term . '_' . $class_name . '_ques')) {
            $this->ques_tbl($term, $class_name);
        }

        if (!$this->db->table_exists($term . '_' . $class_name . '_quiz_ques')) {
            $this->quiz_ques_tbl($term, $class_name);
        }

        if (!$this->db->table_exists($term . '_' . $class_name . '_quiz_answers')) {
            $this->answer_tbl($term, $class_name);
        }

        if (!$this->db->table_exists($term . '_' . $class_name . '_ques_choices')) {
            $this->mcq_tbl($term, $class_name);
        }

        if (!$this->db->table_exists($term . '_' . $class_name . 'ques_prog')) {
            $this->ques_prog_tbl($term, $class_name);
        }
    }

    function quizinfo_tbl($term, $class_name) {
        $fields = array(
            'class_id' => array(
                'type' => 'INT',
            ),
            'session_id' => array(
                'type' => 'INT',
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '55'
            ),
            'description' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'type' => array(
                'type' => 'VARCHAR',
                'constraint' => '15'
            ),
            'active' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 1,
            ),
        );

        $name = $term . '_' . $class_name . '_quiz';
        if (!$this->db->table_exists($name)) {
            $this->dbforge->add_field('id');
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table($name);
        }
    }

    function quiz_ques_tbl($term, $class_name) {
        $fields = array(
            'quiz_id' => array(
                'type' => 'INT',
            ),
            'ques_id' => array(
                'type' => 'INT',
            ),
        );

        $name = $term . '_' . $class_name . '_quiz_ques';
        if (!$this->db->table_exists($name)) {
            $this->dbforge->add_field('id');
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table($name);
        }
    }

    function ques_tbl($term, $class_name) {
        $fields = array(
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'question' => array(
                'type' => 'TEXT',
            ),
            'type' => array(
                'type' => 'VARCHAR',
                'constraint' => '25'
            ),
            'points' => array(
                'type' => 'INT',
                'constraint' => '2'
            ),
            'terms' => array(
                'type' => 'TEXT',
            ),
            'active' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 1,
            ),
        );

        $name = $term . '_' . $class_name . '_ques';
        if (!$this->db->table_exists($name)) {
            $this->dbforge->add_field('id');
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table($name);
        }
    }

    function ques_prog_tbl($term, $class_name) {
        $fields = array(
            'ques_id' => array(
                'type' => 'INT',
            ),
            'code' => array(
                'type' => 'TEXT',
            ),
            'shell' => array(
                'type' => 'TEXT',
            ),
            'snippet' => array(
                'type' => 'TEXT',
            ),
            'tcf' => array(
                'type' => 'TEXT',
            ),
            'exp' => array(
                'type' => 'TEXT',
            ),
        );

        $name = $term . '_' . $class_name . '_ques_prog';
        if (!$this->db->table_exists($name)) {
            $this->dbforge->add_field('id');
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table($name);
        }
    }

    function mcq_tbl($term, $class_name) {
        $fields = array(
            'ques_id' => array(
                'type' => 'INT',
            ),
            'ans' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'correct' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 0,
            ),
            'choice' => array(
                'type' => 'VARCHAR',
                'constraint' => '3',
            ),
        );

        $name = $term . '_' . $class_name . '_ques_choices';
        if (!$this->db->table_exists($name)) {
            $this->dbforge->add_field('id');
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table($name);
        }
    }

    function answer_tbl($term, $class_name) {
        $fields = array(
            'user_id' => array(
                'type' => 'INT',
            ),
            'quiz_id' => array(
                'type' => 'INT',
            ),
            'ques_id' => array(
                'type' => 'INT',
            ),
            'choice_id' => array(
                'type' => 'INT'
            ),
            'points' => array(
                'type' => 'INT'
            ),
            'correct' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 0,
            ),
            'answer_time' => array(
                'type' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
            ),
        );

        $name = $term . '_' . $class_name . '_quiz_answers';
        if (!$this->db->table_exists($name)) {
            $this->dbforge->add_field('id');
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table($name);
        }
    }

}

?>
