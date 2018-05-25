<?php

class ReportsController extends BaseController {
    /*
     * Muestra la pantalla de reportes
     */

    public function getIndex($type = NULL, $interval = 'month_to_date') {        
        return View::make('reports.index', ['type' => $type, 'interval' => $interval]);
    }
    /*
     * Muestra la pantalla de reportes imonomy 
     */

    public function getImonomyIndex($type = NULL, $interval = 'month_to_date') {        
        return View::make('reports.index_imonomy', ['type' => $type, 'interval' => $interval]);
    }

    /*
     * Exporta un reporte
     */

    public function getExport($type = NULL, $interval = 'month_to_date', $format = NULL) {
        $api = new Api();
        $reports = $api->getReport($type, $interval, Session::get('adserver.id'));
        $dates = getDatetimeByInterval($interval);

        if ($format === 'excel') {
            header("Content-type: application/vnd.ms-excel; name='excel'; charset=utf-8");
            header("Content-Disposition: attachment; filename=\"" . Session::get('platform.name') . "_report_by_" . $type . "_period_" . $interval . ".xls\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            return View::make('reports.export', ['type' => $type, 'reports' => $reports, 'start_date' => $dates['start_date'], 'end_date' => $dates['end_date']]);
        }

        if ($format === 'pdf') {
            $html = View::make('reports.export', ['type' => $type, 'reports' => $reports, 'start_date' => $dates['start_date'], 'end_date' => $dates['end_date']]);
            return PDF::load($html, 'A4', 'portrait')->download(Session::get('platform.name') . '_report_by_' . $type . '_period_' . $interval);
        }
    }
    
    /*
     * Exporta un reporte Imonomy
     */

    public function getExportImonomy($type = NULL, $interval = 'month_to_date', $format = NULL) {
        $reports = Imonomy::getReport($type, $interval);
        $dates = getDatetimeByInterval($interval);

        if ($format === 'excel') {
            header("Content-type: application/vnd.ms-excel; name='excel'; charset=utf-8");
            header("Content-Disposition: attachment; filename=\"" . Session::get('platform.name') . "_report_by_" . $type . "_period_" . $interval . ".xls\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            return View::make('reports.export_imonomy', ['type' => $type, 'reports' => $reports, 'start_date' => $dates['start_date'], 'end_date' => $dates['end_date']]);
        }

        if ($format === 'pdf') {
            $html = View::make('reports.export_imonomy', ['type' => $type, 'reports' => $reports, 'start_date' => $dates['start_date'], 'end_date' => $dates['end_date']]);
            return PDF::load($html, 'A4', 'portrait')->download(Session::get('platform.name') . '_report_by_' . $type . '_period_' . $interval);
        }
    }

    /*
     * Obtiene el grafico segun el intervalo solicitado
     */

    public function getGraph($interval, $group) {
        $api = new Api();
        $reports = $api->getDataGraph($interval, $group, Session::get('adserver.id'));

        return View::make('reports.graph', ['reports' => $reports]);
    }
    
    /*
     * Obtiene el mapa segun el intervalo solicitado
     */

    public function getGraphMap($interval) {
        $api = new Api();
        $reports = $api->getDataGraphMap($interval, Session::get('adserver.id'));

        return View::make('reports.graph_map', ['reports' => $reports]);
    }

    /*
     * genera la tabla de los reportes a mostrar
     */

    public function getTable($type = NULL, $interval = 'month_to_date') {
        $reports = Api::getReport($type, $interval, Session::get('adserver.id'));

        if (!$reports["report"])
            return View::make('reports.no_data', ['type' => $type, 'reports' => $reports, 'interval' => $interval]);

        return View::make('tables.tbl_reportes', ['type' => $type, 'reports' => $reports, 'interval' => $interval]);
    }
    
    /*
     * genera la tabla de los reportes de Imonomy a mostrar
     */

    public function getImonomyTable($type = NULL, $interval = 'month_to_date') {
        $reports = Imonomy::getReport($type, $interval);

        if (!$reports["report"])
            return View::make('reports.no_data', ['type' => $type, 'reports' => $reports, 'interval' => $interval]);

        return View::make('tables.tbl_reportes_imonomy', ['type' => $type, 'reports' => $reports, 'interval' => $interval]);
    }

}