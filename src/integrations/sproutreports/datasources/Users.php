<?php

namespace barrelstrength\sproutreports\integrations\sproutreports\datasources;

use barrelstrength\sproutbase\contracts\sproutreports\BaseDataSource;
use Craft;
use barrelstrength\sproutbase\models\sproutreports\Report as ReportModel;
use craft\db\Query;

class Users extends BaseDataSource
{
    /**
     * @return string
     */
    public function getName()
    {
        return Craft::t('sprout-reports', 'Users');
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return Craft::t('sprout-reports', 'Create reports about your users and user groups.');
    }

    /**
     * @param ReportModel $report
     * @param array       $settings
     *
     * @return array
     */
    public function getResults(ReportModel $report, array $settings = [])
    {
        // First, use dynamic options, fallback to report options
        if (!count($settings)) {
            $settings = $report->getSettings();
        }

        $userGroupIds = $settings->userGroups ?? false;
        $displayUserGroupColumns = $settings->displayUserGroupColumns ?? false;

        $includeAdmins = false;

        if (is_array($userGroupIds) && in_array('admin', $userGroupIds, false)) {
            $includeAdmins = true;

            // Admin is always the first in our array if it exists
            unset($userGroupIds[0]);
        }

        $userGroups = Craft::$app->getUserGroups()->getAllGroups();

        $userGroupsByName = [];
        foreach ($userGroups as $userGroup) {
            $userGroupsByName[$userGroup->name] = 0;
        }

        $selectQueryString = "users.id,
            users.username AS Username,
            users.email AS Email,
            (users.firstName) AS 'First Name',
            (users.lastName) AS 'Last Name'";

        if ($displayUserGroupColumns) {
            $selectQueryString .= ',users.admin AS Admin';
        }

        $query = new Query();
        $userQuery = $query
            ->select($selectQueryString)
            ->from('users')
            ->join('LEFT JOIN', 'usergroups_users', 'users.id = usergroups_users.userId');

        if (is_array($userGroupIds)) {
            $userQuery->where(['in', 'usergroups_users.groupId', $userGroupIds]);
        }

        if ($includeAdmins) {
            $userQuery->orWhere('users.admin = 1');
        }

        $userQuery->groupBy('users.id');

        // @todo - can we query users and user their ids as the array key?
        $users = $userQuery->all();

        // Update users to be indexed by their ids
        $usersById = [];
        foreach ($users as $user) {
            $usersById[$user['id']] = $user;
            unset ($usersById[$user['id']]['id']);
        }

        $query = new Query();
        $userGroupsMapQuery = $query
            ->select('*')
            ->from('usergroups_users')
            ->join('LEFT JOIN', 'usergroups', 'usergroups.id = usergroups_users.groupId')
            ->all();

        // Create a map of all users and which user groups they are in
        $userGroupsMap = [];
        foreach ($userGroupsMapQuery as $userGroupsUser) {
            $userGroupsMap[$userGroupsUser['userId']][$userGroupsUser['name']] = true;
        }

        // Add and identify User Groups as columns
        foreach ($usersById as $key => $user) {
            if ($displayUserGroupColumns) {
                // Add User Groups as columns to user array
                $user = array_merge($user, $userGroupsByName);

                if (isset($userGroupsMap[$key])) {
                    // Match users to the user groups they are in
                    $user = array_merge($user, $userGroupsMap[$key]);
                }
            }

            $usersById[$key] = $user;
        }

        return $usersById;
    }

    /**
     * @param array $settings
     *
     * @return null|string
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getSettingsHtml(array $settings = [])
    {
        $userGroups = Craft::$app->getUserGroups()->getAllGroups();

        $userGroupSettings[] = [
            'label' => 'Admin',
            'value' => 'admin'
        ];

        foreach ($userGroups as $userGroup) {
            $userGroupSettings[] = [
                'label' => $userGroup->name,
                'value' => $userGroup->id
            ];
        }

        $settingsErrors = $this->report->getErrors('settings');
        $settingsErrors = array_shift($settingsErrors);

        return Craft::$app->getView()->renderTemplate('sprout-reports/datasources/_settings/users', [
            'userGroupSettings' => $userGroupSettings,
            'settings' => count($settings) ? $settings : $this->report->getSettings(),
            'errors' => $settingsErrors
        ]);
    }

    /**
     * Validate our data source settings
     *
     * @param array $settings
     * @param array $errors
     *
     * @return bool
     */
    public function validateSettings(array $settings = [], array &$errors)
    {
        if (empty($settings['userGroups'])) {
            $errors['userGroups'][] = Craft::t('sprout-reports', 'Select at least one User Group.');

            return false;
        }

        return true;
    }
}
