<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
if (!class_exists('NAWSApiClient')) {
	require_once dirname(__FILE__) . '/../../3rparty/src/Netatmo/autoload.php';
}


class graphs extends eqLogic {
	
	
	public function getClient() {
		$config = array(
			'client_id' => config::byKey('client_id', 'graphs'),
			'client_secret' => config::byKey('client_secret', 'graphs'),
			'username' => config::byKey('username', 'graphs'),
			'password' => config::byKey('password', 'graphs'),
			'scope' => 'read_station'
		);
		
		$client = new  Netatmo\Clients\NAWSApiClient($config);

		try
		{
			$tokens = $client->getAccessToken();
		}
		catch(NAClientException $ex)
		{
			handleError("An error happened while trying to retrieve your tokens: " .$ex->getMessage()."\n", TRUE);
		}	
	//	log::add('graphs', 'debug', print_r($client, true));	
		return $client->getData(NULL, TRUE);
		
	}
	
	public function infoStation() {
		$data = array();
		$data = self::getClient();
		log::add('graphs','debug', 'infostation: ' . print_r($data['devices'],true));
		$array = array();
		foreach($data['devices'] as $device) {
			if($device['type'] == "NAMain") {
				$eqLogic = eqLogic::byLogicalId($device['_id'], 'graphs');
				if (!is_object($eqLogic)) {
					$eqLogic = new graphs();
				}
				$eqLogic->setEqType_name('graphs');
				$eqLogic->setIsEnable(1);
				$eqLogic->setName($device['station_name']);
				$eqLogic->setLogicalId($device['_id']);
				$eqLogic->setConfiguration('type', 'station');
				$eqLogic->setConfiguration('deviceId', $device['_id']);
				$eqLogic->setCategory('heating', 1);
				$eqLogic->setIsVisible(0);
				$eqLogic->save();
				if (count($device['modules']) >0) {
					foreach ($device['modules'] as $module) {
						$eqLogic = eqLogic::byLogicalId($module['_id'], 'graphs');
						if (!is_object($eqLogic)) {
							$eqLogic = new graphs();
						}
						$eqLogic->setEqType_name('graphs');
						$eqLogic->setIsEnable(1);
						$eqLogic->setName($module['module_name']);
						$eqLogic->setLogicalId($module['_id']);
						$eqLogic->setConfiguration('deviceId', $device['_id']);
						$eqLogic->setCategory('heating', 1);
						$eqLogic->setIsVisible(0);
						$eqLogic->save();
					}
				}
			}
		}

		
	}
	
	public function getDataModule() {
		$data = array();
		$data = self::getClient();
		log::add('graphs','debug', 'data_module: ' . print_r($data['devices'], true));
		foreach($data['devices'] as $device) {
			$eqLogic = eqLogic::byLogicalId($device["_id"], 'graphs');
			if (is_object($eqLogic)) {
				$eqLogic->setConfiguration('type', $device['type']);
				$eqLogic->setConfiguration('wifi_status', $device['wifi_status']);
				if(isset($device['dashboard_data'])) {
					foreach($device['dashboard_data'] as $key => $val) {
						$eqLogic->setConfiguration($key, $val);
					}
					$eqLogic->save();
				}
				
			}
			
			foreach ($device['modules'] as $module) {
				$eqLogic = eqLogic::byLogicalId($module["_id"], 'graphs');
				$eqLogic->setConfiguration('battery_vp', $module['battery_vp']);
				$eqLogic->setConfiguration('rf_status', $module['rf_status']);
				$eqLogic->setConfiguration('type', $module['type']);
				if (is_object($eqLogic)) {
					if(isset($module['dashboard_data'])) {
						foreach($module['dashboard_data'] as $key => $val) {
							$eqLogic->setConfiguration($key, $val);
						}
						$eqLogic->save();
					}
					
				}
			}
		}
	
	}	
	
	
	public function getBattery($type,$battery) {
		switch($type) {
			case 'NAModule4':
				if( $battery >= 5640) {return 'battery_full.png';} 
				if($battery >= 5280) {return 'battery_high.png';}
				if($battery >= 4920) {return 'battery_medium.png';} 
				if($battery >= 4560) {return 'battery_low.png';} 
				return "battery_verylow.png";
				break;
			case 'NAModule1':
				if( $battery >= 5500) {return 'battery_full.png';} 
				if($battery >= 5000) {return 'battery_high.png';}
				if($battery >= 4500) {return 'battery_medium.png';} 
				if($battery >= 4000) {return 'battery_low.png';} 
				return "battery_verylow.png";
				break;				
		}
	}
	
	
	public function getSignalStatus($status) {
		if($status >= 90) return 'signal_verylow.png';
		if($status >= 80) return 'signal_low.png';
		if($status >= 70) return 'signal_medium.png';
		if($status >= 60) return 'signal_high.png';
		return 'signal_full.png';
	}

