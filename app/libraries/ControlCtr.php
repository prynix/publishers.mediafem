<?php

class ControlCtr {

    public static function control() {
        try {
            $report = Api::getOrderReport(3, Constant::value('line_item_dfp_id'));
            echo "%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%\n";
            $exclude = array();
            $highCtr = array();
            $max_ctr = Constant::value('maximum_allowed_ctr');
            foreach ($report as $key => $row) {
                if ($row['imps'] > Constant::value('minimum_impressions_to_ctr') && $row['ctr'] >= $max_ctr) {
                    echo "Estado: A excluir!\n";
                    $exclude[$key] = $row;
                } else {
                    if ($row['ctr'] >= $max_ctr) {
                        echo "Estado: CTR alto pero pocas impresiones.\n";
                        $highCtr[$key] = $row;
                    } else {
                        echo"Estado: Controlado.\n";
                    }
                }
                echo "Nombre: " . $row['name'] . ' Id: ' . $key . "\n";
                echo "Imps: " . $row['imps'] . " - Clics: " . $row['clicks'] . " - CTR: " . $row['ctr'] . "%" . "\n";
                echo "___________________________________\n";
            }
            //Exclude Adunits from Line Item
            $result = FALSE;
            try {
                $exclude_ids = array();
                foreach ($exclude as $key => $value) {
                    $exclude_ids[] = $key;
                }
                if(count($exclude_ids)>0)
                    $result = Api::excludeAdunitsFromLineitem(3, Constant::value('line_item_dfp_id'), $exclude_ids);
            } catch (Exception $ex) {
                echo "\nError durante exclusion de AdUnits: " . $ex->getTraceAsString() . "\n";
                $result = FALSE;
            }
            //Send Emails
            $constant = Constant::getValue('control_ctr_monitors');
            $emails = explode("&", $constant->cns_value);
            if (count($exclude) > 0) {
                foreach ($emails as $email) {
                    Mailer::send('emails.alert.controlCtrAdsense', ['error' => !$result,
                        'excluded' => $exclude,
                        'high_ctr' => $highCtr], $email, '', 'Adtomatik: Control de CTR Adsense');
                    echo "Se envio mail a " . $email . "\n";
                }
            } else {
                echo 'Los ad units estan todos con CTR controlado.';
            }
        } catch (Exception $exc) {
            echo "\nError general: " . $exc->getTraceAsString() . "\n";
        }
    }

    public static function processOrderReport($data, $startHour, $endHour) {
        echo "Procesamiento del Reporte:\n";
        $count = 0;
        $report = array();
        echo "Rango de horas: " . $startHour . ":00 hs - " . $endHour . ":00 hs.\n";
        foreach ($data as $row) {
            try {
                if (($startHour <= $row[1]) && ($row[1] <= $endHour)) {
                    if (!isset($report[$row[2]])) {
                        //Nuevo registro
                        $report[$row[2]]['name'] = $row[3];
                        $report[$row[2]]['imps'] = self::getNumber($row[4]);
                        $report[$row[2]]['clicks'] = self::getNumber($row[5]);
                        echo "Ad Unit analizado: " . $row[3] . "\n";
                    } else {
                        //Ya existente
                        $report[$row[2]]['imps'] += self::getNumber($row[4]);
                        $report[$row[2]]['clicks'] += self::getNumber($row[5]);
                    }
                    $report[$row[2]]['ctr'] = self::getCtr($report[$row[2]]['clicks'], $report[$row[2]]['imps']);
                }
                $count++;
            } catch (Exception $exce) {
                echo $exce->getTraceAsString() . "\n";
            }
        }
        return $report;
    }

    public static function getNumber($value) {
        $value = str_replace('"', '', $value);
        $value = str_replace("'", '', $value);
        $value = str_replace(',', '', $value);
        $value = str_replace('$', '', $value);
        $value = str_replace('?', '', $value);
        return $value;
    }

    public static function getCtr($clicks, $imps) {
        if ($clicks > 0) {
            return round($clicks * 100 / $imps, 2);
        } else {
            return 0;
        }
    }

}
