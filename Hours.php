<?php

/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */

namespace Piwik\Plugins\Hours;

use Piwik\WidgetsList;
use Piwik\DataTable;

/**
 */
class Hours extends \Piwik\Plugin {

    public function getListHooksRegistered() {
        return array(
            'AssetManager.getJavaScriptFiles' => 'getJsFiles',
            'Menu.Reporting.addItems' => 'getReportingMenuItems',
            'WidgetsList.addWidgets' => 'addWidgets',
            'Tracker.newVisitorInformation' => 'addVisitorHourInformation'
        );
    }

    //Dodaję kolumnę oznaczającą w jakiej godzinie (parzysta lub nie) 
    //użytkownik został zanotowany w bazie w momencie instalacji plugina
    public function install() {
        $query = "ALTER IGNORE TABLE `" . Common::prefixTable('log_visit') . "` ADD `hour_even` INT(1) NULL";
        try {
            Db::exec($query);
        } catch (Exception $e) {
            if (!Db::get()->isErrNo($e, '1060')) {
                throw $e;
            }
        }
    }
    //Usuwam kolumnę oznaczającą w jakiej godzinie (parzysta lub nie) 
    //użytkownik został zanotowany w bazie w momencie usunięcia plugina
    public function uninstall(){
        $query = "ALTER IGNORE TABLE `" . Common::prefixTable('log_visit') . "` DROP CLOUMN `hour_even`";
        try {
            Db::exec($query);
        } catch (Exception $e) {
            if (!Db::get()->isErrNo($e, '1060')) {
                throw $e;
            }
        }
    }

    /*
     * W momencie odwiedzin sprawdzam, czy godzina jest parzysta czy nie 
     * i zapisuję informacje w tabeli.
     * 1 - parzyste
     * 0 - nieparzyste
     */

    public function addVisitorHourInformation(&$visitorInfo, \Piwik\Tracker\Request $request) {
        $time = explode(':', $visitorInfo['visitor_localtime']);
        if ($time[0] % 2 == 0) {
            $visitorInfo['hour_even'] = 1;
        } else {
            $visitorInfo['hour_even'] = 0;
        }
    }

    public function addWidgets() {
        WidgetsList::add('Visitors', 'Even/Uneven times', 'Hours', 'getLastVisitsByTime');
    }

    public function getReportingMenuItems() {
        \Piwik\Menu\MenuMain::getInstance()->add(
                $category = 'General_Visitors', $title = 'Hours Reporting', $urlParams = array('module' => $this->getPluginName(),
            'action' => 'index')
        );
    }

    public function getJsFiles(&$jsFiles) {
        $jsFiles[] = 'plugins/Hours/javascripts/plugin.js';
    }

}
