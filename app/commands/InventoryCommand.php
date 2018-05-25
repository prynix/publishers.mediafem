<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InventoryCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:fillInventory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Llena las tablas de inventario con datos de ayer.';

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
        
        echo "Inventario de DFP\n";
        $registers = 0;
        $trys = 9;
        $report = new DfpApi();
        while ($registers == 0 && $trys < 10) {
            $registers = $report->getInventoryReport() . " registros agregados.\n";
            echo "\nRegistros Agregados: " . $registers;
            $trys += 1;
            sleep(5);
            echo ' - Intento Nro' . $trys . "\n";
        }
        echo "\n" . '----------------';
        /*echo "\nInventario de ADK2\n";
        $registers = 0;
        $trys = 0;
        $report = new Adk2Api();
        while ($registers == 0 && $trys < 10) {
            $registers = $report->getInventoryReport() . " registros agregados.\n";
            echo "\nRegistros Agregados: " . $registers;
            $trys += 1;
            sleep(5);
            echo ' - Intento Nro' . $trys . "\n";
        }*/
        echo "Inventario de Imonomy\n";
        //Imonomy::fillInventory();
        echo "\n" . '----------------';
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
