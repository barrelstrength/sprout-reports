<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutreports\elements;

use barrelstrength\sproutbase\base\BaseSproutTrait;
use barrelstrength\sproutbase\SproutBase;
use barrelstrength\sproutreports\SproutReports;
use barrelstrength\sproutreports\elements\db\ReportQuery;
use barrelstrength\sproutbase\records\sproutreports\Report as ReportRecord;

use Craft;
use craft\helpers\UrlHelper;
use craft\elements\actions\Delete;
use craft\elements\actions\Edit;
use craft\base\Element;
use craft\elements\db\ElementQueryInterface;
use craft\validators\HandleValidator;
use yii\base\ErrorHandler;
use yii\base\Exception;
use yii\validators\UniqueValidator;

/**
 * SproutReports - Report element type
 */
class Report extends Element
{
    use BaseSproutTrait;

    public $id;

    public $name;

    public $hasNameFormat;

    public $nameFormat;

    public $handle;

    public $description;

    public $allowHtml;

    public $settings;

    public $pluginId;

    public $dataSourceId;

    public $dataSourceSlug;

    public $enabled;

    public $groupId;

    public $dateCreated;

    public $dateUpdated;

    public $results;

    public function __toString()
    {
        if ($this->hasNameFormat && $this->nameFormat) {
            return (string)$this->processNameFormat();
        }

        return (string)$this->name;
    }

    /**
     * Returns the element type name.
     *
     * @return string
     */
    public static function displayName(): string
    {
        return Craft::t('sprout-reports', 'Reports (Sprout)');
    }

    /**
     * Returns whether this element type has titles.
     *
     * @return bool
     */
    public static function hasTitles(): bool
    {
        return false;
    }

    /**
     * Returns whether this element type has content.
     *
     * @return bool
     */
    public static function hasContent(): bool
    {
        return false;
    }

