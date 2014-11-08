<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Sounds_To_Pictures extends CI_Migration {

	public function up()
	{
		$fields = array(
			'sound VARCHAR(50) DEFAULT NULL'
		);

		$this->dbforge->add_column(DBPREFIX.'pictures', $fields, 'picture');
	}

	public function down()
	{
		$this->dbforge->drop_column(DBPREFIX.'pictures', 'sound');
	}
}