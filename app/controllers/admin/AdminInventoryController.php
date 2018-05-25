<?php

set_time_limit(0);
ini_set('post_max_size', '99999M');
ini_set('upload_max_filesize', '999999M');
ini_set('memory_limit', '999999M');
ini_set('max_execution_time', '99999');
ini_set('max_input_time', '99999');

class AdminInventoryController extends BaseController {
    /*
     * Muestra la pantalla de mi cuenta
     */

    public function getIndex($type = NULL, $interval = 'month_to_date') {
        return View::make('admin.inventory.index', ['type' => $type, 'interval' => $interval]);
    }

    /*
     * Exporta un reporte
     */

    /* public function getExport($type = NULL, $interval = 'month_to_date', $format = NULL) {
      $api = new Api();
      $reports = $api->getReport($type, $interval, Session::get('adserver.id'));
      $dates = getDatetimeByInterval($interval);

      if ($format === 'excel') {
      header("Content-type: application/vnd.ms-excel; name='excel'; charset=utf-8");
      header("Content-Disposition: attachment; filename=\"adtomatik_report_by_" . $type . "_period_" . $interval . ".xls\"");
      header("Pragma: no-cache");
      header("Expires: 0");
      return View::make('reports.export', ['type' => $type, 'reports' => $reports, 'start_date' => $dates['start_date'], 'end_date' => $dates['end_date']]);
      }

      if ($format === 'pdf') {
      $html = View::make('reports.export', ['type' => $type, 'reports' => $reports, 'start_date' => $dates['start_date'], 'end_date' => $dates['end_date']]);
      return PDF::load($html, 'A4', 'portrait')->download('adtomatik_report_by_' . $type . '_period_' . $interval);
      }
      } */

    /*
     * Obtiene el grafico segun el intervalo solicitado
     */

    /* public function getGraph($interval, $group) {
      $api = new Api();
      $reports = $api->getDataGraph($interval, $group, 1);

      return View::make('admin.inventory.graph', ['reports' => $reports]);
      } */

    /*
     * Obtiene el mapa segun el intervalo solicitado
     */

    /* public function getGraphMap($interval) {
      $api = new Api();
      $reports = $api->getDataGraphMap($interval, 1);

      return View::make('admin.inventory.graph_map', ['reports' => $reports]);
      } */

    /*
     * genera la tabla de los reportes a mostrar
     */

    public function getTable($type = NULL, $interval = 'month_to_date') {
        try {
            $interval = getDatetimeByInterval($interval);
            if ($type == 'site_name') {
                $report = InventoryAdmin::getReportAllSites($interval);
            } elseif ($type == 'country') {
                $report = InventoryAdmin::getReportAllCountries($interval);
            } elseif ($type == 'publisher_name') {
                $report = InventoryAdmin::getReportAllPublishers($interval);
            }
        } catch (Exception $ex) {
            return View::make('admin.inventory.no_data');
        }
        if (!$report)
            return View::make('admin.inventory.no_data');

        return View::make('admin.tables.tbl_inventory', ['report' => $report['report'], 'totals' => $report['totals'], 'type' => $type]);
    }

}
