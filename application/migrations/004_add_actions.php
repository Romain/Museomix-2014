<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_actions extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 5,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'secret_id' => array(
				'type' => 'VARCHAR',
				'constraint' => '30',
			),
			'action' => array(
				'type' => 'VARCHAR',
				'constraint' => '20',
			),
			'created_at' => array(
				'type' => 'varchar',
				'constraint' => '20',
			),
			'updated_at' => array(
				'type' => 'TIMESTAMP',
			),
		));

		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table(DBPREFIX.'actions', TRUE);
	}

	public function down()
	{
		$this->dbforge->drop_table(DBPREFIX.'actions');
	}
}