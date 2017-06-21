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
	 * Loads the Settings Index page
	 *
	 * @throws InvalidParamException
	 */
	public function actionSettingsIndex()
	{
		\Craft::dd('xx');
		$settingsModel = new Settings();

		$settings = (new Query())
			->select('settings')
			->from(['{{%plugins}}'])
			->where('class=:class', [':class' => 'SproutReports'])
			->scalar();

		$settings = Json::decode($settings);
		$settingsModel->setAttributes($settings);

		$settingsTemplate = Craft::$app->request->getSegment(3);

		return $this->renderTemplate('sproutreports/settings/' . $settingsTemplate, [
			'settings' => $settingsModel
		]);
	}

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
