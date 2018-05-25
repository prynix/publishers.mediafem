<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BackupAndDropboxCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'db:dumpAndSave';

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
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
                $dw = date( "w");
                $week = "";
                switch ($dw) {
                    case 0: 
                        $week = 'Domingo';
                        break;
                    case 1:
                        $week = 'Lunes';
                        break;
                    case 2:
                        $week = 'Martes';
                        break;
                    case 3:
                        $week = 'Miercoles';
                        break;
                    case 4:
                        $week = 'Jueves';
                        break;
                    case 5:
                        $week = 'Viernes';
                        break;
                    case 6:
                        $week = 'Sabado';
                        break;
                }
		$this->call('db:backup');
		$this->call('dropbox:upload');
                
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('example', InputArgument::OPTIONAL, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
