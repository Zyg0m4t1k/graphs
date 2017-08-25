<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../../3rparty/src/Netatmo/autoload.php';
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
$data = array();
$data = graphs::getClient();
graphs::getDataModule();	
foreach($data['devices'] as $device)
{
   	$name_module  = $device['station_name'];
	$id_module = $device['_id'];
	$temp_module = $device['dashboard_data']['Temperature'];
	$hum_module = $device['dashboard_data']['Humidity'];
	$pressure_module = $device['dashboard_data']['Pressure'];
	$noise_module = $device['dashboard_data']['Noise'];
	$CO2_module = $device['dashboard_data']['CO2'];
	$tempmax_module = $device['dashboard_data']['max_temp'];
	$tempmin_module = $device['dashboard_data']['min_temp'];
}
/*$type = "temperature,humidity,Co2";
$measure = $client->getMeasure($device['_id'], Null, "1hour" , $type, time() - 24*3600*30, time(), 1024,  FALSE, FALSE);
$measure = json_encode($measure); 
$measure2 = $client->getMeasure('70:ee:50:00:71:2e', '02:00:00:00:81:1a', "1hour" , $type, time() - 24*3600*30, time(), 1024,  FALSE, FALSE);
$measure2 = json_encode($measure2); */
?>

<div class="panel">
	<header class="title_bar row">
    	<div class="col-lg-12 col-md-12 col-sm-12" id="title">
		<h2>Graphique Netatmo</h2>
        </div>
    </header>
    <div class="main row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 aside_data">
	    	<aside class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            	<div class="row">
                	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 temp_module_left">
                        <div class="strong"><img src="plugins/graphs/doc/images/sonde.png"><strong><?php echo $temp_module ; ?></strong><span class="unit">°C</span></div>
                     </div>  
                     <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 temp_max_left">
                     	<div class="strong_max">Max: <span style="color:#DC143C"><?php echo $tempmax_module ; ?></span>°C</div>
                     </div>
                     <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 temp_min_left">
                        <div class="strong_min">Min:<span style="color:#00008B"> <?php echo $tempmin_module ; ?></span>°C</div> 
                     </div>
                </div>
                <hr />
                <div class="row">
                    	<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 hide_c02">	
                			<div class="strong"><img src="plugins/graphs/doc/images/Co2.png"><span style="color:#191970"><?php echo $CO2_module ; ?><span class="unit">ppm</span></span></div>  
                        </div>
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 humidity_left">
                        	  <div class="strong"><img src="plugins/graphs/doc/images/humidity.png"><span style="color:#00FFFF"><?php echo $hum_module ; ?><span class="unit">%</span></span></div>
                        </div>

               
                </div>
                <hr />
                <div class="row">
                    	<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 ">
                       		<div class="strong"><img src="plugins/graphs/doc/images/decibel.png"><strong style="color:#B0C4DE"><?php echo $noise_module ; ?></strong><span class="unit">db</span></div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        	<div class="strong"><img src="plugins/graphs/doc/images/barometre.png"><strong style="color:#D87093"><?php echo $pressure_module ; ?></strong><span class="unit">mbar</span></div> 
                        </div>                    
                </div>
                <hr />
                <div class="row">
                			<div class="strong">status : <img src="plugins/graphs/doc/icone/signal_full.png"> </div>  

                </div> 
                <hr />
                <div class="row battery">
                			<div class="strong">Batterie : <img src="plugins/graphs/doc/icone/battery_full.png"> </div>  
                </div>               
            </aside>
        </div>
        <section class="col-lg-8 col-md-8 col-sm-8">
                     <div class="col-lg-5 col-md-6 col-sm-6">
                        <select mame="modules" id="modules" class="select_modules">
                        <?php
                        foreach (graphs::treeById('graphs') as $graphs) {			
                            if (is_object($graphs)) {
                               echo '<option value="'.$graphs->getLogicalId().'" class="option_id" name="'.$graphs->getConfiguration('type').'">'.$graphs->getName().'</option>'; 
                               log::add('graphs','debug', 'value: ' . $graphs->getLogicalId() . ' Name :' . $graphs->getName() . ' Type :' . $graphs->getConfiguration('type')  );
                            }			
                        }
                        ?>
                        </select> 

                        <select mame="module_option" id="module_option" class="select">
                            <option value="3hours" id="hours" class="time_option" >{{3 heures}}</option> <!--3hours-->
                            <option value="1day" id="day" class="time_option">{{1 jour}}</option> <!--1day-->
                            <option value="1week" id="week" class="time_option">{{1 semaine}}</option> <!--1 semaine-->
                            <option value="1month" id="month" class="time_option">{{1 mois}}</option> <!--1 mois-->
                        </select> 
                     </div>

                    <div class="col-lg-5 col-md-6 col-sm-6">
                         <label for="startDate">{{Du :}}</label>
                         <input name="startDate" id="select_date_begin" class="select_date" />  
                         <input type="hidden" id="timestamp_start"  value=""  />                     
                         <label for="endDate">{{au :}}</label>
                         <input name="endDate" id="select_date_end" class="select_date" /> 
                         <input type="hidden" id="timestamp_end" value=""   />                	
                    </div>                    
                    <div class="graph col-lg-12 col-md-12 col-sm-12">
                            <div id="container" ></div>
                            
                            <div style="text-align:center;" class="graph col-lg-12 col-md-12 col-sm-12">
                                    <button id="temp" name="Température">temp</button>
                                    <button id="hum" name="Humidité">hum</button>
                                    <button id="co2" name="C02">c02</button>
                                    <button id="Pressure" name="Pression">Pressure</button>
                                    <button id="Noise" name="Bruit">Noise</button>
                           </div>       
                    </div>                      
                                        
        </section>  
