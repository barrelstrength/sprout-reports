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
}