<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutimport\migrations;

use barrelstrength\sproutcore\migrations\sproutreports\Install as SproutCoreReportsInstall;
use craft\db\Migration;
use Craft;

class Install extends Migration
{
	// Public Methods
	// =========================================================================

	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->runSproutCoreInstall();

		return true;
	}

	// Protected Methods
	// =========================================================================

	protected function runSproutCoreInstall()
	{
		$migration = new SproutCoreReportsInstall();

		ob_start();
		$migration->safeUp();
		ob_end_clean();
	}
}