	public function getWifi($wifi) {
		if($wifi >= 100)return 'wifi_unknown.png';
		if($wifi >= 86) return 'wifi_low.png';    
		if($wifi >= 71) return 'wifi_medium.png';
		if($wifi >= 56) return 'wifi_high.png';   
		return 'wifi_full.png';
	}	
	
	public function getType($type) {
		switch($type)
		{
			// Outdoor Module
			case "NAModule1": return $type = 'module_ext';break;
			
			//Wind Sensor
			case "NAModule2": return $type = 'module_wind';break;
	
			//Rain Gauge
			case "NAModule3": return $type = 'module_rain';break;
			
			//Indoor Module
			case "NAModule4": return $type = 'module_int';break;
		}		
	}
	
	
    public static function treeById($_eqType_name) {
        $values = array(
            'eqType_name' => $_eqType_name
        );
        $sql = 'SELECT *
                FROM eqLogic
                WHERE eqType_name=:eqType_name
				';
        $results = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
        $return = array();
        foreach ($results as $result) {
            $return[] = eqLogic::byId($result['id']);
        }
        return $return;
    }	
						
	public function getDataGraph($device_id, $module_id, $scale, $type, $date_begin, $date_end, $limit, $subtitle, $real_time) {
		$config = array(
			'client_id' => config::byKey('client_id', 'graphs'),
			'client_secret' => config::byKey('client_secret', 'graphs'),
			'username' => config::byKey('username', 'graphs'),
			'password' => config::byKey('password', 'graphs'),
			'scope' => 'read_station'
				);
		$client = new  Netatmo\Clients\NAWSApiClient($config);
		try
		{
			$tokens = $client->getAccessToken();
		}
		catch(NAClientException $ex)
		{
			log::add('graphs','error','erreur: ' .$ex->getMessage());
		}
		if ($module_id == 0) {
			$module_id = NULL;
		} 
		$measure = $client->getMeasure($device_id, $module_id, $scale, $type, $date_begin, $date_end, $limit, FALSE, FALSE);
		log::add('graphs','debug', 'measure: ' . print_r($measure, true));
		$data_module = array();
		$data_module['type_graph'] = $subtitle;
		$data_module['infos'] = json_encode($measure); 
		$device = eqLogic::byLogicalId($device_id,'graphs');
		$data_module['name_device'] = $device->getName();
		$data_module['temperature_device'] = $device->getConfiguration('Temperature');
		$data_module['Humidity_device'] = $device->getConfiguration('Humidity');		
		$data_module['Pressure_device'] = $device->getConfiguration('Pressure');		
		$data_module['Noise_device'] = $device->getConfiguration('Noise');		
		$data_module['CO2_device'] = $device->getConfiguration('CO2');	
		$data_module['max_temp_device'] = $device->getConfiguration('max_temp');	
		$data_module['min_temp_device'] = $device->getConfiguration('CO2');	
/*			if ($module->getConfiguration('wifi_status') >= 100) return $data_module['rf_status_module'] = 'wifi_unknown.png';
			if ($module->getConfiguration('wifi_status') >= NAWifiRssiThreshold::RSSI_THRESHOLD_0) return $data_module['rf_status_module'] = 'wifi_low.png';
			if ($module->getConfiguration('wifi_status') >= NAWifiRssiThreshold::RSSI_THRESHOLD_1) return $data_module['rf_status_module'] = 'wifi_medium.png';
			if ($module->getConfiguration('wifi_status') >= NAWifiRssiThreshold::RSSI_THRESHOLD_2) return $data_module['rf_status_module'] = 'wifi_high.png';	*/
		
		$module = eqLogic::byLogicalId($module_id,'graphs');
		if (is_object($module)) { 
			$data_module['name_module'] = $module->getName();
			$data_module['temperature_module'] = $module->getConfiguration('Temperature');
			$data_module['Humidity_module'] = $module->getConfiguration('Humidity');		
			$data_module['Pressure_module'] = $module->getConfiguration('Pressure');		
			$data_module['Noise_module'] = $module->getConfiguration('Noise');		
			$data_module['CO2_module'] = $module->getConfiguration('CO2');	
			$data_module['max_temp_module'] = $module->getConfiguration('max_temp');	
			$data_module['min_temp_module'] = $module->getConfiguration('min_temp');
			$data_module['type'] = $module->getConfiguration('type');
			$data_module['battery_vp'] = self::getBattery($module->getConfiguration('type'),$module->getConfiguration('battery_vp'));
			$data_module['wifi_status'] = self::getWifi($module->getConfiguration('wifi_status'));
			if (array_key_exists('wifi_status', $data_module)) {
				log::add('graphs','debug', '----------------------------------------------');
				log::add('graphs','debug', 'conf: ' . $module->getConfiguration('rf_status'));
				log::add('graphs','debug', '----------------------------------------------');
				$data_module['rf_status'] = self::getSignalStatus($module->getConfiguration('rf_status'));
			}
			
		} else {
			$data_module['name_module'] = $data_module['name_device'];
		}
		return($data_module);	
	}

	
	
