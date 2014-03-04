<?php

/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */

namespace Piwik\Plugins\Hours;

use Piwik\DataAccess\LogAggregator;
use Piwik\DataTable;
use Piwik\Metrics;

require_once PIWIK_INCLUDE_PATH . '/plugins/UserSettings/functions.php';

/**
 * Archiver for UserSettings Plugin
 *
 * @see PluginsArchiver
 */
class Archiver extends \Piwik\Plugin\Archiver {

    const HOURS_RECORD_NAME = 'Hours_even';
    const PLUGIN_RECORD_NAME = 'Hours_plugin';
    const HOURS_DIMENSION = "log_visit.hour_even";
    const CONFIGURATION_RECORD_NAME = 'Hours_even';

    public function aggregateDayReport() {
        $this->aggregateByHour();
    }

    public function aggregateMultipleReports() {
        $dataTableRecords = array(
            self::CONFIGURATION_RECORD_NAME
        );
        $this->getProcessor()->aggregateDataTableRecords($dataTableRecords, $this->maximumRows);
    }

    protected function aggregateByHour() {
        $selects = array(
            "sum(case log_visit.hour_even when 1 then 1 else 0 end) as even",
            "sum(case log_visit.hour_even when 0 then 1 else 0 end) as uneven"
        );
        $query = $this->getLogAggregator()->queryVisitsByDimension(array(), false, $selects, $metrics = array());
        $data = $query->fetch();
        $cleanRow = LogAggregator::makeArrayOneColumn($data, Metrics::INDEX_NB_VISITS);
        $table = DataTable::makeFromIndexedArray($cleanRow);
        $this->insertTable(self::CONFIGURATION_RECORD_NAME, $table);
    }

    protected function insertTable($recordName, DataTable $table) {
        $report = $table->getSerialized($this->maximumRows, null, Metrics::INDEX_NB_VISITS);
        return $this->getProcessor()->insertBlobRecord($recordName, $report);
    }

}

