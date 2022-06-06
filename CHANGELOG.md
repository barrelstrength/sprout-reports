# Changelog

## 1.5.13 - 2022-06-06

### Fixed
- Fixed bug where TimePicker is no longer loaded on Report settings page ([#115])

[#115]: https://github.com/barrelstrength/craft-sprout-reports/issues/115

## 1.5.12 - 2021-11-12

### Changed
- Updated `barrelstrength/sprout-base-reports` requirement v1.5.12

### Fixed
- Fixed pagination styles on results page ([#117])

[#117]: https://github.com/barrelstrength/craft-sprout-reports/issues/117

## 1.5.10 - 2021-03-27

### Fixed
- Update migration to conditionally address `pluginHandle` column ([#111])

[#111]: https://github.com/barrelstrength/craft-sprout-reports/issues/111

## 1.5.9 - 2021-02-10

### Changed
- Updated `barrelstrength/sprout-base-reports` requirement v1.5.9

### Fixed
- Fixed specificity of name column on Report Element index page

## 1.5.8 - 2021-02-10

### Changed
- Updated `barrelstrength/sprout-base-reports` requirement v1.5.8

### Fixed
- Fixed database migration column references ([#95], [#97])
- Improved data source schema tedium in Craft 2 to Craft 3 migration

[#95]: https://github.com/barrelstrength/craft-sprout-reports/issues/95
[#97]: https://github.com/barrelstrength/craft-sprout-reports/issues/97

## 1.5.7 - 2020-10-13

### Changed
- Updated `craftcms/cms` requirement v3.5.0
- Updated `barrelstrength/sprout-base-reports` requirement v1.5.7
- Updated `barrelstrength/sprout-base` requirement v6.0.2
- Updated `league/csv` to allow optional requirement v9.6.1

### Fixed
- Fixed DataSourcesController init behavior in Craft 3.5
- Fixed Settings init behavior in Craft 3.5

## 1.5.6 - 2020-05-28

### Changed
- Updated `barrelstrength/sprout-base-reports` requirement v1.5.6

### Fixed
- Removed problematic, niche data type migration

## 1.5.5 - 2020-05-27

### Changed
- Updated `barrelstrength/sprout-base-reports` requirement v1.5.5

### Fixed
- Fixed migration data type bug that could occur in some scenarios 

## 1.5.4 - 2020-05-21

### Changed
- Updated Report delete behavior to consistently Hard Delete	
- Updated `barrelstrength/sprout-base-reports` requirement v1.5.3

### Fixed
- Added migration to cleanup User Report value in `sproutreports_reports.dataSourceId`
- Added migration to ensure `dataSourceId` column type is correct in PostgreSQL
- Added migration to cleanup orphaned Reports and ensure Report Elements have a foreign key

## 1.5.3 - 2020-05-16

### Fixed
- Fixed Plugin Name setting behavior

## 1.5.2 - 2020-05-15

## Added
- Added Report export delimiter setting and support for comma, semi-colon, and tab-delimited exports
- Added Default Export Delimiter setting
- Added `barrelstrength\sproutbasereports\elements\Report::$delimiter`
- Added `barrelstrength\sproutbasereports\models\Settings::$defaultExportDelimiter`

### Changed
- Updated `barrelstrength/sprout-base-reports` requirement v1.5.2

### Fixed
- Fixed bug where settings weren’t saved to `sprout_settings` table
- Fixed translation categories in report settings

## 1.5.1 - 2020-05-03

### Changed
- Updated `barrelstrength/sprout-base-reports` requirement v1.5.1

### Fixed
- Fixed migration scenario where base table wasn't installed correctly ([#83])

- https://github.com/barrelstrength/craft-sprout-reports/issues/83

## 1.5.0 - 2020-04-28

### Changed
- Updated `barrelstrength/sprout-base` requirement v6.0.0

### Fixed
- Fixed migration issue when multiple Sprout plugins are installed

## 1.4.0 - 2020-04-27

### Added
- Added example config file `src/config.php`
- Added `barrelstrength\sproutbase\base\SproutDependencyTrait`
- Added `barrelstrength\sproutbase\base\SproutDependencyInterface`
- Added `barrelstrength\sproutbase\records\Settings`
- Added `barrelstrength\sproutbase\migrations\Install::safeDown()`
- Added support for config overrides in base settings models

### Changed
- Improved uninstall migration
- Updated `barrelstrength/sprout-base` requirement v5.2.0
- Updated `barrelstrength/sprout-base-reports` requirement v1.5.0

### Removed
- Removed `barrelstrength\sproutbase\services\Settings::getPluginSettings()`
- Removed `barrelstrength\sproutbase\base\BaseSproutTrait`

## 1.3.3 - 2020-03-14

### Added
- Added support for setting default Sort Order and Sort Column on Reports ([#60], [#71])

### Changed
- Updated `barrelstrength/sprout-base-reports` requirement to v1.4.4

### Fixed
- Fixed behavior where Report would default to ordering by first column instead of simply displaying the default report query results ([#71])
- Fixed bug where `dataSourceBaseUrl` was not defined after editing a report with validation errors

[#60]: https://github.com/barrelstrength/craft-sprout-reports/issues/60
[#71]: https://github.com/barrelstrength/craft-sprout-reports/issues/71

## 1.3.2 - 2020-02-10

### Fixed 
- Fixed bug where Sprout Base Fields was not registered properly ([#77])

[#77]: https://github.com/barrelstrength/craft-sprout-reports/issues/77

## 1.3.1 - 2020-02-07

### Changed 
- Updated `barrelstrength/sprout-base-reports` requirement to v1.4.1

## 1.3.0 - 2020-02-05

### Added 
- Added Mailing List Reports integration
- Added support for Notification Emails to be sent to Mailing List Reports
- Added Email Column setting, to identify the `email` column of a Mailing List report
- Added 'All mailing lists' source
- Added Users Data Source
- Added Report Groups
- Added `barrelstrength\sproutbasereports\elements\Report::$emailColumn`
- Added `barrelstrength\sproutbasereports\elements\Report::canBeDeleted()`
- Added `barrelstrength\sproutbasereports\base\DataSource::isEmailColumnEditable()`
- Added `barrelstrength\sproutbasereports\base\DataSource::getDefaultEmailColumn()`
- Added `barrelstrength\sproutbasereports\elements\Report::pluralDisplayName()`

### Changed
- Updated Results UI to match look and field of Craft 3.4
- Added 'autofocus` behavior to Search box on results page
- Improved logic when displaying Rename Group dropdown
- Updated `barrelstrength/sprout-base-reports/base/DataSource::DEFAULT_VIEW_CONTEXT` default to `sprout-reports`
- Updated `barrelstrength/sprout-base-reports` requirement to v1.4.0
- Updated `barrelstrength/sprout-base` requirement to v5.1.0

### Fixed
- Fixed several bugs introduced in Craft 3.4
- Fixed bug where `Command+S` save action did not reload current page

### Removed
- Removed support for Sprout Lists integration (use Mailing List Reports)

### Fixed
- Fixed bug in Users Data Source where error was thrown if no user group was selected

## 1.2.11 - 2019-11-22

### Changed
- Updated barrelstrength/sprout-base-reports requirement v1.3.10

### Fixed
- Fixed bug where Report may not exist when loading Dashboard widget ([#64])
- Fixed bug when running console requests ([#66])
- Fixed horizontal scroll on some screen sizes ([#67])

[#64]: https://github.com/barrelstrength/craft-sprout-reports/issues/64
[#66]: https://github.com/barrelstrength/craft-sprout-reports/issues/66
[#67]: https://github.com/barrelstrength/craft-sprout-reports/issues/67

## 1.2.8 - 2019-10-04

### Changed
- Updated craftcms/cms requirement to v3.3.7

### Fixed 
- Fixed `renderString` breaking change introduced by Craft and resolved in Craft CMS v3.3.7 ([#63]) 

[#63]: https://github.com/barrelstrength/craft-sprout-reports/issues/63

## 1.2.7 - 2019-09-04

### Changed
- Updated barrelstrength/sprout-base-reports requirement v1.3.8

### Fixed
- Fixed migration bug where `viewContext` column may not be found

## 1.2.6 - 2019-08-15

### Changed
- Updated barrelstrength/sprout-base requirement v5.0.7
- Updated barrelstrength/sprout-base-reports requirement v1.3.7

### Fixed
- Fixed bug where 'View Reports' permission did not allow a user to export reports ([#50])
- Fixed bug where `pluginHandle` column may not be found in Data Sources migration ([#315][#315sproutforms], [#318][#318sproutforms])

[#50]: https://github.com/barrelstrength/craft-sprout-reports/issues/50
[#315sproutforms]: https://github.com/barrelstrength/craft-sprout-forms/issues/315
[#318sproutforms]: https://github.com/barrelstrength/craft-sprout-forms/issues/318

## 1.2.5 - 2019-07-26

### Changed
- Updated barrelstrength/sprout-base-reports requirement v1.3.5

## 1.2.4 - 2019-07-16

### Changed
- Improves Data Source integration support
- Updated barrelstrength/sprout-base-reports requirement to 1.3.4

## 1.2.3 - 2019-07-14

### Added
- Added `isExport` variable to use in Twig Template reports

### Changed
- Updated Twig Data Source to use Auto Suggest Field for template settings
- Updated barrelstrength/sprout-base-reports requirement to 1.3.3

## 1.2.2 - 2019-07-14

### Added
- Added `isExport` property to customize reports for Control Panel display or export use cases
- Added 'Default Results to Display' setting

### Changed
- Updated pagination footer to be styled like Craft 3.2
- Updated pagination length to include options to display 10 and 25 results
- Updated pagination length to default to 10 results

## 1.2.1 - 2019-07-09

### Changed
- Updated Report Name to be dynamic
- Updated barrelstrength/sprout-base-reports requirement to 1.3.1

### Fixed
- Fixed display bug where column headers could be incorrect width
- Fixed display bug where column header order arrow would repeat in Safari

## 1.2.0 - 2019-07-03

> {tip} This release adds a new, interactive results page for your reports including search, ordering columns, and pagination. Enjoy!

### Added
- Added support for search, ordering columns, and pagination on results pages

### Changed
- Updated barrelstrength/sprout-base-reports requirement to 1.3.0

## 1.1.9 - 2019-06-28

### Changed
- Updated barrelstrength/sprout-base-reports requirement to 1.2.1

### Fixed
- Fixed bug where Data Source grouping could cause reports to disappear from the UI ([#297][#297-sproutforms], [#286][#286-sproutforms])

[#286-sproutforms]: https://github.com/barrelstrength/craft-sprout-forms/issues/286
[#297-sproutforms]: https://github.com/barrelstrength/craft-sprout-forms/issues/297

## 1.1.8 - 2019-06-17

> {warning} This release improves Data Source management and registration. Users with Custom Data Sources please review the [upgrade notes](https://sprout.barrelstrengthdesign.com/docs/reports/installing-and-updating-craft-3.html#upgrading-to-v1-1-8) for details on how to update your Custom Data Source.

### Added
- Added Install Data Source action
- Added Delete Data Source action
- Added list of uninstalled Data Sources
- Added improved messaging around Missing Data Sources

### Changed
- Improved Data Source management and registration
- Updated Data Sources to extend SavableComponent
- Removed DataSource model in favor of DataSource base class
- Updated DataSource::dataSourceId => DataSource::id
- Updated barrelstrength/sprout-base-reports requirement v1.2.0
- Updated barrelstrength/sprout-base requirement to v5.0.1

### Fixed
- Fixed bug where Report Element query would throw error on Craft 3.2
- Fixed widget icon path

## 1.1.7 - 2019-06-05

### Fixed
- Fixed issue on twig reports with not results ([#54])

[#54]: https://github.com/barrelstrength/craft-sprout-reports/issues/54

## 1.1.6 - 2019-04-20

### Changed
- Updated barrelstrength/sprout-base-reports requirement to v1.0.4
- Updated barrelstrength/sprout-base requirement v5.0.0

### Fixed
- Improved Postgres support
- Fixed javascript error on Internet Explorer

## 1.1.5 - 2019-03-22

### Changed
- Improved permission handling across plugins implementing Sprout Reports
- Updated barrelstrength/sprout-base-reports requirement to v1.0.3
- Updated barrelstrength/sprout-base requirement v4.0.8

### Fixed
- Fixed bug where custom Data Sources could not be registered from Modules ([#49])
- Fixed incorrect permissions check for non-admin users ([#50])

[#49]: https://github.com/barrelstrength/craft-sprout-reports/issues/49
[#50]: https://github.com/barrelstrength/craft-sprout-reports/issues/50

## 1.1.4 - 2019-03-19

### Changed
- Improves support for Report integrations
- Updated barrelstrength/sprout-base-reports requirement to v1.0.2

### Fixed
- Fixed bug where Settings model was not available for Report integrations

## 1.1.3 - 2019-03-18

### Added
- Added View Reports permission
- Added requirement of Admin status to edit settings

### Changed
- Updated Edit Reports permission to require View Reports permissions
- Updated Report export naming to use toString method ([#9][#9-sproutbasereports])
- Updated barrelstrength/sprout-base-reports requirement to v1.0.1
- Updated barrelstrength/sprout-base requirement to v4.0.7

### Fixed
- Added Report Element migration ([#44])

[#44]: https://github.com/barrelstrength/craft-sprout-reports/issues/44
[#9-sproutbasereports]: https://github.com/barrelstrength/craft-sprout-base/pull/9

## 1.1.2 - 2019-02-13

### Changed
- Improved translation support
- Updated settings to implement SproutSettingsInterface
- Updated barrelstrength/sprout-base requirement to v4.0.6
- Added barrelstrength/sprout-base-reports requirement v1.0.0

## 1.1.1 - 2019-01-25

### Added
- Added initial support for Craft 3.1

### Changed
- Updated Craft CMS requirement to v3.1.0
- Updated Sprout Base requirement to v4.0.5
- Reverted league/csv library to require v8.2.0 to ensure compatibility with Craft

## 1.1.0 - 2019-01-23

### Added
- Stable release.

### Changes
- Removed sproutReports-editSettings permissions. Access to Settings now follows Craft's convention and requires Admin level privileges
- Updated league/csv library to require v9.0.0
- Updated barrelstrength/sprout-base to require v4.0.4

## 1.0.0-beta.16 - 2018-10-29

### Changed
- Updated Sprout Base requirement to v4.0.0

## 1.0.0-beta.15 - 2018-10-27

### Changed
- Updated Sprout Base requirement to v3.0.10

## 1.0.0-beta.14 - 2018-10-23

### Fixed
- Fixed error "unknown column nameFormat" when migrating from Craft 2 to Craft3

## 1.0.0-beta.13 - 2018-09-10

### Added
- Added Delete Report bulk actions

### Changed
- Improved Postgres support ([#22])
- Updated Sprout Base requirement to v3.0.4

### Fixed
- Fixed bug where some New Report links were broken ([#2])
- Fixed foreign key support for Report Elements
- Fixed date field errors in Twig Report example templates ([#28])

[#2]: https://github.com/barrelstrength/craft-sprout-reports/issues/2
[#22]: https://github.com/barrelstrength/craft-sprout-reports/issues/22
[#28]: https://github.com/barrelstrength/craft-sprout-reports/issues/28

## 1.0.0-beta.12 - 2018-07-26

## Changed
- Updated Sprout Base requirement to v3.0.0

## 1.0.0-beta.11 - 2018-07-24

### Added
- Added support for Data Sources in Modules
- Added support for consistent line breaks between HTML & CSV output ([#26])

### Changed
- Updated Sprout Base requirement to v2.0.9

[#26]: https://github.com/barrelstrength/craft-sprout-reports/issues/26

## 1.0.0-beta.10 - 2018-05-17

### Fixed
- Fixes release notes warning syntax

## 1.0.0-beta.9 - 2018-05-15

> {warning} If you have more than one Sprout Plugin installed, to avoid errors use the 'Update All' option.

### Added
- Added minVersionRequired as Sprout Reports v0.9.3

### Changed
- Updated URL pattern for editing Report Elements
- Updated folder structure
- Moved schema and component definitions to Plugin class
- Moved templates to Sprout Base
- Moved asset bundles to Sprout Base

### Fixed
- Fixed Twig Template report display issue

## 1.0.0-beta.7 - 2018-04-17

### Fixed
- Fixed bug where report data source could return null

## 1.0.0-beta.6 - 2018-04-17

### Added
- Added Report Element
- Added Data Source Plugin ID

## 1.0.0-beta.5 - 2018-04-05

### Fixed
- Fixed icon mask display issue

## 1.0.0-beta.4 - 2018-03-25

## Updated
- Updated to Sprout Base v1.1.0

### Fixed
- Fixed incorrect link in README

## 1.0.0-beta.3 - 2018-03-11

## Updated
- Updated to Sprout Base v1.0.9

### Fixed
- Added check for Craft edition before adding UserPermissions
- Fixed beta version naming convention
- Fixed namespace conflict on install migration

## 1.0.0-beta2 - 2018-03-10

### Added
- Updated README

## 1.0.0-beta1 - 2018-03-10

### Added
- Initial Craft 3 release

### Changed
- Moved Categories integration to separate plugin [Sprout Reports - Categories](https://github.com/barrelstrength/craft-sprout-reports-categories)
- Moved Users integration to separate plugin [Sprout Reports - Users](https://github.com/barrelstrength/craft-sprout-reports-users)

### Removed
- Removed Report integrations in favor of Sprout Import

## 0.9.3 - 2018-01-08

### Fixed
- Fixed incorrect template paths

## 0.9.1 - 2017-12-19

### Added
- Added Twig Data Source
- Added support for DateTime fields in Twig Reports
- Added Twig Report example files
- Added support for naming reports dynamically with the Name Format setting
- Added craft.sproutReports.addHeaderRow variable
- Added craft.sproutReports.addRow variable
- Added craft.sproutReports.addRows variable
- Added PHP 5.6 compatibility

### Changed
- Updated Report Groups to be ordered alphabetically

### Fixed
- Fixed method signature in SproutReportsQueryDataSource
- Fixed migration bug

## 0.8.9 - 2016-11-30

### Fixed
- Fixed a potential XSS vulnerability that could occur with custom Data Source integrations

## 0.8.8 - 2016-11-11

### Fixed
- Fixed a migration bug in where the incorrect log class was used

## 0.8.7 - 2016-11-10

### Added
- Added Data Source column to reports
- Added &#039;Edit Data Sources&#039; Permission
- Added Data Source &#039;Allow New?&#039; option to allow access to whether Reports can be created from a particular Data Source

### Changed
- Updated display of Report index page

### Fixed
- Fixed &#039;Edit Reports&#039; permission on some pages where it was not in use
- Fixed &#039;Download&#039; button on Results page
- Fixed bug where dynamically editing Report settings from Results page didn&#039;t save settings
- Fixed &#039;Save and Continue Editing&#039; option when saving a Report
- Fixed horizontal scroll on Results page

## 0.8.4 - 2016-06-01

### Changed
- Added New Report button with dropdown of all report options on Report index page
- Improved workflow around creating and deleting Report Groups

### Fixed
- Fixed bug where updating a Report dynamically did not reflect the updated settings in the results
- Fixed bug where User Report would throw an error on installations using Craft Personal

## 0.8.3 - 2016-04-07

### Added
- Added support for update options on the fly when running a report on the Report page
- Added Fields Datasource which can generate reports to help manage fields

### Changed
- Updated settings settings to be managed in plugin&#039;s Settings section
- Updates SproutReportsBaseDataSource::getDefaultLabels method signature to accept a SproutReports_ReportModel and options.

## 0.8.1 - 2016-03-30

### Added
- Added PHP 7 compatibility

### Fixed
- Fixed bug where editing a report didn&#039;t load the selected Report Group
- Fixed grid layout on Report edit page for large screens

## 0.8.0 - 2016-01-14

### Custom Reports &amp; DataSources
- Sprout Reports now supports custom Reports, Report Options, and Data Sources. Reports have full control over what options they allow a user to select. Additional Reports and Data Sources can be added by plugins. Data Sources can define data available within Craft or beyond!

### Added
- Added support for custom DataSources
- Added support for custom Reports
- Added support for custom Report Options
- Added Custom Query DataSource
- Added Users DataSource
- Added Category Usage by Section DataSource
- Added Users and User Groups Report
- Added option for Reports now display a description
- Added registerSproutReportsDataSources hook
- Added SproutReports_ReportsService::registerReports method
- Control Panel has been updated to work with Craft 2.5
- Added Plugin icon
- Added Plugin description
- Added link to documentation
- Added link to plugin settings
- Added link to release feed
- Added subnav in place of tabs for top level navigation
- Added support for CSRF protection
- Export CSV button on results page
- Adds sproutReports-editReports permission
- Adds sproutReports-editSettings permission

### Changed
- Updated behavior of Number widget to return the result count if a result set has multiple records
- Updated CSV export to use `league/csv` (Requires PHP &gt;= 5.5.0 and the mbstring extension)
- Various UI updates and improvements

## 0.4.4 - 2014-03-24

### Added
- Added query validation by disallowing unsafe commands
- Added modifier flag for table prefix replacement and command escaping
- Added better error reporting on report edit and result pages
- Added ability to &#039;Save and Continue&#039; or &#039;Save and Add Another&#039; report
- Added support for Cmd+S to save reports
- Added ability to add and filter reports by groups
- Added ability to add, update, rename, and delete report groups

### Changed
- Integrated Single Number Report widget with reports
- Improved code spacing, organization, and conventions

### Fixed
- Fixed an issue where the edit page would throw an error for new reports
- Fixed the way CDbExceptions are handled in the service layer

## 0.4.0 - 2014-03-04

### Added
- Private beta
