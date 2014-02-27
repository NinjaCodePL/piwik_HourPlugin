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

/**
 */
class Hours extends \Piwik\Plugin {

    public function getListHooksRegistered() {
        return array(
            'AssetManager.getJavaScriptFiles' => 'getJsFiles',
            'Menu.Reporting.addItems' => 'getReportingMenuItems',
            'WidgetsList.addWidgets' => 'addWidgets'
        );
    }

    public function addWidgets() {
        WidgetsList::add('Visitors', 'Even/Uneven times', 'Hours', 'getLastVisitsByTime');
    }

    public function getReportingMenuItems() {
        \Piwik\Menu\MenuMain::getInstance()->add(
                $category = 'General_Visitors', $title = 'Hours Reporting', $urlParams = array('module' => $this->getPluginName(), 'action' => 'index')
        );
    }

    public function getJsFiles(&$jsFiles) {
        $jsFiles[] = 'plugins/Hours/javascripts/plugin.js';
    }

}
