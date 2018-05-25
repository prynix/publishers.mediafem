<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RemainderUncreatedPublishersCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:media_buyer_commission';

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
        
        if ($this->argument('month') !== NULL)
            MediaBuyerCommission::calculateData($this->argument('month'));
        else
            MediaBuyerCommission::calculateData();

        /* $all = PublisherOptimization::whereNull('adserving')->get();
          $count = count($all);
          foreach ($all as $i=>$a) {
          echo 'Id '.$a->id." ---> ".$count.'--'.$i."\n";
          $a->actualize();
          }
          dd(); */
        /*
          $this->info('Comienzo el envio de emails');
          sendRemainderMailToUncreatedPublishers();
          $this->info('Finalizado');
         */
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return array(
            array('month', InputArgument::OPTIONAL, 'Month and Year YYYY-MM (default: Last Month).'),
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
