<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../../3rparty/src/Netatmo/autoload.php';
$config = array(
			'client_id' => config::byKey('client_id', 'graphs'),
			'client_secret' => config::byKey('client_secret', 'graphs'),
			'username' => config::byKey('username', 'graphs'),
			'password' => config::byKey('password', 'graphs'),
			'scope' => ''
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

?>

<div class="panel">
	<header class="title_bar row">
    	<div class="col-lg-12 col-md-12 col-sm-12" id="title">
		<h2 id="title_name"></h2>
        </div>
    </header>
    <div class="main row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 aside_data">
            	<div class="row">
                	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="strong"><img src="plugins/graphs/docs/images/sonde.png"><strong class="temp_module_left"><?php echo $temp_module ; ?></strong><span class="unit">°C</span></div>
                     </div>  
                     <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 ">
                     	<div class="strong_max">Max: <span style="color:#DC143C" class="temp_max_left"><?php echo $tempmax_module ; ?></span>°C</div>
                     </div>
                     <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <div class="strong_min">Min: <span style="color:#00008B"  class="temp_min_left"> <?php echo $tempmin_module ; ?></span>°C</div> 
                     </div>
                </div>
                <hr />
                <div class="row">
                    	<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">	
                			<div class="strong"><img src="plugins/graphs/docs/images/Co2.png"><span style="color:#191970"><?php echo $CO2_module ; ?><span class="unit">ppm</span></span></div>  
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        	  <div><img src="plugins/graphs/docs/images/humidite.png"><span style="color:#00FFFF" class="strong humidity_left"><?php echo $hum_module ; ?></span><span class="unit">%</span></div>
                        </div>

               
                </div>
                <hr />
                <div class="row">
                    	<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 ">
                       		<div class="strong"><img src="plugins/graphs/docs/images/decibel.png"><strong style="color:#B0C4DE"><?php echo $noise_module ; ?></strong><span class="unit">db</span></div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        	<div class="strong"><img src="plugins/graphs/docs/images/barometre.png"><strong style="color:#D87093"><?php echo $pressure_module ; ?></strong><span class="unit">mbar</span></div> 
                        </div>                    
                </div>
                <hr />
                <div class="row">
                			<div class="strong">status : <img src="plugins/graphs/docs/icone/signal_full.png"> </div>  

                </div> 
                <hr />
                <div class="row battery">
                			<div class="strong">Batterie : <img src="plugins/graphs/docs/icone/battery_full.png"> </div>  
                </div>               
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8">
        
                       <!-- <select mame="modules" id="modules" class="select_modules">-->
                        <?php
							echo '<div class="menu_list"><ul class="nav nav-tabs">';	

                        foreach (graphs::treeById('graphs') as $graphs) {	

                          if (is_object($graphs)) {
								echo '<li ><a href="#" data-type="'.$graphs->getConfiguration('deviceId').'" value="'.$graphs->getLogicalId().'" class="option_id" name="'.$graphs->getConfiguration('type').'"><h5>' . $graphs->getName() .'</h5></a></li>';
                            }	
							 	
                        }
						 echo '</ul></div>';

                        ?>

                    <div class="graph col-lg-12 col-md-12 col-sm-12">
                        	<div class="box-header">
                            	<div class="btn-group">
                                	<button class="delay btn btn-default" name="day" id="1hour" >Dernières 24h</button>
                                    <button class="delay btn btn-default" name="week" id="3hours" >7 derniers jours</button>
                                    <button class="delay btn btn-default" name="month" id="1day" >30 derniers jours</button>
                                    <button class="delay btn btn-default" name="year" id="1week" >Année</button>
                                 </div>
								 <div class="pull-right">
                                     <label for="startDate">{{Du :}}</label>
                                     <input name="startDate" id="select_date_begin" class="select_date" />  
                                     <input type="hidden" id="timestamp_start"  value=""  />                     
                                     <label for="endDate">{{au :}}</label>
                                     <input name="endDate" id="select_date_end" class="select_date" /> 
                                     <input type="hidden" id="timestamp_end" value=""   /> 
                                     <button class="btn btn-default"  id="valid_time" type="button"><i class="fa fa-fw fa-chevron-right"></i></button>  
                                 </div>  
                             </div>                            


                            <div id="container" ></div>
                            
                            
                            
                            <div style="text-align:center;" class="graph col-lg-12 col-md-12 col-sm-12">
                                    <button class="data temp" id="temp" name="Température">temp</button>
                                    <button class="data hum" id="hum" name="Humidité">hum</button>
                                    <button class="data co2" id="co2" name="C02">c02</button>
                                    <button class="data pressure" id="pressure" name="Pression">Pressure</button>
                                    <button class="data noise" id="noise" name="Bruit">Noise</button>
                           </div>       
                    </div> 
                    <div class="stat col-lg-12 col-md-12 col-sm-12">                  
                    <div id="container_stat" ></div>  
                    </div>     
        </div>  
		<div class="compare_sonde col-lg-2 col-md-2 col-sm-2 col-xs-12 ">  
            <div id="historicMenu">
              <div class="list-group panel">
                <a href="#" class="list-group-item "  data-parent="#historicMenu" id="return_home"><i class="fa fa-home" style="margin-right:5px"></i>home</a>                
                <a href="#history" class="list-group-item " data-toggle="collapse" data-parent="#historicMenu"><i class="fa fa-line-chart" style="margin-right:5px"></i>Historique Annuel<i class="fa fa-caret-down" style="margin-left:5px"></i></a>
                <div class="collapse" id="history">
                  <a href="#" class="list-group-item" value="temp" class="year "><span><i class="fa fa-thermometer-full" style="margin-right:5px"></i>Température</span></a>
                  <a href="#" class="list-group-item" value="hum" class="year "><span><i class="fa fa-tint" style="margin-right:5px"></i>Humidité</span></a>
                  <a href="#" class="list-group-item noise" value="noise" class="year "><span><i class="fa fa-volume-up" style="margin-right:5px"></i> Bruit</span></a>
                  <a href="#" class="list-group-item year co2" value="co2"><span><i class="fa fa-cloud" style="margin-right:5px"></i>Co2</span></a>
                  <a href="#" class="list-group-item pressure" value="pressure"><span><i class="fa fa-superpowers" style="margin-right:5px"></i>Pression</span></a>
                </div>                
                
                <a href="#statistic" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#historicMenu"><i class="fa fa-table" style="margin-right:5px"></i>Statistiques Annuelles<i class="fa fa-caret-down" style="margin-left:5px"></i></a>
                <div class="collapse" id="statistic">
                     <?php 
					   $i=1;
                	   foreach (graphs::treeById('graphs') as $graph) {
							   echo '<a href="#SubMenu' . $i .'" class="list-group-item" data-toggle="collapse" data-parent="#SubMenu' . $i .'"><span>'.$graph->getName().'</span><i class="fa fa-caret-down"></i></a>';
							   echo '<div class="collapse list-group-submenu statistic_year" id="SubMenu' . $i .'">';
							   echo '<a href="#" class="list-group-item " data-parent="#SubMenu' . $i .'"  value="'.$graph->getLogicalId().'" title="tem" ><span style="margin-left:20px"><i class="fa fa-thermometer-full" style="margin-right:5px"></i> Température</span></a>';
							   echo '<a href="#" class="list-group-item " data-parent="#SubMenu' . $i .'" value="'.$graph->getLogicalId().'" title="hum" ><span style="margin-left:20px"><i class="fa fa-tint" style="margin-right:5px"></i>Humidité</span></a>';
							   echo '</div>'; 
							   $i++;
					   }
					  

					 ?>	
             	 </div>
                <a href="#statistic_month" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#historicMenu"><i class="fa fa-table" style="margin-right:5px"></i>Statistiques Mensuelles<i class="fa fa-caret-down" style="margin-left:5px"></i></a>
                <div class="collapse" id="statistic_month">
                     <?php 
                	   foreach (graphs::treeById('graphs') as $graph) {
							   echo '<a href="" class="list-group-item" data-toggle="collapse" value="'.$graph->getLogicalId().'"><span>'.$graph->getName().'</span></a>';

					   }
					 ?>	
             	 </div>
              </div>
            </div>     
        </div>        
    </div>
</div>

<?php include_file('desktop', 'style', 'css', 'graphs');?>
<?php include_file('desktop', 'panel', 'js', 'graphs');?>
<?php include_file('3rparty', 'datepicker_fr', 'js', 'graphs');?>
<script type="text/javascript">

		
		$(' #statistics').hide();
		$(' aside .battery ').hide();
		$( ' .strong_max ' ).css({
			marginLeft : '15px'
		});	
		var current_scale = "1hour",
			current_begin = parseInt(Math.round(+new Date() / 1000) - 24*3600 ) ,	
			current_end = Math.round(+new Date() / 1000),
			device_id =$('.option_id:eq(0)').attr('value'),
			module_id = 0,
			scale = current_scale,
			type = 'min_temp,max_temp',
			date_begin = current_begin ,	
			date_end = current_end,
			limit = 1024,
			subtitle = 'Température',	
			real_time = 'FALSE'
			;
		console. log ( 'device : ' + $('.option_id:eq(0)').attr('value') + ' module ' + $('ul.nav-tabs li.active').attr('value')) 
		
			
					
			$.ajax({
			type: 'POST',
			url: 'plugins/graphs/core/ajax/graphs.ajax.php',
			data: {
				action: 'getDataModule',
				device_id: device_id,
				module_id: module_id,
				scale: scale,
				type: type,
				date_begin: current_begin,
				date_end: current_end,
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
	
	

var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].onclick = function(){
        /* Toggle between adding and removing the "active" class,
        to highlight the button that controls the panel */
        this.classList.toggle("active");

        /* Toggle between hiding and showing the active panel */
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    }
}
var d = new Date();
var n = d.getFullYear();
</script>


