<?php

/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */

namespace Piwik\Plugins\Hours;

use Piwik\View;

/**
 *
 */
class Controller extends \Piwik\Plugin\Controller {

    public function index() {
        $view = new View("@Hours/index.twig");
        $view->getLastVisitsByTime = $this->getLastVisitsByTime();
        echo $view->render();
    }

    public function getLastVisitsByTime() {
        $view = \Piwik\ViewDataTable\Factory::build(
                        $defaultVisualization = 'table', $apiAction = 'Hours.getLastVisitsByTime'
        );
        $view->config->show_table_all_columns = false;
        $view->config->show_goals = false;
        $view->config->translations['label'] = 'Times';
        return $view->render();
    }
}
