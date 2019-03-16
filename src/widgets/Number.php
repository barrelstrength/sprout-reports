<?php

namespace barrelstrength\sproutreports\widgets;

use barrelstrength\sproutbasereports\SproutBaseReports;
use Craft;
use craft\base\Widget;

/**
 *
 * @property mixed  $bodyHtml
 * @property mixed  $settingsHtml
 * @property string $title
 */
class Number extends Widget
{
    /**
     * @var string
     */
    public $heading;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $number;

    public $resultPrefix;

    public $reportId;

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('sprout-reports', 'Number');
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        return $this->heading;
    }

    /**
     * @inheritdoc
     */
    public static function iconPath()
    {
        return Craft::getAlias('@barrelstrength/sproutreports/icon-mask.svg');
    }

    /**
     * @inheritdoc
     *
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getSettingsHtml()
    {
        $reportOptions = SproutBaseReports::$app->reports->getReportsAsSelectFieldOptions();

        return Craft::$app->getView()->renderTemplate('sprout-base-reports/_components/widgets/Number/settings', [
                'widget' => $this,
                'reportOptions' => $reportOptions
            ]
        );
    }

    /**
     * @inheritdoc
     *
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getBodyHtml()
    {
        $report = SproutBaseReports::$app->reports->getReport($this->reportId);

        if ($report) {
            $dataSource = SproutBaseReports::$app->dataSources->getDataSourceById($report->dataSourceId);

            if ($dataSource) {
                $result = $dataSource->getResults($report);

                return Craft::$app->getView()->renderTemplate('sprout-base-reports/_components/widgets/Number/body',
                    [
                        'widget' => $this,
                        'result' => $this->getScalarValue($result)
                    ]
                );
            }
        }

        return Craft::$app->getView()->renderTemplate('sprout-base-reports/_components/widgets/Number/body',
            [
                'widget' => $this,
                'result' => Craft::t('sprout-reports', 'NaN')
            ]);
    }

    /**
     * @param $result
     *
     * @return int|mixed|null
     */
    protected function getScalarValue($result)
    {
        $value = null;

        if (is_array($result)) {

            if (count($result) == 1 && isset($result[0]) && count($result[0]) == 1) {
                $value = array_shift($result[0]);
            } else {
                $value = count($result);
            }
        } else {
            $value = $result;
        }

        return $value;
    }
}
