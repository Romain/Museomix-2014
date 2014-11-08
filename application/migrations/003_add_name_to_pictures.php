<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Name_To_Pictures extends CI_Migration {

	public function up()
	{
		$fields = array(
			'name VARCHAR(50) DEFAULT NULL'
		);

		$this->dbforge->add_column(DBPREFIX.'pictures', $fields, 'comment');
	}

	public function down()
	{
		$this->dbforge->drop_column(DBPREFIX.'pictures', 'name');
	}
}