<?php

use LaravelBook\Ardent\Ardent;

class OptimizedPublisher extends Ardent {

    protected $table = 'optimized_publishers';
    protected $fillable = array();
    protected $guarded = array();

    public function getDetails() {
        return strtoupper($this->comments).' - '.'Optimizado '.date('d/m/Y', strtotime($this->optimized_date)).' _ Datos del '.date('d/m/Y', strtotime('-1 day '.$this->optimized_date)).' _ Profit antes del cambio $'.$this->previous_profit.' _ Profit estimativo despues del cambio $'.$this->new_profit.' _ Share anterior '.$this->previous_share.'% _ Share nuevo '.floor($this->new_share).'%';
    }
    
    public static function getPublisher($publisherId) {
        $publisher = OptimizedPublisher::where('publisher_id', $publisherId)->orderBy('id', 'DESC')->limit(1)->get();
        if (count($publisher) > 0)
            return $publisher[0];
        else
            return null;
    }

    public static function getPlacement($placementId) {
        $placement = OptimizedPublisher::where('placement_id', $placementId)->orderBy('id', 'DESC')->get();
        if (count($placement) > 0)
            return $placement;
        else
            return null;
    }

    public static function getSite($siteId) {
        $site = OptimizedPublisher::where('site_id', $siteId)->orderBy('id', 'DESC')->get();
        if (count($site) > 0)
            return $site;
        else
            return null;
    }
    
    public static function getByPlacementCountryLastOptimization($placementId, $countryId) {
        $history = OptimizedPublisher::where('placement_id', $placementId)->where('country_id', $countryId)->orderBy('id', 'DESC')->limit(1)->get();
        if (count($history) > 0)
            return $history[0];
        else
            return null;
    }
    
    public static function getByPlacementCountryTypeLastOptimization($placementId, $countryId, $type) {
        $history = OptimizedPublisher::where('placement_id', $placementId)->where('country_id', $countryId)->where('comments', $type)->orderBy('id', 'DESC')->limit(1)->get();
        if (count($history) > 0)
            return $history[0];
        else
            return null;
    }

    public static function getPublisherSites($publisherId, $dateStart = NULL, $dateEnd = NULL) {
        if($dateStart)
            $publisher = OptimizedPublisher::whereBetween('optimized_date', array(date('Y-m-d', strtotime($dateStart)), date('Y-m-d', strtotime($dateEnd))))->where('publisher_id', $publisherId)->orderBy('id', 'DESC')->get();
        else
            $publisher = OptimizedPublisher::where('publisher_id', $publisherId)->orderBy('id', 'DESC')->get();
        if (count($publisher) > 0)
            return $publisher;
        else
            return null;
    }

    public static function getLastOptimization($publisherId) {
        $publisher = OptimizedPublisher::where('publisher_id', $publisherId)->orderBy('id', 'DESC')->limit(1)->get();
        if (count($publisher) > 0) {
            return $publisher[0]->optimized_date;
        } else {
            return '---';
        }
    }

    public static function getRangeHistory($from, $to) {
        //echo date('Y-m-d', strtotime($from)) . "\n";
        //echo date('Y-m-d', strtotime($to)) . "\n";
        $publishers = OptimizedPublisher::whereBetween('optimized_date', array(date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))))->groupBy('publisher_id')->orderBy('id', 'DESC')->get();
        if (count($publishers) > 0) {
            return $publishers;
        } else {
            return NULL;
        }
    }
    
    public static function getRangeHistoryByPublisher($from, $to) {
        //echo date('Y-m-d', strtotime($from)) . "\n";
        //echo date('Y-m-d', strtotime($to)) . "\n";
        $publishers = OptimizedPublisher::whereBetween('optimized_date', array(date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))))->groupBy('publisher_id')->orderBy('id', 'DESC')->get();
        if (count($publishers) > 0) {
            return $publishers;
        } else {
            return NULL;
        }
    }

    public function pasedTwoDays() {
        if ($this->optimized_date <= date('Y-m-d', strtotime("-2 day")))
            return true;
        else {
            return false;
        }
    }

    public static function sendTodayOptimizations() {
        $publishers = OptimizedPublisher::where('optimized_date', date('Y-m-d'))->get();
        $strings = array();
        $i = 0;
        if (count($publishers) > 0) {
            foreach ($publishers as $optimized) {
                $publisher = Publisher::find($optimized->publisher_id);
                $site = Site::find($optimized->site_id);
                $placement = Placement::find($optimized->placement_id);
                $country = Country::find($optimized->country_id);
                $strings[$i]['date'] = date("Y-m-d");
                $strings[$i]['adserver'] = $publisher->getFirstAdserverName();
                $strings[$i]['comments'] = $optimized->comments;
                $strings[$i]['publisher'] = $publisher->getName();
                if ($site) {
                    $strings[$i]['site'] = $site->getName();
                    $strings[$i]['placement'] = $placement->getName();
                    $strings[$i]['country'] = Lang::get('countries.' . $country->cnt_id);
                }else{
                    $strings[$i]['site'] = '--';
                    $strings[$i]['placement'] = '--';
                    $strings[$i]['country'] = '--';
                }
                $strings[$i]['previousProfit'] = $optimized->previous_profit;
                $strings[$i]['newProfit'] = $optimized->new_profit;
                $strings[$i]['previousShare'] = $optimized->previous_share;
                $strings[$i]['newShare'] = $optimized->new_share;
                if ($publisher->mediaBuyer) {
                    $strings[$i]['mediaBuyer'] = $publisher->mediaBuyer->user->profile->getName();
                } else {
                    $strings[$i]['mediaBuyer'] = 'Sin Asignar';
                }
                $i++;
            }
            $data = array(
                'history' => $strings,
                'day' => date('d/m/Y')
            );
            try {
                $constant = Constant::getValue('optimization_monitors');
                $emails = explode("&", $constant->cns_value);
                foreach ($emails as $email) {
                    Mailer::send('emails.alert.optimizedPublishers', $data, $email, '', 'Adtomatik: Publishers Optimizados');
                    echo "Se envio mail a " . $email . "\n";
                }
            } catch (Exception $ex) {
                echo "Error! No se pudo enviar mails a " . implode(', ', $emails) . "\n";
            }
        }
    }

}
