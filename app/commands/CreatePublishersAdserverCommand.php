<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreatePublishersAdserverCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:createPublishersAdserver';

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
        $publishers_id = array(20, 5, 17, 37, 2, 4, 7, 8, 10, 12, 14, 15, 19, 23, 25, 31, 33, 34, 39, 42, 43, 45, 46, 48, 50, 51, 60, 62, 65, 67, 69, 70, 75, 80, 81, 87, 90, 92, 94, 96, 98, 100, 111, 114, 175, 177, 178, 179, 182, 183, 184, 185, 186, 187, 188, 189);
        $publishers = Publisher::all();
        foreach ($publishers as $publisher) {
            echo $publisher->getName() . "\n";
            Api::assignMediaBuyer($publisher->getFirstAdserverId(), $publisher);
            //$adserverKeys = 999999;
            /* $adserverKeys = Api::newPublisher(2, $publisher);
              Api::assignPaymentRule(2, $adserverKeys['publisher_key']);
              foreach ($publisher->adservers as $key => $value) {
              $publisher->adservers[$key]->pivot->adv_pbl_adserver_key = $adserverKeys['publisher_key'];
              //$publisher->adservers[$key]->pivot->adv_pbl_adserver_key = $adserverKeys;
              echo 'Created publisher ' . $adserverKeys['publisher_key'] . ' - ' . $publisher->adservers[$key]->pivot->save() . "\n";
              }
              foreach ($publisher->sites as $key => $site) {
              $adserverKey = Api::newSite(2, $site);
              //$adserverKey = 888888;
              $site->sit_categorized_on_adserver = 0;
              foreach ($site->adservers as $key => $value) {
              $site->adservers[$key]->pivot->adv_sit_adserver_key = $adserverKey;
              echo "\tCreated site " .$site->getName().' - '. $adserverKey . ' - ' . $site->adservers[$key]->pivot->save() . "\n";
              }
              foreach ($site->placements as $key => $placement) {
              $key = Api::newPlacement(2, $placement);
              //$key = 777777;
              $placement->plc_adserver_key = $key;
              $placement->forceSave();
              echo "\t\tCreated placement " .$placement->getName().' - '. $key . "\n";
              }
              $site->forceSave();
              } */
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
