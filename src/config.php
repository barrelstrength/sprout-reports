<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */

/**
 * Reports settings available in craft/config/sprout.php
 *
 * This file does nothing on its own. It provides documentation of the
 * default value for each config setting and provides an example of how to
 * override each setting in 'craft/config/sprout.php`
 *
 * To override default settings, copy the settings you wish to implement to
 * your 'craft/config/sprout.php' config file and make your changes there.
 *
 * Config settings files are multi-environment aware so you can have different
 * settings groups for each environment, just as you do for 'general.php'
 */
return [
    'sprout' => [
        'reports' => [
            // Set the number of results to initially display when a report is run
            'defaultPageLength' => 50,

            // Set the default export delimiter setting when creating new reports
            'defaultExportDelimiter' => ',',
        ],
    ],
];
