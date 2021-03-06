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

try {
	require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	if (init('action') == 'syncWithStation') {
		graphs::infoStation();
		ajax::success();
	}
	if (init('action') == 'cronHourly') {
		graphs::cronHourly();
		ajax::success();
	}	
	
    if (init('action') == 'getDataModule') {
        $return = graphs::getDataGraph(init('device_id'),init('module_id'),init('scale'),init('type'),init('date_begin'),init('date_end'),init('limit'),init('subtitle'),init('real_time'));
		ajax::success($return);
    }
	
	if (init('action') == 'getData') {
		$type = init('type');
		$path = dirname(__FILE__) . '/../../data/' . $type . '.json';
		if (!file_exists($path)) {
			log::add('graphs', 'debug', 'pas trovue le fichier');
			return array();
		}	else {
			log::add('graphs', 'debug', 'fichier ok');
		}
		com_shell::execute(system::getCmdSudo() . 'chmod 777 ' . $path) ;
		$lines = explode("\n", trim(file_get_contents($path)));
		$result = array();
		foreach ($lines as $line) {
			$result[] = json_decode($line, true);
		}
		ajax::success($result);
	}
		
	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>