<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AddDefaultTagsCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:addDefaultTags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Default Creatives to Appenxus Placements.';

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
        $placements = Placement::getByAdserver(2);
        echo count($placements) . "\n";
        foreach ($placements as $placementRow) {
            try {
                if ($placementRow->plc_aditional_adserver_key == NULL) {
                    echo 'Placement: ' . $placementRow->plc_name . "\n";
                    $placement = Placement::find($placementRow->plc_id);
                    if (Api::addDefaultTagToPlacement(2, $placement) == TRUE) {
                        $placement->plc_aditional_adserver_key = '1';
                        $placement->save();
                        echo "\t" . 'OK' . "\n";
                    } else {
                        echo "\t" . 'Fallo ' . "\n";
                    }
                    sleep(2);
                }
            } catch (Exception $ex) {
                echo "\t" . $ex->getMessage() . "\n";
                sleep(2);
            }
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
