<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PublishersOptimizationCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'publishers:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will fill publishers_optimize table.';

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
        $flag = 0;
        if (PublisherOptimization::fillTable()) {
            echo 'OK Obtencion de datos de Appnexus!!';
            PublisherOptimization::optimizeAllPublishers();
            $flag = $flag + 1;
        } else {
            echo "Fallo en el reporte de Appnexus - ver Log.\n";
        }
        if (PublisherOptimizationDfp::fillTable()) {
            echo 'OK Obtencion de datos de Dfp!!';
            PublisherOptimizationDfp::optimizeAllPublishers();
            $flag = $flag + 1;
        } else {
            echo "Fallo en el reporte de Dfp - ver Log.\n";
        }
        if ($flag > 0) {
            OptimizedPublisher::sendTodayOptimizations();
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
