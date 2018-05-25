<?php

class Forms extends Illuminate\Html\FormBuilder {

    public static function formGroup($label = array(), $text = array()) {
        if (!isset($text['value']))
            $text['value'] = Input::old($text['name']);

        if (!isset($label['col']))
            $label['col'] = 3;

        if (!isset($text['col']))
            $text['col'] = 8;

        if (!isset($text['placeholder']))
            $text['placeholder'] = '';


        $error = $hasError = FALSE;

        if (Session::get('error')) {
            if (Session::get('error')->first($text['name'])) {
                $error = Session::get('error')->first($text['name']);
                $hasError = ' has-error';
            }
        }

        $html = '<div id="' . $text['name'] . '" class="form-group' . $hasError . '">';
        $html .= '<label for="ID_' . $text['name'] . '" class="col-sm-' . $label['col'] . ' control-label">' . $label['text'] . ':</label>';
        $html .= '<div class="col-sm-' . $text['col'] . '">';
        $html .= '<input type="' . $text['type'] . '" name="' . $text['name'] . '" id="ID_' . $text['name'] . '" value="' . $text['value'] . '" placeholder="' . $text['placeholder'] . '" class="form-control" />';
        $html .= '<span class="help-block">' . $error . '</span>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public static function formGroupV($label = array(), $text = array()) {
        if (!isset($text['value']))
            $text['value'] = Input::old($text['name']);

        if (!isset($text['placeholder']))
            $text['placeholder'] = '';

        $error = $hasError = FALSE;

        if (Session::get('error')) {
            if (Session::get('error')->first($text['name'])) {
                $error = Session::get('error')->first($text['name']);
                $hasError = ' has-error';
            }
        }

        $html = '<div id="' . $text['name'] . '" class="form-group' . $hasError . '">';
        $html .= '<label for="ID_' . $text['name'] . '" class="control-label">' . $label['text'] . ':</label>';
        $html .= '<input type="' . $text['type'] . '" name="' . $text['name'] . '" id="ID_' . $text['name'] . '" value="' . $text['value'] . '" placeholder="' . $text['placeholder'] . '" class="form-control" />';
        $html .= '<span class="help-block">' . $error . '</span>';
        $html .= '</div>';

        return $html;
    }

    public static function exportButton($url) {
        $html = '<a href="' . $url . '" class="btn btn-success"><i class="fa fa-file-excel-o"></i> ' . Lang::get('admin.export_excel') . '</a>';
        return $html;
    }

    public static function filters($filters) {
        $html = '<div class="panel panel-success">';
        $html .= '<div class="panel-heading" id="filtrosHead" style="cursor: pointer"><h4><i class="fa fa-plus-square-o"></i> ' . Lang::get('admin.filters') . '</h4></div>';
        $html .= '<div class="panel-body" id="filtros" hidden="true">';
        foreach ($filters as $key => $filter) {
            $html .= '<div class="btn btn-default btn-marginR20">'.$filter[0];
            $html .= '<span id="'.$filter[1].'"></span></div>';
        }
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

}
