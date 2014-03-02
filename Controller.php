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
        $view->getLastVisitsByTime = $this->getLastVisitsByTime(true);
        echo $view->render();
    }

    public function getLastVisitsByTime() {
        return $this->renderReport(__FUNCTION__);
    }
}
