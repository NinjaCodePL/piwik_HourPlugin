<?php

/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */

namespace Piwik\Plugins\Hours;

/**
 * API for plugin Hours
 *
 * @method static \Piwik\Plugins\Hours\API getInstance()
 */
class API extends \Piwik\Plugin\API {

    public function getLastVisitsByTime($idSite, $period, $date, $segment = false) {
        //Pobieram dane na temat odwiedzających
        $data = \Piwik\Plugins\Live\API::getInstance()->getLastVisitsDetails(
                $idSite, $period, $date, $segment, $minTimestamp = false, $flat = false, $doNotFetchActions = true
        );
        $result = $data->getEmptyClone($keepFilters = false);
        //Ustawiam zmienne zliczające godziny parzyste/nieparzyste
        $even = 0;
        $uneven = 0;

        foreach ($data->getRows() as $visitRow) {
            //Pobieram czas w jakim dokonane zostało odpowiedzenie strony
            //w formacie 00:00:00
            $visitorTime = $visitRow->getColumn('visitor_localtime');
            //Wyciągam godzinę $time[0]
            $time = explode(":", $visitorTime);
            //Jeśli godzina jest parzysta inkrementuję zmienną $even
            //w innym wypadku zmienną $uneven
            if ($time[0] % 2 == 0) {
                $even++;
            } else {
                $uneven++;
            }
        }
        //Dodaję rekordy do rezultatu
        $result->addRowFromSimpleArray(array(
            'label' => "Even",
            'nb_visits' => $even
        ));
        $result->addRowFromSimpleArray(array(
            'label' => "Uneven",
            'nb_visits' => $uneven
        ));
        //Zwracam rezultat
        return $result;
    }

}
