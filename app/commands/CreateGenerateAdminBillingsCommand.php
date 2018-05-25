<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateGenerateAdminBillingsCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:adminBillings';

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
        try {
            $this->info("Ingresos Del Mes ");
            generateEarnings(1, 'administrator');
            $this->info("\n\nPagos en proceso\n");
            generateBillings('administrator');
            $this->info('OK!');
        } catch (Exception $ex) {
            $this->error('Error! ' . $ex->getMessage());
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
