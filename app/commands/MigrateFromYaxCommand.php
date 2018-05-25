<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MigrateFromYaxCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cron:migrateFromYax';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migracion de Yax a Appnexus.';

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
        $publisherIds = Publisher::getByAdserver(1);
        $adserver = Adserver::find(2);
        foreach ($publisherIds as $id) {
            //if (($id->publisher_id == 985) || ($id->publisher_id == 1127) || ($id->publisher_id == 1150) || ($id->publisher_id == 1298) || ($id->publisher_id == 1312)) {
                try {
                    $publisher = Publisher::find($id->publisher_id);
                    if (($publisher->pbl_alert == '1')) {
                        echo 'Id: ' . $publisher->getId() . ' Publisher: ' . $publisher->getName() . "\n";
                        //crear publisher en appnexus
                        if ($publisher->getAdserverKey(2) == NULL) {
                            $adserverKeys = Api::newPublisher(2, $publisher);
                            if (is_null($adserverKeys['publisher_key']))
                                throw new Exception('No se pudo dar de alta el publisher ' . $publisher->getName() . ' en Appnexus');
                            if (!Api::assignPaymentRule(2, $adserverKeys['publisher_key']))
                                throw new Exception('No se pudo crear el Payment Rule para el publisher ' . $publisher->getName());
                            echo ' -- Publisher adserver key: ' . $adserverKeys['publisher_key'] . " \n";
                            //guardar nuevo key del adserver
                            $publisher->adservers()->save($adserver, array('adv_pbl_adserver_key' => $adserverKeys['publisher_key']));
                        }else {
                            echo 'YA CREADO' . "\n";
                        }
                        if($publisher->mediaBuyer){
                            if(Api::assignMediaBuyer($adserver->getId(), $publisher))
                                    echo 'MediaBuyer asignado correctamente.'."\n";
                            else
                                echo 'Error en la asignacion de MediaBuyer.'."\n";
                        }
                        //detach publisher - adserver id 1 (Yax)
                        //$publisher->adservers()->detach(1);
                        //--------------------Sitios
                        //listar sitios
                        $sites = $publisher->sites;
                        echo ' -- Sitios: ' . count($sites) . "\n";
                        foreach ($sites as $site1) {
                            $site = Site::find($site1->getId());
                            echo $site->getName();
                            //crear sitio (validado) en appnexus
                            if ($site->isValidated()) {
                                echo ' validado' . "\n";
                                if ($site->getAdserverKey(2) == NULL) {
                                    $site->createSiteInAdServer($adserver->getId());
                                } else {
                                    echo 'YA CREADO' . "\n";
                                }
                                //detach sitio - adserver 1
                                //$site->adservers()->detach(1);
                                //-----------------------Espacios
                                //traer section
                                if (count($site->placements) < 4) {
                                    $sections = $site->sections;
                                    echo ' -- Secciones: ' . count($sections) . "\n";
                                    foreach ($sections as $section) {
                                        echo $section->getName() . "\n";
                                        //crear placements a partir del section
                                        $placements = $section->getPlacements();
                                        foreach ($placements as $placement) {
                                            //crear placement en appnexus
                                            echo 'Formato: ' . $placement->size->getName();
                                            if (!$site->isAlreadyHasPlacementFormat($placement->size->getId())) {
                                                $newPlacement = new Placement();
                                                $newPlacement->adserverId = $adserver->getId();
                                                $newPlacement->setName($placement->getName());
                                                $newPlacement->setSite($placement->site->getId());
                                                $newPlacement->setSize($placement->size->getId());
                                                $newPlacement->save();
                                                //$placement->save();
                                                echo ' Espacio: "' . $placement->getName() . '"' . " \n";
                                            } else {
                                                echo 'YA CREADO' . "\n";
                                            }
                                        }
                                        //eliminar section
                                        //$section->delete();
                                    }
                                } else {
                                    echo 'YA CREADO' . "\n";
                                }
                            } else {
                                echo " no validado\n";
                            }
                        }
                    } else {
                        continue;
                    }
                } catch (Exception $ex) {
                    echo $ex->getMessage() . "\n";
                }
            //}
            echo "\n-----------\n";
            sleep(3);
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
