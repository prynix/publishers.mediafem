<?php

set_time_limit(0);
ini_set('post_max_size', '999999999M');
ini_set('upload_max_filesize', '999999999M');
ini_set('memory_limit', '999999999M');
ini_set('max_execution_time', '9999');
ini_set('max_input_time', '9999');
ignore_user_abort();

class AdminPaymentsController extends BaseController {
    /*
     * Muestra la pantalla de mis pagos
     */

    public function getPayments($type = NULL) {
        if ($type == 'affiliate') {
            return View::make('admin.payments.index', ['type' => 'affiliate']);
        }
        return View::make('admin.payments.index', ['type' => 'publisher']);
    }

    public function getMediaBuyerCommissions() {
        return View::make('admin.payments.mediabuyer');
    }

    public function getMediaBuyerCommissionsTable() {
        $commissions = MediaBuyerCommission::all();
        return View::make('admin.tables.tbl_mediaBuyerCommissions', ['commissions' => $commissions]);
    }

    public function getPagosProcesoTodos($type = 'publisher') {
        if ($type == 'affiliate') {
            $imps = NULL;
            $billings = AdminBilling::didNotPaid();
        } else {
            $imps = Publisher::lastWeekImps();
            $billings = Billing::didNotPaid();
        }
        return View::make('admin.payments.pagosProceso', ['allBillings' => $billings, 'imps' => $imps, 'type' => $type]);
    }

    public function getIngresosMensuales($type = 'publisher') {
        if ($type == 'affiliate') {
            $earnings = AdminEarning::greaterThanZero();
        } else {
            $earnings = Earning::greaterThanZero();
        }
        return View::make('admin.payments.ingresosMensuales', ['earnings' => $earnings, 'type' => $type]);
    }

    public function getPagosHistorial($type = 'publisher') {
        if ($type == 'affiliate') {
            $payments = AdminPayment::all();
        } else {
            $payments = Payment::all();
        }
        return View::make('admin.payments.pagosHistorial', ['payments' => $payments, 'type' => $type]);
    }

    public function getPublisherPayments($id, $type = 'publisher') {
        try {
            if ($type == 'publisher') {
                $earnings = Publisher::find($id)->earnings;
                $billings = Billing::ofPublisher($id);
                $payments = Payment::ofPublisher($id);
            } else {
                $earnings = Administrator::find($id)->earnings;
                $billings = AdminBilling::ofAdministrator($id);
                $payments = AdminPayment::ofAdministrator($id);
            }

            $records = self::_mergeEarningsWithPayments($earnings, $payments);
        } catch (Exception $ex) {
            $records = null;
            $billings = null;
        }
        return View::make('admin.publishers.publisherPayments', ['billings' => $billings, 'records' => $records]);
    }

    public function getPublisherBillings($id, $type = 'publisher') {
        try {
            if ($type == 'publisher') {
                $billings = Billing::ofPublisher($id);
            } else {
                $billings = AdminBilling::ofAdministrator($id);
            }
        } catch (Exception $ex) {
            $billings = null;
        }
        return View::make('admin.payments.publisherBillings', ['billings' => $billings, 'itemId' => $id, 'type' => $type]);
    }

    public function deletePayment($payment_id, $type = 'publisher') {
        if ($type == 'publisher')
            $payment = Payment::find($payment_id);
        else
            $payment = AdminPayment::find($payment_id);
        $payment->revertPayment();
        if (Request::ajax())
            return Response::json(['error' => 0, 'messages' => 'El pago revertido Ok!']);
    }

    public function setPayment() {
        try {
            if (Input::get('billingId') == null) {
                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => 'Debe seleccionar un pago en proceso']);
            }
            if (Input::get('type') == 'publisher')
                $payment = new Payment();
            else
                $payment = new AdminPayment();
            $payment->setBilling(Input::get('billingId'));
            $payment->setAmount(Input::get('importe'));
            $payment->setDate(Input::get('fecha'));
            if (Input::get('pym_type')[0] == '3') {
                if (Input::get('pym_description') == '') {
                    return Response::json(['error' => 1, 'messages' => 'Debe ingresar la descripci&oacute;n del pago']);
                }
                $payment->setDescription(Input::get('pym_description'));
            } else {
                if (Input::get('type') == 'publisher')
                    $payment->setDescription(Payment::$typeOfPayments[(int) Input::get('pym_type')[0]]);
                else
                    $payment->setDescription(AdminPayment::$typeOfPayments[(int) Input::get('pym_type')[0]]);
            }
            if (!$payment->validate()) {
                Session::put('error', $payment->errors());
                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $payment->errors()->all()]);
            }
            $payment->save();
            return Response::json(['error' => 0]);
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);
        }
    }

    public function newBilling() {
        try {
            if (Input::get('type') == 'publisher') {
                $item = Publisher::find(Input::get('itemId'));
                $days_to_billing = $item->getDaysToBilling();
                if (!$days_to_billing || ($days_to_billing == NULL)) {
                    $days_to_billing = Constant::value('pbl_days_to_billing');
                }
                $earning = new Earning();
                $earning->setPublisher($item->getId());
                $billing = new Billing();
            } else {
                $item = Administrator::find(Input::get('itemId'));
                $days_to_billing = $item->getDaysToBilling();
                if (!$days_to_billing || ($days_to_billing == NULL)) {
                    $days_to_billing = Constant::value('pbl_days_to_billing');
                }
                $earning = new AdminEarning();
                $earning->setAdministrator($item->getId());
                $billing = new AdminBilling();
            }
            $importe = Input::get('importeBilling');
            $fecha = Input::get('fechaBilling');
            $descripcion = Input::get('descripcionBilling');
            $earning->setAmount($importe);
            $earning->setPeriod($fecha);
            $earning->setDescription($descripcion);
            if (!$earning->validate()) {
                Session::put('error', $earning->errors());
                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $earning->errors()->all()]);
            }
            $earning->save();
            $billing->setStipulatedDate(date("Y-m-05", strtotime(incremetMonthsToDate($days_to_billing, date('Y-m-d')))));
            $billing->setBalance($importe);
            if (!$billing->save()) {
                Session::put('error', $billing->errors());
                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $billing->errors()->all()]);
            }
            $billing->earnings()->save($earning);

            //Informa al item del pago generado
            //Mailer::send('emails.alert.newBilling', ['billing' => $billing], $item->user->getEmail(), '', 'New payment registered');

            return Response::json(['error' => 0]);
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);
        }
    }

    public function renamePayments() {
        renamePayments();
    }

    private function _mergeEarningsWithPayments($earnings, $payments) {
        $records = array();

        foreach ($earnings as $earning) {
            $records[] = ['date' => date("Y-m-d 00:00:00", strtotime($earning->getPeriod())), 'type' => 'earning', 'record' => $earning, 'balance' => 0];
        }

        foreach ($payments as $payment) {
            $records[] = ['date' => date("Y-m-d H:i:s", strtotime($payment->created_at)), 'type' => 'payment', 'record' => $payment, 'balance' => 0];
        }
        usort($records, '_asc');
        self::_calculateBalance($records);
        usort($records, '_desc');
        return $records;
    }

    private function _calculateBalance(&$records) {
        $balance = 0;
        foreach ($records as &$record) {
            if ($record['type'] == 'earning')
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
