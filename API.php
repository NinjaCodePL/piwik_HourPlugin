<?php

/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */

namespace Piwik\Plugins\Hours;

use Piwik\Archive;
use Piwik\Metrics;
use Piwik\Piwik;

/**
 * @see plugins/UserSettings/functions.php
 */
require_once PIWIK_INCLUDE_PATH . '/plugins/UserSettings/functions.php';

/**
 * API for plugin Hours
 *
 * @method static \Piwik\Plugins\Hours\API getInstance()
 */
class API extends \Piwik\Plugin\API {

    protected function getDataTable($name, $idSite, $period, $date, $segment) {
        Piwik::checkUserHasViewAccess($idSite);
        $archive = Archive::build($idSite, $period, $date, $segment);
        $dataTable = $archive->getDataTable($name);
         $dataTable->filter('Sort', array(Metrics::INDEX_NB_VISITS));
          $dataTable->queueFilter('ReplaceColumnNames');
          $dataTable->queueFilter('ReplaceSummaryRowLabel'); 
        return $dataTable;
    }

    public function getTime($idSite, $period, $date, $segment = false) {
        $dataTable = $this->getDataTable(Archiver::CONFIGURATION_RECORD_NAME, $idSite, $period, $date, $segment);
        return $dataTable;
    }

}
