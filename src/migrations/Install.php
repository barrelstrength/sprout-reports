<?php

namespace barrelstrength\sproutreports\migrations;

class Install extends \craft\db\Migration
{
	public function safeUp()
	{
		$this->createTables();
	}

	public function safeDown()
	{
		// ...
	}

	public function createTables()
	{
		$this->createTable('{{%sproutreports_report}}', [
			'id'     => $this->primaryKey(),
			'name'   => $this->string()->notNull(),
			'handle' => $this->string()->notNull(),
		  'description'  => $this->text(),
		  'options'      => $this->text(),
		  'dataSourceId' => $this->integer(),
		  'groupId'      => $this->integer(),
		  'enabled'      => $this->boolean()
		]);
	}
}