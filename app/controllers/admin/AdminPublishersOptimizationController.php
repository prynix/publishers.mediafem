<?php

class AdminPublishersOptimizationController extends BaseController {
    /*
     * Muestra la pantalla de mis pagos
     */

    public function getIndex() {
        $report = OptimizedPublisher::getRangeHistoryByPublisher(date('Y-m-d'), date('Y-m-d'));
        $dates = PublisherOptimization::getAllDates();
        $datesDfp = PublisherOptimizationDfp::getAllDates();
        $emails = Constant::value('optimization_monitors');
        $emails = str_replace('&', ' - ', $emails);
        return View::make('admin.publishersOptimization.index', ['history' => $report, 'days' => $dates, 'daysDfp' => $datesDfp, 'emails' => $emails]);
    }

    public function loadPayemtRuleTable() {
        $publishers = PaymentRule::getGroupByPublisher();
        return View::make('admin.tables.tbl_paymentRulePublisher', ['paymentRules' => $publishers]);
    }

    public function loadPayemtRuleTableByPublisher($id) {
        $publisher = Publisher::find($id);
        foreach ($publisher->getAllPlacements() as $placement) {
            $placements[] = $placement->getId();
        }
        if (count($placements) > 0) {
            $rules = PaymentRule::whereIn('placement_id', $placements)->get();
        }
        return View::make('admin.tables.tbl_paymentRulePlacement', ['paymentRules' => $rules]);
    }

    public function loadOptimizedPublisherTable($publisherId, $date = NULL) {
        if ($date) {
            $range = explode('-to-', $date);
            $report = OptimizedPublisher::getPublisherSites($publisherId, $range[0], $range[1]);
        } else {
            $report = OptimizedPublisher::getPublisherSites($publisherId);
        }
        return View::make('admin.tables.tbl_optimizedPlacementsHistory', ['placements' => $report, 'publisherId' => $publisherId]);
    }

    public function loadPublishersOptimizationTable($date = NULL) {
        if (is_null($date)) {
            $date = date('Y-m-d');
        }
        $report = PublisherOptimization::where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($date)))
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($date)))
                ->get();
        return View::make('admin.tables.tbl_publishersOptimization', ['report' => $report]);
    }

    public function loadPublishersDfpOptimizationTable($date = NULL) {
        $report = PublisherOptimizationDfp::all();
        $lastReport = array();
        foreach ($report as $row) {
            if ($row->isSameDate($date)) {
                $lastReport[] = $row;
            }
        }
        return View::make('admin.tables.tbl_publishersOptimizationDfp', ['report' => $lastReport]);
    }

    public function loadPublishersOptimizationHistoryTable() {
        $report = OptimizedPublisher::all();
        $history = array();
        foreach ($report as $key => $value) {
            if ($this->historyAlreadyHasPublisher($history, $value->publisher_id))
                continue;
            else
                $history[] = $value;
        }
        //dd($history);
        return View::make('admin.tables.tbl_optimizedPublisherHistory', ['history' => $history]);
    }

    private function historyAlreadyHasPublisher($array, $publisherId) {
        foreach ($array as $value) {
            if ($value->publisher_id == $publisherId)
                return TRUE;
        }
        return FALSE;
    }

    public function getOptimization($id) {
        $optimization = PublisherOptimization::find($id);
        $optimized = OptimizedPublisher::getByPlacementCountryLastOptimization($optimization->placement_id, $optimization->country_id);
        return View::make('admin.publishersOptimization.show', ['optimization' => $optimization, 'optimized' => $optimized]);
    }

    public function getOptimizationDfp($id, $type) {
        $optimization = PublisherOptimizationDfp::find($id);
        $optimized = OptimizedPublisher::getByPlacementCountryTypeLastOptimization($optimization->placement_id, $optimization->country_id, $type);
        return View::make('admin.publishersOptimization.show_dfp', ['optimization' => $optimization, 'optimized' => $optimized]);
    }

    public function getHistoryRange($range) {
        $date = explode('-to-', $range);
        $data = OptimizedPublisher::getRangeHistory($date[0], $date[1]);
        //echo $range;
        return View::make('admin.tables.tbl_optimizedPublisherHistory', ['history' => $data, 'range' => $range]);
    }

    public function optimize($optimizationId) {
        try {
            $pbOpt = PublisherOptimization::find($optimizationId);
            Api::adjustRevShare(2, $pbOpt);
            $pbOpt->optimized = 1;
            $pbOpt->save();
            $optimized = OptimizedPublisher::getPublisher($pbOpt->getPublisherId());
            if ($optimized) {
                $optimized->new_share = $pbOpt->getPublisherNewShare();
                $optimized->optimized_date = date("Y-m-d");
                $optimized->save();
            } else {
                $optimized = new OptimizedPublisher();
                $optimized->publisher_id = $pbOpt->getPublisherId();
                $optimized->new_share = $pbOpt->getPublisherNewShare();
                $optimized->optimized_date = date("Y-m-d");
                $optimized->save();
            }
            return View::make('admin.publishersOptimization.index');
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => $ex->getMessage()]);
        }
    }

}
