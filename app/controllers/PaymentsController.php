<?php

class PaymentsController extends BaseController {
    /*
     * Muestra la pantalla de mis pagos
     */

    public function getIndex() {
        Session::forget('error');
        try{
            $earnings = Publisher::find(Session::get('publisher.id'))->earnings;
            $billings = Billing::ofPublisher(Session::get('publisher.id'));
            $payments = Payment::ofPublisher(Session::get('publisher.id'));

            $records = self::_mergeEarningsWithPayments($earnings, $payments);
        }  catch (Exception $ex){
            $records = null;
            $billings = null;
        }
        return View::make('payments.index', ['billings' => $billings, 'records' => $records]);
    }
    
    public function getActualBalance() {        
        $acumulado = 0;
        try{
            $earnings = Publisher::find(Session::get('publisher.id'))->earnings;
            $payments = Payment::ofPublisher(Session::get('publisher.id'));
            $records = self::_mergeEarningsWithPayments($earnings, $payments);

            if($records)
                $acumulado = $records[0]['balance'];
        }catch(Exception $ex){
            $acumulado = 0.00;
        }
        return number_format($acumulado, 2, '.', ',');
    }
    
    private function _mergeEarningsWithPayments($earnings, $payments) {
        $records = array();

        foreach ($earnings as $earning) {
            $records[] = ['date' => date("Y-m-d 00:00:00", strtotime($earning->getPeriod())), 'type' => 'earning', 'record' => $earning, 'balance'=>0];
        }
        
        foreach ($payments as $payment) {
            $records[] = ['date' => date("Y-m-d H:i:s", strtotime($payment->created_at)), 'type' => 'payment', 'record' => $payment, 'balance'=>0];
        }
        usort($records, '_asc');
        self::_calculateBalance($records);
        usort($records, '_desc');
        return $records;
    }

    private function _calculateBalance(&$records) {
       $balance = 0;
        foreach ($records as &$record) {
            if($record['type'] == 'earning')
                $balance += $record['record']->getAmount();
            else
                $balance -= $record['record']->getAmount();
            
            $record['balance'] = $balance;
        }
    }
}

function _asc($a, $b) {
    $t1 = strtotime($a['date']);
    $t2 = strtotime($b['date']);
    return $t1 - $t2;
}

function _desc($a, $b) {
    $t1 = strtotime($a['date']);
    $t2 = strtotime($b['date']);
    return $t2 - $t1;
}
