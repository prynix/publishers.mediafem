<?php

/* * *
 * CRONS
 */

function generateEarnings($lastMonth = 1, $type = 'publisher') {
    if ($type == 'publisher') {
        $start_date = date('Y-m-d 00:00:00', strtotime('-' . $lastMonth . ' month', strtotime(date('Y-m-01'))));
        $end_date = date('Y-m-d 23:59:59', strtotime("-" . Date("d") . " days" . ' -' . ($lastMonth - 1) . ' month'));
        $data = array('start_date' => $start_date, 'end_date' => $end_date, 'group_by' => 'publisher_adserver_id');
        $publishers = Publisher::all();
        echo $start_date . ' - ' . $end_date . "\n";

        echo count($publishers) . " publishers\n";
        foreach ($publishers as $publisher) {

            if (!$publisher->hasEarningsOfThatMonth($start_date)) {
                $revenue = 0;
                $data['publisher_id'] = $publisher->getId();
                echo '  publisher ' . $publisher->getName() . "\n";
                $adservers = Adserver::all();
                foreach ($adservers as $adserver) {
                    Session::put('adserver.id', $adserver->getId());
                    $report = Inventory::getRevenueByDate($data);
                    if ($report):
                        $revenue += $report[0]->revenue;
                    else:
                        $revenue += 0;
                    endif;
                }
                if($publisher->imonomy){
                    $revenue += Imonomy::getRevenueByDate('last_month', $data);
                }
                echo '    $' . $revenue . "\n";
                $earning = new Earning();
                $earning->setPublisher($publisher->getId());
                $earning->setAmount($revenue);
                $date = date("Y-m-d", strtotime($start_date));
                $earning->setPeriod($date);
                $earning->forceSave();
                echo '   --> $' . $earning->getAmount() . ' ' . $earning->getConcept() . "\n";
                echo "------------------\n";
            }
        }
    }
    else {
        $start_date = date('Y-m-d 00:00:00', strtotime('-' . $lastMonth . ' month', strtotime(date('Y-m-01'))));
        $end_date = date('Y-m-d 23:59:59', strtotime("-" . Date("d") . " days" . ' -' . ($lastMonth - 1) . ' month'));
        $data = array('start_date' => $start_date, 'end_date' => $end_date, 'group_by' => 'publisher_adserver_id');
        $administrators = Administrator::all();
        echo $start_date . ' - ' . $end_date . "\n";
        echo count($administrators) . " administrators\n";
        foreach ($administrators as $administrator) {
            if ($administrator->group->has('affiliate_revenue')) {
                $revenue = 0;
                $revshare = $administrator->getRevenueShare();
                echo '  administrator ' . $administrator->user->getEmail() . "\n";
                $report = InventoryAdmin::getReportAllPublishers(getDatetimeByInterval('last_month'), $administrator->getId());
                if ($report) {
                    echo '    revenue share ' . $revshare . "%\n";
                    echo '    total $' . $report['totals']['revenue'] . "%\n";
                    $revenue = $report['totals']['revenue'] / 100 * $revshare;
                } else {
                    $revenue = 0;
                }
                echo '    $' . $revenue . "\n";
                $earning = new AdminEarning();
                $earning->setAdministrator($administrator->getId());
                $earning->setAmount($revenue);
                $date = date("Y-m-d", strtotime($start_date));
                $earning->setPeriod($date);
                $earning->forceSave();
                echo '   --> $' . $earning->getAmount() . ' ' . $earning->getConcept() . "\n";
                echo "------------------\n";
            }
        }
    }
}

