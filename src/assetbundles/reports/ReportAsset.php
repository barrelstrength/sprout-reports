<?php

namespace barrelstrength\sproutreports\assetbundles\reports;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class ReportAsset extends AssetBundle
{
	public function init()
	{
		$this->sourcePath = "@barrelstrength/sproutreports/assetbundles/reports/dist";

		$this->depends = [
			CpAsset::class,
		];

		$this->js = [
			'js/groups.js',
		];

		$this->css = [
			'css/styles.css',
		];

		parent::init();
	}
}