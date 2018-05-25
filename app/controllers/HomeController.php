<?php

class HomeController extends BaseController {
    /*
     * Muestra la pantalla de inicio de la herramienta
     */


    public function getIndex() {
        $totalReport = new stdClass();
        $totalReport->report = new stdClass();
        $totalReport->report->imps = 0;
        $totalReport->report->clicks = 0;
        $totalReport->report->ctr = 0;
        $totalReport->report->cpm = 0;
        $totalReport->report->revenue = 0;
        $publisher = Publisher::find(Session::get('publisher.id'));
        $adservers = Adserver::all();
        foreach ($adservers as $adserver) {
            if (($adserver->getId() == 4) || ($adserver->getId() == 1)) {
                continue;
            }
            $report = Api::getReport('home', 'month_to_date', $adserver->getId());
            if ($report['report'][0]) {
                  $totalReport->report->imps += $report['report'][0]->imps;
                  $totalReport->report->clicks += $report['report'][0]->clicks;
                  $totalReport->report->revenue += $report['report'][0]->revenue;
            }
            $report = NULL;
        }
        //(clicks)/SUM(imps)*100 = ctr
        if ($totalReport->report->imps != 0)
            $totalReport->report->ctr = ($totalReport->report->clicks / $totalReport->report->imps) * 100;
        else
            $totalReport->report->ctr = 0;
        //(revenue)/SUM(imps)*1000 = cpm
        if ($totalReport->report->imps != 0)
            $totalReport->report->cpm = ($totalReport->report->revenue / $totalReport->report->imps) * 1000;
        else
            $totalReport->report->cpm = 0;

        //Imonomy Revenue
        $imonomyRevenue = Imonomy::getRevenueByDate('month_to_date');
        
        return View::make('home.index', ['report' => $totalReport->report, 'imonomyRevenue' => $imonomyRevenue]);
    }

    /*
     * Obtiene el revenue de un publisher segun la fecha solicitada
     */

    public function getRevenueByDate($interval = 'month_to_date') {
        $publisher = Publisher::find(Session::get('publisher.id'));
        $adservers = Adserver::all();
        $report = 0;
        foreach ($adservers as $adserver) {
            $report += Api::getRevenueByDate($interval, $adserver->getId());
        }
        //var_dump($report);
        $report += Imonomy::getRevenueByDate($interval);
        //dd($report);
        return number_format($report, 2, '.', ',');
    }

}
