<?php

namespace barrelstrength\sproutreports\migrations;

use barrelstrength\sproutreports\SproutReports;

class Install extends \craft\db\Migration
{
	private $reportTable      = '{{%sproutreports_report}}';
	private $reportGroupTable = '{{%sproutreports_reportgroups}}';
	private $dataSourcesTable = '{{%sproutreports_datasources}}';

	public function safeUp()
	{
		SproutReports::$app->sproutReportMigration->createTables();
	}

	public function safeDown()
	{
		$reportTable = $this->getDb()->getTableSchema($this->reportTable);

		if ($reportTable != null)
		{
			$this->dropTable($this->reportTable);
		}

		$reportGroupTable = $this->getDb()->getTableSchema($this->reportGroupTable);

		if ($reportGroupTable != null)
		{
			$this->dropTable($this->reportGroupTable);
		}

		$dataSourcesTable = $this->getDb()->getTableSchema($this->dataSourcesTable);

		if ($dataSourcesTable != null)
		{
			$this->dropTable($this->dataSourcesTable);
		}
	}

	public function createTables()
	{
/*		$this->createTable('{{%sproutreports_report}}', [
			'id'     => $this->primaryKey(),
			'name'   => $this->string()->notNull(),
			'handle' => $this->string()->notNull(),
		  'description'  => $this->text(),
		  'allowHtml'    => $this->boolean(),
		  'options'      => $this->text(),
		  'dataSourceId' => $this->string(),
		  'groupId'      => $this->integer(),
		  'enabled'      => $this->boolean(),
			'dateCreated'  => $this->dateTime()->notNull(),
			'dateUpdated'  => $this->dateTime()->notNull(),
			'uid'          => $this->uid()
		]);*/

		/*$this->createTable('{{%sproutreports_reportgroups}}', [
			'id'          => $this->primaryKey(),
			'name'        => $this->string()->notNull(),
			'dateCreated' => $this->dateTime()->notNull(),
			'dateUpdated' => $this->dateTime()->notNull(),
			'uid'         => $this->uid()
		]);

		$this->createTable('{{%sproutreports_datasources}}', [
			'id'           => $this->primaryKey(),
			'dataSourceId' => $this->string(),
			'options'      => $this->text(),
			'allowNew'     => $this->boolean(),
			'dateCreated'  => $this->dateTime()->notNull(),
			'dateUpdated'  => $this->dateTime()->notNull(),
			'uid'          => $this->uid()
		]);

		$this->createIndex($this->db->getIndexName('{{%sproutreports_report}}', 'handle', true, true),
			'{{%sproutreports_report}}', 'name', true);

		$this->createIndex($this->db->getIndexName('{{%sproutreports_report}}', 'name', true, true),
			'{{%sproutreports_report}}', 'name', true);

		$this->createIndex($this->db->getIndexName('{{%sproutreports_report}}', 'dataSourceId', true, false),
			'{{%sproutreports_report}}', 'dataSourceId', false);

		$this->createIndex($this->db->getIndexName('{{%sproutreports_reportgroups}}', 'name', false, true),
			'{{%sproutreports_reportgroups}}', 'name', false);*/
	}
}