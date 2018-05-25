<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ChangeAppnexusSiteOptionsCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:changeSiteOptions';

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
        $adserver = Adserver::find(2);
        $sites = $adserver->sites;
        echo 'Cantidad total: '.count($sites)."\n";
        $i = 0;
        foreach ($sites as $site) {
            //if($site->getId() != '697' && $site->getId() != '696')
            //continue;
            try {
                if ($site->flag == '0') {
                    $i++;
                    echo $site->getName();
                    if (Api::changeSiteOptions(2, $site)) {
                        $site->flag = 1;
                        $site->save();
                        echo "\tOK\n";
                    } else {
                        echo "\tFALLA ADSERVER\n";
                    }
                }
            } catch (Exception $ex) {
                echo "\tERROR: " . $ex->getMessage() . "\n";
            }
        }
        echo "\n\tTotal: ".$i;
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
