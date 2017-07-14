<?php
namespace barrelstrength\sproutreports\controllers;

use barrelstrength\sproutreports\SproutReports;
use Craft;
use barrelstrength\sproutreports\models\Settings;
use craft\helpers\Json;
use craft\web\Controller;
use craft\db\Query;
use Imagine\Exception\InvalidArgumentException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

class SettingsController extends Controller
{
	/**
	 * Saves Plugin Settings
	 *
	 * @throws BadRequestHttpException
	 */
	public function actionSaveSettings()
	{
		$this->requirePostRequest();
		$settings = Craft::$app->getRequest()->getBodyParam('settings');

		if (SproutReports::$app->settings->saveSettings($settings))
		{
			Craft::$app->getSession()->setNotice(SproutReports::t('Settings saved.'));

			$this->redirectToPostedUrl();
		}
		else
		{
			Craft::$app->getSession()->setError(SproutReports::t('Couldn’t save settings.'));

			Craft::$app->getUrlManager()->setRouteParams([
				'settings' => $settings
			]);
		}
	}
}
