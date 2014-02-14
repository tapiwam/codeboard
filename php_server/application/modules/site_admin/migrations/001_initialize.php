<?php

class Migration_Initialize extends CI_Migration {

	public function up()
	{
		$this->init_classes_tbl();
		$this->init_reg_tbl();
		$this->init_user_sessions_tbl();
		$this->init_users_tbl();
		$this->initial_users();
	}

	public function down()
	{
		$this->dbforge->drop_table('classes');
		$this->dbforge->drop_table('registration');
		$this->dbforge->drop_table('codeboard_sessions');
		$this->dbforge->drop_table('users');
	}
	
	
	function init_classes_tbl()
	{
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
			'class_name_id' => array(
				'type' => 'VARCHAR',
				'constraint' => '75',
				),
		);
		
		$this->dbforge->add_field('id');
		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('classes');
	}
	
	function init_reg_tbl()
	{
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
				),
		);
		
		$this->dbforge->add_field('id');
		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('registration');
	}

	function init_users_tbl()
	{
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
		);
		
		$this->dbforge->add_field('id');
		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('users');
	}
	
	function init_user_sessions_tbl()
	{
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
		$this->dbforge->add_field('last_activity INT(10) unsigned DEFAULT 0 NOT NULL');
		$this->dbforge->create_table('codeboard_sessions');
		$this->db->query('ALTER TABLE `ci_sessions` ADD KEY `last_activity_idx` (`last_activity`)'); 
	}

	function initial_users(){
		$admin = array(
			'first_name' => 'Admin',
			'last_name' => 'Instructor',
			'username' => 'admin',
			'password' => md5('password'),
			'email' => 'admin@test.com',
			'level' => '1'
		);
		
		$st = array(
			'first_name' => 'Admin',
			'last_name' => 'Super',
			'username' => 'admin2',
			'password' => md5('admin2'),
			'email' => 'admin@test.com',
			'level' => '2'
		);
		
		$this->db->insert('users', $admin);
		$this->db->insert('users', $st);
	}
}