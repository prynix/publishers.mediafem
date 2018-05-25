<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateControlPlacementsCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:controlPlacements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        $send_email = array();
        $placements_count = 0;
        /**
         * El Placement en el dia dio -$10 o menos
         */
        echo "El Placement en el dia dio -$10 o menos\n\n";
        echo 'Fecha de hoy: ' . date('Y-m-d', time()) . "\n";
        echo "----------------------------\n";
        $placementsEliminar = DB::table('placement_earnings_control_by_day')
                ->where('dia', '=', date('Y-m-d', time()))
                ->where('ganancia', '<=', '-10')
                ->get();
        foreach ($placementsEliminar as $row) {
            $placement = Placement::find($row->espacio);
            echo 'ID: ' . $row->espacio . "\n";
            echo $placement->getName() . "\n";
            echo 'Sitio: ' . $placement->site->getName() . "\n";
            echo 'Publisher: ' . $placement->site->publisher->getName() . ' ID: ' . $placement->site->publisher->getId() . "\n";
            echo 'Dia: ' . $row->dia . "\n";
            echo 'Ingresos: $' . $row->ganancia . "\n";
            $send_email[$placement->site->publisher->mediaBuyer->getId()][$placements_count]['publisher'] = $placement->site->publisher->getName();
            $send_email[$placement->site->publisher->mediaBuyer->getId()][$placements_count]['site'] = $placement->site->getName();
            $send_email[$placement->site->publisher->mediaBuyer->getId()][$placements_count]['placement'] = $placement->getName();
            $send_email[$placement->site->publisher->mediaBuyer->getId()][$placements_count]['formato'] = $placement->size->getName();
            $send_email[$placement->site->publisher->mediaBuyer->getId()][$placements_count]['razon'] = "El espacio HOY gener&oacute; una p&eacute;rdida de usd " . $row->ganancia;
            echo date('d/m/Y H:i:s')."\n";
            if (Api::deletePlacement(2, $placement)) {
                echo date('d/m/Y H:i:s')."\n";
                echo "\tEliminado de Appnexus " . $placement->getName() . " Key: " . $placement->getKey() . "\n";
                if ($placement->site->publisher->mediaBuyer) {
                    $send_email[$placement->site->publisher->mediaBuyer->getId()][$placements_count]['status'] = "Eliminado de Appnexus " . $placement->getKey();
                } else {
                    $send_email[0][$placements_count]['status'] = "Eliminado de Appnexus " . $placement->getKey();
                }
                if ($placement->delete() == 1) {
                    echo "\tEliminado de la Base de Datos.\n";
                } else {
                    echo "\tNO SE PUDO eliminar de la Base de Datos.\n";
                }
            } else {
                echo date('d/m/Y H:i:s')."\n";
                echo "\tEliminar MANUALMENTE el espacio  " . $placement->getName() . " Key: " . $placement->getKey() . " de Appnexus\n";
                if ($placement->site->publisher->mediaBuyer) {
                    $send_email[$placement->site->publisher->mediaBuyer->getId()][$placements_count]['status'] = "Eliminar MANUALMENTE el espacio  " . $placement->getName() . " Key: " . $placement->getKey() . " de Appnexus";
                } else {
                    $send_email[0][$placements_count]['status'] = "Eliminar MANUALMENTE el espacio  " . $placement->getName() . " Key: " . $placement->getKey() . " de Appnexus";
                }
            }
            $placements_count = $placements_count + 1;
            echo "----------------------------\n";
        }


        echo "\n\nEl Placement en la semana dio profit negativo\n\n";
        $placements = DB::table('placement_earnings_control')->get();
        foreach ($placements as $earning) {
            if ($earning->earnings < 0) {
                $placement = Placement::find($earning->placement_id);
                echo 'ID: ' . $earning->placement_id . "\n";
                echo $placement->getName() . "\n";
                echo 'Sitio: ' . $placement->site->getName() . "\n";
                echo 'Publisher: ' . $placement->site->publisher->getName() . ' ID: ' . $placement->site->publisher->getId() . "\n";
                echo 'Ultimos 7 dias' . "\n";
                echo 'Ingresos: $' . $earning->earnings . "\n";
                $send_email[$placement->site->publisher->mediaBuyer->getId()][$placements_count]['publisher'] = $placement->site->publisher->getName();
                $send_email[$placement->site->publisher->mediaBuyer->getId()][$placements_count]['site'] = $placement->site->getName();
                $send_email[$placement->site->publisher->mediaBuyer->getId()][$placements_count]['placement'] = $placement->getName();
                $send_email[$placement->site->publisher->mediaBuyer->getId()][$placements_count]['formato'] = $placement->size->getName();
                $send_email[$placement->site->publisher->mediaBuyer->getId()][$placements_count]['razon'] = "El profit total de los ultimos 8 dias fue de usd " . $earning->earnings;
                echo date('d/m/Y H:i:s')."\n";
                if (Api::deletePlacement(2, $placement)) {
                    echo date('d/m/Y H:i:s')."\n";
                    echo "\tEliminado de Appnexus " . $placement->getName() . " Key: " . $placement->getKey() . "\n";
                    if ($placement->site->publisher->mediaBuyer) {
                        $send_email[$placement->site->publisher->mediaBuyer->getId()][$placements_count]['status'] = "Eliminado de Appnexus " . $placement->getKey();
                    } else {
                        $send_email[0][$placements_count]['status'] = "Eliminado de Appnexus " . $placement->getKey();
                    }
                    if ($placement->delete() == 1) {
                        echo "\tEliminado de la Base de Datos.\n";
                    } else {
                        echo "\tNO SE PUDO eliminar de la Base de Datos.\n";
                    }
                } else {
                    echo date('d/m/Y H:i:s')."\n";
                    echo "\tEliminar MANUALMENTE el espacio  " . $placement->getName() . " Key: " . $placement->getKey() . " de Appnexus\n";
                    if ($placement->site->publisher->mediaBuyer) {
                        $send_email[$placement->site->publisher->mediaBuyer->getId()][$placements_count]['status'] = "Eliminar MANUALMENTE el espacio  " . $placement->getName() . " Key: " . $placement->getKey() . " de Appnexus";
                    } else {
                        $send_email[0][$placements_count]['status'] = "Eliminar MANUALMENTE el espacio  " . $placement->getName() . " Key: " . $placement->getKey() . " de Appnexus";
                    }
                }
                $placements_count = $placements_count + 1;
                echo "----------------------------\n";
            }
        }

        foreach ($send_email as $index => $email) {
            if ($index != 0) {
                $admin = Administrator::find($index);
                $data = array(
                    'placements' => $email,
                    'executive' => 'Publishers con ejecutivo asignado: ' . $admin->user->getEmail()
                );
                echo $admin->user->getEmail();
                Mailer::send('emails.alert.controlPlacements', $data, $admin->user->getEmail(), 'Media Buyer', 'Control de Placements en Appnexus');
                Mailer::send('emails.alert.controlPlacements', $data, 'valeria.khusnulina@mediafem.com', 'Media Buyer', 'Control de Placements en Appnexus');
            } else {
                $data = array(
                    'placements' => $email,
                    'executive' => 'Publishers SIN ejecutivo asignado'
                );
                echo 'medios@mediafem.com (SIN ejecutivo)';
                Mailer::send('emails.alert.controlPlacements', $data, 'medios@mediafem.com', 'Media Buyer', 'Control de Placements en Appnexus');
                Mailer::send('emails.alert.controlPlacements', $data, 'sc@mediafem.com', 'Media Buyer', 'Control de Placements en Appnexus');
            }
            echo "\tEspacios:" . count($data['placements']) . "\n";
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return array(
            array('example', InputArgument::OPTIONAL, 'An example argument.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return array(
            array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}
