<?php

namespace barrelstrength\sproutreports\assetbundles\reports;

use barrelstrength\sproutcore\web\assets\sproutreports\ReportCoreAsset;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class ReportAsset extends AssetBundle
{
	public function init()
	{
		$this->sourcePath = "@barrelstrength/sproutreports/assetbundles/reports/dist";

		$this->depends = [
			ReportCoreAsset::class,
		];

		$this->js = [
			'js/groups.js',
		];

		parent::init();
	}
}