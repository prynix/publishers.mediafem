<?php

class TestApiController extends BaseController {

    public function getReport() {

        $interval = getDatetimeByInterval('month_to_date');

        $data['publisher_id'] = 21;
        $data['start_date'] = $interval['start_date'];
        $data['end_date'] = $interval['end_date'];

        /*
         * Agrupar por:
         * day
         * site_adserver_id
         * placement_adserver_id
         */

        $data['group_by'] = 'day';

        $data['columns'] = Config::get('groupby.' . $data['group_by']);

        $data['columns'][] = 'imps';
        $data['columns'][] = 'clicks';
        $data['columns'][] = 'revenue';

        $report = InventoryYax::getReport($data);

        echo "Reporte:<br/><br/>";

        foreach ($report as $row) {

            for ($i = 0; $i < sizeof($data['columns']); $i++) {
                $key = $data['columns'][$i];

                $value = $row->$key;

                echo $value . " - ";
            }
            echo "<br/>";
        }
    }

    public function newCategory() {
        /*
        $categorias[] = 'Women';
        $categorias[] = 'SaludyFitness';
        $categorias[] = 'RedesSociales';
        $categorias[] = 'ModayShopping';
        $categorias[] = 'Mascotas';
        $categorias[] = 'MamayBebe';
        $categorias[] = 'Juegos';
        $categorias[] = 'HogaryJardin';
        $categorias[] = 'Glam';
        $categorias[] = 'Espectaculos';
        $categorias[] = 'Entretenimiento';
        $categorias[] = 'EjecutivasyProfesionales';
        $categorias[] = 'Educacion';
        $categorias[] = 'Cocina';
        $categorias[] = 'BodasyFestejos';
        $categorias[] = 'BellezayCosmetica';
        $categorias[] = 'AmoryPareja';
        */
        $categorias[] = 'Replicas';
        for ($i = 0; $i < sizeof($categorias); $i++) {
            $name = $categorias[$i];

            echo $name . " - ";

            $category = new Category();
            $category->setName($name);
            $adunit = Api::newCategory(3, $category);
            
            echo $adunit->id;
            echo "<br/>";
            
        }
    }

}
