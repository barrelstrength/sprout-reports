<?php
namespace Craft;

class SproutReports_SettingsController extends BaseController
{
	/**
	 * Save Settings to the Database
	 *
	 * @return mixed Return to Page
	 */
	public function actionSettingsIndexTemplate()
	{
		$settingsModel = new SproutReports_SettingsModel;

		$settings = craft()->db->createCommand()
			->select('settings')
			->from('plugins')
			->where('class=:class', array(':class' => 'SproutReports'))
			->queryScalar();

		$settings = JsonHelper::decode($settings);
		$settingsModel->setAttributes($settings);

		$variables['settings'] = $settingsModel;

		// Load our template
		$this->renderTemplate('sproutreports/settings', $variables);
	}

	/**
	 * Save Plugin Settings
	 *
	 * @return void
	 */
	public function actionSaveSettings()
	{
		$this->requirePostRequest();
		$settings = craft()->request->getPost('settings');

		if (sproutReports()->settings->saveSettings($settings))
		{
			craft()->userSession->setNotice(Craft::t('Settings saved.'));

			$this->redirectToPostedUrl();
		}
		else
		{
			craft()->userSession->setError(Craft::t('Couldn’t save settings.'));

			// Send the settings back to the template
			craft()->urlManager->setRouteVariables(array(
				'settings' => $settings
			));
		}
	}
}