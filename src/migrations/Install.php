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
		$this->dropTable('{{%sproutreports_report}}');
		$this->dropTable('{{%sproutreports_reportgroups}}');
	}

	public function createTables()
	{
		$this->createTable('{{%sproutreports_report}}', [
			'id'     => $this->primaryKey(),
			'name'   => $this->string()->notNull(),
			'handle' => $this->string()->notNull(),
		  'description'  => $this->text(),
		  'options'      => $this->text(),
		  'dataSourceId' => $this->string(),
		  'groupId'      => $this->integer(),
		  'enabled'      => $this->boolean()
		]);

		$this->createTable('{{%sproutreports_reportgroups}}', [
			'id'     => $this->primaryKey(),
			'name'   => $this->string()->notNull()
		]);

		$this->createTable('{{%sproutreports_datasources}}', [
			'id'     => $this->primaryKey(),
			'dataSourceId' => $this->string(),
			'options'      => $this->text(),
			'allowNew'      => $this->boolean()
		]);

		$this->createIndex($this->db->getIndexName('{{%sproutreports_report}}', 'handle', true, true),
			'{{%sproutreports_report}}', 'name', true);

		$this->createIndex($this->db->getIndexName('{{%sproutreports_report}}', 'name', true, true),
			'{{%sproutreports_report}}', 'name', true);

		$this->createIndex($this->db->getIndexName('{{%sproutreports_report}}', 'dataSourceId', true, false),
			'{{%sproutreports_report}}', 'dataSourceId', false);

		$this->createIndex($this->db->getIndexName('{{%sproutreports_reportgroups}}', 'name', false, true),
			'{{%sproutreports_reportgroups}}', 'name', false);
	}
}