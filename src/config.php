<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */

/**
 * Sprout Reports config.php
 *
 * This file exists only as a template for the Sprout Reports settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to 'craft/config' as 'sprout-reports.php'
 * and make your changes there to override default settings.
 *
 * Once copied to 'craft/config', this file will be multi-environment aware as
 * well, so you can have different settings groups for each environment, just as
 * you do for 'general.php'
 */

return [
    // Set the number of results to initially display when a report is run
    'defaultPageLength' => 50,

    // Set the default export delimiter setting when creating new reports
    'defaultExportDelimiter' => ','
];