<!--		<div class="compare_sonde col-lg-2 col-md-2 col-sm-2 col-xs-12 ">  
                <div class="form-group">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                         <label class="control-label">{{Comparaison}}</label> 
                         <input type="checkbox" class="compareAttr"/>
                    </div>                 
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <label for="startDate">{{Mois :}}</label>
                     <input name="startDate" id="compare_date_start" class="select_date" />  
                     <input type="hidden" id="timestamp_start_compare"  value=""  />                     
                </div>  
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <label for="endDate">{{Mois :}}</label>
                     <input name="endDate" id="compare_date_end" class="select_date" /> 
                     <input type="hidden" id="timestamp_end_compare" value=""   />
                </div>      
        
        </div>  -->       
    </div>
</div>

<script type="text/javascript">
		$(' aside .battery ').hide();
		$( ' .strong_max ' ).css({
			marginLeft : '15px'
		});	
		
		var device_id =$('.option_id:eq(0)').attr('value'),
			module_id = 0,
			scale = '1day',
			type = 'min_temp,max_temp',
			date_begin = parseInt(Math.round(+new Date() / 1000) - 24*3600*30 ) ,		
			date_end = Math.round(+new Date() / 1000),
			limit = 1024,
			subtitle = 'Température',	
			real_time = 'FALSE';
					
			$.ajax({
			type: 'POST',
			url: 'plugins/graphs/core/ajax/graphs.ajax.php',
			data: {
				action: 'getDataModule',
				device_id: device_id,
				module_id: module_id,
				scale: scale,
				type: type,
				date_begin: date_begin,
				date_end: date_end,
				limit: limit,
				subtitle: subtitle,
				real_time: real_time
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error);
			},
			success: function (data) {
				
				if (data.state != 'ok') {
					return;
				}
				createGraph(data)
				

						
					}
				});	
		
//$("body").undelegate(".compareAttr", 'change ').delegate('.compareAttr','change ', function () {
//    if ($(this).value() == 1) {
//       $('#cron_speedtest').hide();
//    } else {
//        $('#cron_speedtest').hide();
//    }
//});		
	
</script>
<?php include_file('desktop', 'style', 'css', 'graphs');?>
<?php include_file('desktop', 'panel', 'js', 'graphs');?>
<?php include_file('3rparty', 'datepicker_fr', 'js', 'graphs');?>
<?php 
if ($_SESSION['user']->getOptions('desktop_highcharts_theme') != '') {
	try {
		include_file('3rdparty', 'highstock/themes/' . $_SESSION['user']->getOptions('desktop_highcharts_theme'), 'js');
	} catch (Exception $e) {

	}
}
?>