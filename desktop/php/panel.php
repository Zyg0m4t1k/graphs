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
		<h2>Netatmo</h2>
        </div>
    </header>
    <div class="main row">
		<div class="col-lg-1 col-md-21 col-sm-1 aside_menu">  
        </div>  
        <div class="col-lg-2 col-md-2 col-sm-2 aside_data">
	    	<aside class="col-lg-12 col-md-12 col-sm-12">
            	<div class="row">
                	<aside class="col-lg-12 col-md-12 col-sm-12 temp_module_left">
                        <div class="strong"><img src="plugins/graphs/doc/images/sonde.png"><strong><?php echo $temp_module ; ?></strong><span class="unit">°C</span></div>
                     </aside>  
                     <aside class="col-lg-6 col-md-6 col-sm-6 temp_max_left">
                     	<div class="strong_max">Max: <?php echo $tempmax_module ; ?>°C</div>
                     </aside>
                     <aside class="col-lg-6 col-md-6 col-sm-6 temp_min_left">
                        <div class="strong_min">Min: <?php echo $tempmin_module ; ?>°C</div> 
                     </aside>
                </div>
                <hr />
                <div class="row">
                	<aside class="col-lg-12 col-md-12 col-sm-12">
                    	<aside class="col-lg-6 col-md-6 col-sm-6 hide_c02">	
                			<div class="strong"><img src="plugins/graphs/doc/images/Co2.png"><strong  class="strong_Co2"><?php echo $CO2_module ; ?></strong><span class="unit">ppm</span></div>  
                        </aside>
                        <aside class="col-lg-5 col-md-5 col-sm-5 humidity_left">
                        	  <div class="strong"><img src="plugins/graphs/doc/images/humidity.png"><strong class="strong_hum"><?php echo $hum_module ; ?></strong><span class="unit">%</span></div>
                        </aside>
                	</aside> 
               
                </div>
                <hr />
                <div class="row">
                    <aside class="col-lg-12 col-md-12 col-sm-12">
                    	<aside class="col-lg-6 col-md-6 col-sm-6 ">
                       		<div class="strong"><img src="plugins/graphs/doc/images/decibel.png"><strong><?php echo $noise_module ; ?></strong><span class="unit">db</span></div>
                        </aside>
                        <aside class="col-lg-6 col-md-6 col-sm-6">
                        	<div class="strong"><img src="plugins/graphs/doc/images/barometre.png"><strong><?php echo $pressure_module ; ?></strong><span class="unit">mbar</span></div> 
                        </aside>
                    </aside>                      
                </div>
                <hr />
                <div class="row">

                	<aside class="col-lg-12 col-md-12 col-sm-12">
                    	<aside class="col-lg-6 col-md-6 col-sm-6">	
                			<div class="strong">status : </div>  
                        </aside>
                        <aside class="col-lg-5 col-md-5 col-sm-5">
                        	  <div class="status"><img src="plugins/graphs/doc/icone/signal_full.png"></div>
                        </aside>
                	</aside>                      
                </div> 
                <hr />
                <div class="row">
                	<aside class="col-lg-12 col-md-12 col-sm-12 battery">
                    	<aside class="col-lg-6 col-md-6 col-sm-6">	
                			<div class="strong">Batterie : </div>  
                        </aside>
                        <aside class="col-lg-5 col-md-5 col-sm-5">
                        	  <div class="battery"><img src="plugins/graphs/doc/icone/battery_full.png"></div>
                        </aside>
                	</aside>                      
                </div>               
            </aside>
        </div>
        <section class="col-lg-7 col-md-7 col-sm-7">
        	<div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="select_module col-lg-3 col-md-3 col-sm-3">
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
                    </div>
                    <div class="select_option col-lg-3 col-md-3 col-sm-3">
                        <select mame="module_option" id="module_option" class="select">
                            <option value="3hours" id="hours" class="time_option" >3 heures</option> <!--3hours-->
                            <option value="1day" id="day" class="time_option">1 jour</option> <!--1day-->
                            <option value="1week" id="week" class="time_option">1 semaine</option> <!--1 semaine-->
                            <option value="1month" id="month" class="time_option">1 mois</option> <!--1 mois-->
                        </select> 
                    </div> 
                    <div class="col-lg-6 col-md-6 col-sm-6">
                         <label for="startDate">Du :</label>
                         <input name="startDate" id="select_date_begin" class="select_date" />  
                         <input type="hidden" id="timestamp_start"  value=""  />                     
                         <label for="endDate">au :</label>
                         <input name="endDate" id="select_date_end" class="select_date" /> 
                         <input type="hidden" id="timestamp_end" value=""   />                	
                    </div>                
                 </div>  
             </div>      
        </section>
		<section class="graph col-lg-7 col-md-7 col-sm-7">
        		<div id="container1"></div>
                
                <div class="row">
                    <div id="toolbar_btn" class="ui-widget-header ui-corner-all graph col-lg-12 col-md-12 col-sm-12">
                        <button id="temp" name="Température">temp</button>
                        <button id="hum" name="Humidité">hum</button>
                        <button id="co2" name="C02">c02</button>
                        <button id="Pressure" name="Pression">Pressure</button>
                        <button id="Noise" name="Bruit">Noise</button>
                    </div>
                </div>       
        </section>     
        <div class="info_sonde col-lg-2 col-md-2 col-sm-2" >  
            <aside class="col-lg-12 col-md-12 col-sm-12">
            	<div>
                </div> 
            </aside>
            <aside class="col-lg-12 col-md-12 col-sm-12">
                	<div class="row">                 	
                        <div class="col-lg-4 col-md-4 col-sm-4">
                        </div>
                    	<div class="col-lg-4 col-md-4 col-sm-4">                       
                        </div>
                    	<div class="col-lg-4 col-md-4 col-sm-4">   
                        </div>                                                                   
                    </div>
            </aside>       
        </div>
    </div>
    <footer class="row">
    <div class="col-lg-12 col-md-12 col-sm-12" id="title">
    <h2></h2>
    </div>
    </footer>

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
	
</script>
<?php include_file('3rparty/themes', 'gray', 'js', 'graphs');?>
<?php include_file('desktop', 'style', 'css', 'graphs');?>
<?php include_file('3rparty', 'jquery.utils', 'js', 'graphs');?>
<?php include_file('3rparty', 'jquery-1.10.2', 'js', 'graphs');?>
<?php include_file('3rparty', 'jquery-ui', 'js', 'graphs');?>
<?php include_file('3rparty', 'highstock', 'js', 'graphs');?>
<?php include_file('3rparty', 'exporting', 'js', 'graphs');?>
<?php include_file('desktop', 'panel', 'js', 'graphs');?>
<?php include_file('3rparty', 'jquery.utils', 'js', 'graphs');?>
<?php include_file('3rparty', 'datepicker_fr', 'js', 'graphs');?>




	