    /**
     * @inheritDoc IElementType::hasStatuses()
     *
     * @return bool
     */
    public static function hasStatuses(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public static function isLocalized(): bool
    {
        return false;
    }

    /**
     * Returns whether the current user can edit the element.
     *
     * @return bool
     */
    public function getIsEditable(): bool
    {
        return true;
    }

    /**
     * Returns the element's CP edit URL.
     *
     * @return string|false
     */
    public function getCpEditUrl()
    {
        $dataSource = SproutBase::$app->dataSources->getDataSourceById($this->dataSourceId);

        return UrlHelper::cpurl('sprout-reports/reports/'.$this->dataSourceId.'-'.$dataSource->getDataSourceSlug().'/edit/'.$this->id);
    }

    /**
     * @inheritdoc
     *
     * @return ReportQuery The newly created [[RedirectQuery]] instance.
     */
    public static function find(): ElementQueryInterface
    {
        return new ReportQuery(get_called_class());
    }

    /**
     * Returns the attributes that can be shown/sorted by in table views.
     *
     * @param string|null $source
     *
     * @return array
     */
    public static function defineTableAttributes($source = null): array
    {
        return [
            'name' => Craft::t('sprout-reports', 'Name'),
            'dataSourceId' => Craft::t('sprout-reports', 'Data Source'),
            'download' => '',
            'results' => '',
        ];
    }

    public function getTableAttributeHtml(string $attribute): string
    {
        if ($attribute === 'dataSourceId') {

            $dataSource = SproutBase::$app->dataSources->getDataSourceById($this->dataSourceId);

            return $dataSource->getName();
        }

        if ($attribute === 'download') {
            return '<a href="'.UrlHelper::actionUrl('sprout-base/reports/export-report', ['reportId' => $this->id]).'" class="btn small">'.Craft::t('sprout-base', 'Download').'</a>';
        }

        if ($attribute === 'results') {

            $pluginHandle = $this->getPlugin()->getHandle();

            $resultsUrl = UrlHelper::cpUrl($pluginHandle.'/reports/view/'.$this->id);

            $reportsIconHtml = file_get_contents(Craft::getAlias('@sproutbase/web/assets/sproutreports/dist/images/icon-mask.svg'));

//              a tag svg styles
//              width: 12px;
//              fill: #aaa;
//              vertical-align: baseline;
            return '<a href="'.$resultsUrl.'" class="btn small">' . $reportsIconHtml . Craft::t('sprout-reports', 'Run Report') .'</a>';
        }

        return parent::getTableAttributeHtml($attribute);
    }

    /**
     * Returns the attributes that can be sorted by in table views.
     *
     * @param string|null $source
     *
     * @return array
     */
    public function defineSortableAttributes($source = null)
    {
        return [
            'name' => Craft::t('sprout-reports', 'Name'),
            'dataSourceId' => Craft::t('sprout-reports', 'Data Source')
        ];
    }

    /**
     * @inheritdoc
     */
    protected static function defineSortOptions(): array
    {
        $attributes = [
            'name' => Craft::t('sprout-reports', 'Name'),
            'dataSourceId' => Craft::t('sprout-reports', 'Data Source')
        ];

        return $attributes;
    }

    /**
     * @inheritdoc
     */
    protected static function defineSources(string $context = null): array
    {
        $sources = [
            [
                'key' => '*',
                'label' => Craft::t('sprout-reports', 'All reports')
            ]
        ];

        $dataSources = SproutBase::$app->dataSources->getDataSourcePlugins();

        foreach ($dataSources as $dataSource) {

            $plugin = Craft::$app->plugins->getPlugin($dataSource['pluginId']);

            $key = 'pluginId:'.$plugin->getHandle();
            $sources[] = [
                'key' => $key,
                'label' => $plugin->name,
                'criteria' => ['pluginId' => $plugin->getHandle()],
            ];
        }

        return $sources;
    }

    public static function defineSearchableAttributes(): array
    {
        return ['name'];
    }

    /**
     * @return BaseDataSource
     * @throws \yii\base\Exception
     */
    public function getDataSource()
    {
        $dataSource = SproutBase::$app->dataSources->getDataSourceById($this->dataSourceId);

        if ($dataSource === null) {
            return null;
        }

        $dataSource->setReport($this);

        return $dataSource;
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function processNameFormat()
    {
        $dataSource = $this->getDataSource();
        $settingsArray = Json::decode($this->settings);

        $settings = $dataSource->prepSettings($settingsArray);

        return Craft::$app->getView()->renderObjectTemplate($this->nameFormat, $settings);
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        $settings = $this->settings;

        if (is_string($this->settings)) {
            $settings = json_decode($this->settings, true);
        }

        return $settings;
    }

    /**
     * Returns a user supplied setting if it exists or $default otherwise
     *
     * @param string     $name
     * @param null|mixed $default
     *
     * @return null
     */
    public function getSetting($name, $default = null)
    {
        $settings = $this->getSettings();

        if (isset($settings[$name])) {

            return $settings[$name];
        }

        return $default;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'handle'], 'required'],
            [['handle'], HandleValidator::class, 'reservedWords' => ['id', 'dateCreated', 'dateUpdated', 'uid', 'title']],
            [['name', 'handle'], UniqueValidator::class, 'targetClass' => ReportRecord::class]
        ];
    }


    /**
     * @param array $results
     */
    public function setResults(array $results = [])
    {
        $this->results = $results;
    }

    /**
     * @param string $message
     */
    public function setResultsError($message)
    {
        $this->addError('results', $message);
    }

    /**
     * @param bool $isNew
     *
     * @throws \InvalidArgumentException
     */
    public function afterSave(bool $isNew)
    {
        if (!$isNew) {
            $reportRecord = ReportRecord::findOne($this->id);

            if (!$reportRecord) {
                throw new \InvalidArgumentException('Invalid Report ID: '.$this->id);
            }
        } else {
            $reportRecord = new ReportRecord();
            $reportRecord->id = $this->id;
        }

        $reportRecord->dataSourceId = $this->dataSourceId;
        $reportRecord->groupId = $this->groupId;
        $reportRecord->name = $this->name;
        $reportRecord->hasNameFormat = $this->hasNameFormat;
        $reportRecord->nameFormat = $this->nameFormat;
        $reportRecord->handle = $this->handle;
        $reportRecord->description = $this->description;
        $reportRecord->allowHtml = $this->allowHtml;
        $reportRecord->settings = $this->settings;
        $reportRecord->enabled = $this->enabled;
        $reportRecord->save(false);

        parent::afterSave($isNew);
    }
}
