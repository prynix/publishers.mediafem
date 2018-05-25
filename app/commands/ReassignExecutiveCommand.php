<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ReassignExecutiveCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:reassignExecutive';

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
        $publisher = new Publisher();
        $publishers = Publisher::where('pbl_media_buyer_id', '=', $this->argument('from'))->get();
        $error = "";
        $count = count($publishers);
        echo 'Total ' . $count . "\n";
        foreach ($publishers as $publisher) {
            $count = $count - 1;
            if ($publisher->mediaBuyer) {
                if ($publisher->mediaBuyer->adm_id == $this->argument('from')) {
                    $publisher->setMediaBuyer($this->argument('to'));
                    if ($publisher->forceSave()) {
                        if (!Api::assignMediaBuyer($publisher->getFirstAdserverId(), $publisher)) {
                            sleep(2);
                            if (!Api::assignMediaBuyer($publisher->getFirstAdserverId(), $publisher)) {
                                sleep(2);
                                if (!Api::assignMediaBuyer($publisher->getFirstAdserverId(), $publisher)) {
                                    echo $publisher->getName() . "\n";
                                    echo "\tError en asignacion en el Adserver\n";
                                    $error = $error . '_' . $publisher->getId();
                                }
                            }
                        }
                    } else {
                        echo $publisher->getName() . "\n";
                        echo "\tError al guardar en la BD\n";
                    }
                    echo 'Faltan ' . $count . "\n";
                }
            }
        }
        echo "\n" . 'Errores: ' . $error . "\n";
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return array(
            array('from', InputArgument::REQUIRED, 'Administrator Id (From)'),
            array('to', InputArgument::REQUIRED, 'Administrator Id (From)'),
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