function generateBillings($type = 'publisher') {
    if ($type == 'publisher') {
        $publishers = Publisher::all();
        //$publishers[] = Publisher::find(1396);
        echo count($publishers) . " publishers\n";
        foreach ($publishers as $publisher) {

            $earnings = array();
            $balance_accumulated = 0;
            foreach ($publisher->earnings as $earning) {
                if (!$earning->billed()) {
                    $earnings[] = $earning;
                    $balance_accumulated += $earning->getAmount();
                    /* if ($earning->getAmount() != 0) {
                      echo '    ingreso $' . $earning->getAmount() . "\n";
                      echo "      balance acumulado: $" . $balance_accumulated . "\n";
                      } */
                }
            }
            if ($balance_accumulated >= 100) {

                $days_to_billing = $publisher->pbl_days_to_billing;
                if (!$days_to_billing)
                    $days_to_billing = Constant::value('pbl_days_to_billing');
                $billing = new Billing();
                $billing->setStipulatedDate(date("Y-m-05", strtotime(incremetMonthsToDate($days_to_billing, date('Y-m-d')))));
                $billing->setBalance($balance_accumulated);
                $billing->save();
                $billing->earnings()->saveMany($earnings);
                echo '  publisher ' . $publisher->getName() . "\n";
                echo '    Pago en proceso: ' . $billing->getPublisher()->getName() . ' - $' . $billing->getBalance() . "\n";
                echo "------------------\n";
            }
        }
    }else {
        $administrators = Administrator::all();
        //$administrators[] = Administrator::find();
        echo count($administrators) . " administrator\n";
        foreach ($administrators as $administrator) {

            $earnings = array();
            $balance_accumulated = 0;
            foreach ($administrator->earnings as $earning) {
                if (!$earning->billed()) {
                    $earnings[] = $earning;
                    $balance_accumulated += $earning->getAmount();
                }
            }
            if ($balance_accumulated >= 100) {

                $days_to_billing = $administrator->getDaysToBilling();
                if (!$days_to_billing)
                    $days_to_billing = Constant::value('adm_days_to_billing');
                $billing = new AdminBilling();
                $billing->setStipulatedDate(date("Y-m-05", strtotime(incremetMonthsToDate($days_to_billing, date('Y-m-d')))));
                $billing->setBalance($balance_accumulated);
                $billing->save();
                $billing->earnings()->saveMany($earnings);
                echo '  administrator ' . $administrator->user->getEmail() . "\n";
                echo '    Pago en proceso: ' . $billing->getAdministrator()->user->getEmail() . ' - $' . $billing->getBalance() . "\n";
                echo "------------------\n";
            }
        }
    }
}

function renamePayments() {
    $pays = Payment::all();
    foreach ($pays as &$pay) {
        $name = $pay->getConcept();
        $name = strtolower($name);
        if (strpos($name, 'total')) {
            $pay->setDescription('total_payment');
        } elseif (strpos($name, 'parcial') || strpos($name, 'partial')) {
            $pay->setDescription('partial_payment');
        } elseif (strpos($name, 'adjustments') || strpos($name, 'ajustes')) {
            $pay->setDescription('adjustments');
        }
        var_dump($pay->getConcept());
        var_dump($pay->update());
    }
}

function sendRemainderMailToUncreatedPublishers() {
    //$allUsers = User::all();
    $allUsers[] = User::find(916);
    $allUsers[] = User::find(924);
    $users = array();
    foreach ($allUsers as $user) {
        if (!($user->administrator) && !($user->publisher) && (date('Y-m-d', strtotime($user->created_at)) <= date('Y-m-d', strtotime("-2 day")))) {
            $data = array(
                'user_id' => $user->id,
                'email' => $user->getEmail()
            );
            try {
                Mailer::send('emails.alert.reminder', $data, $data['email'], '', 'Adtomatik: Reminder (Action required for payment)');
                echo "Se envio mail a " . $user->getEmail() . "\n";
            } catch (Exception $ex) {
                echo "Error! No se pudo enviar mail a " . $user->getEmail() . "\n";
            }
        }
    }
}

function categorizeSites() {
    $adservers = Adserver::all();
    foreach ($adservers as $adserver) {
        if ($adserver->getId() == 1)
            continue;
        $res = Site::getSitesByAdserverHasToBeCategorized($adserver->getId());
        echo "Categorizacion de " . $adserver->getName() . " a " . count($res) . " sitios." . "\n";
        if (count($res) > 0) {
            $api = Api::categorizeSites($adserver->getId(), $res);
        }
    }
}