      public static function cronHourly() {
		  log::add('graphs', 'debug', 'cronHourly');
		$path = dirname(__FILE__) . '/../../data';
		if (!is_dir($path)) {
			 log::add('graphs', 'debug', 'data existe pas');
			com_shell::execute(system::getCmdSudo() . 'mkdir ' . dirname(__FILE__) . '/../../data' . ' > /dev/null 2>&1;');
			com_shell::execute(system::getCmdSudo() . 'chmod 777 -R ' . dirname(__FILE__) . '/../../data' . ' > /dev/null 2>&1;');
			 log::add('graphs', 'debug', 'data crééé');
		} else {
			 log::add('graphs', 'debug', 'data existe ');
			com_shell::execute(system::getCmdSudo() . 'chmod 777 -R ' . dirname(__FILE__) . '/../../data' . ' > /dev/null 2>&1;');
			 log::add('graphs', 'debug', 'droit sudo');
		}		  
		$config = array(
			'client_id' => config::byKey('client_id', 'graphs'),
			'client_secret' => config::byKey('client_secret', 'graphs'),
			'username' => config::byKey('username', 'graphs'),
			'password' => config::byKey('password', 'graphs'),
			'scope' => 'read_station'
				);
		$client = new  Netatmo\Clients\NAWSApiClient($config);
	
		$files = array('year','month');
		$delay = array('1month','1day');
		$k=0;
		
		foreach ($files as $file) {
			$datas = array();
			$eqLogics = eqLogic::byType('graphs');
			$j = 0;
			foreach ( $eqLogics as $eqLogic) {
				for ($i = 0; $i <= 100 ; $i++) {
					$year = date('Y') - $i;
					$begin = new DateTime('first day of January ' . $year);
					$begin= $begin->getTimestamp();
					$end = new DateTime('last day of December ' . $year);
					$end = $end->getTimestamp() + 23*3600;	
					$measure = $client->getMeasure( $eqLogic->getConfiguration('deviceId'), $eqLogic->getLogicalId(), $delay[$k],'Temperature,Humidity,Pressure,Noise,CO2,max_temp,min_temp,max_hum,min_hum,date_max_temp,date_min_temp,date_max_hum,date_min_hum', $begin, $end, 1024, FALSE, FALSE);
					$datas[$j]['name'] = $eqLogics[$j]->getName() ;
					$datas[$j]['module_id'] = $eqLogics[$j]->getLogicalId() ;
					$datas[$j]['device_id'] = $eqLogics[$j]->getLogicalId() ;
					if ($measure != null) {
						$datas[$j]['year'][$year] = $measure;
					} else {
						break;
					}
				}
				$j++;	
			}
			file_put_contents(dirname(__FILE__) . '/../../data/' . $file .'.json', json_encode($datas));	
			$k++;
			}	
			log::add('graphs', 'debug', 'Fin cronHourly');	 		  
      }
    



    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {
        
    }

    public function preUpdate() {
        
    }

    public function postUpdate() {
        
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*     * **********************Getteur Setteur*************************** */
}

class graphsCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
        
    }

    /*     * **********************Getteur Setteur*************************** */
}
