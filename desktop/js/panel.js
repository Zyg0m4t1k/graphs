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

/*
$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

 * Fonction pour l'ajout de commande, appellé automatiquement par plugin.template
 
function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td>';
    tr += '<span class="cmdAttr" data-l1key="id" style="display:none;"></span>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}">';
    tr += '</td>';
    tr += '<td>';
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
    tr += '</td>';
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
    }
    tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
    tr += '</td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    if (isset(_cmd.type)) {
        $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
    }
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}
*/

		
	$( "#modules" ).selectmenu({
		change: function( event, data ) {
			if ( $('#select_date_begin').val() == "" && $('#select_date_end').val() == ""  ) {
				var date_begin = parseInt(Math.round(+new Date() / 1000) - 24*3600*30 ) ,		
					date_end = Math.round(+new Date() );				
			}
			
			if ( $('#select_date_begin').val() != "" && $('#select_date_end').val() != ""  ) {
				var date_begin =  $('#timestamp_start').val() ,		
					date_end =  $('#timestamp_end').val();	
			}	
			
			var device_id = $('.option_id:eq(0)').attr('value'),
				module_id = data.item.value,
				scale = $( "#module_option option:selected" ).attr('value'),
				type = 'min_temp,max_temp',	
				date_end = Math.round(+new Date() / 1000),
				limit = '1024',
				subtitle = 'Température',	
				real_time = 'FALSE';
				ajaxRequest(device_id,module_id,scale,type,date_begin,date_end,limit,subtitle,real_time);
				var type_station = $( "#modules option:selected" ).attr('name');
				if (type_station == 'NAModule1') {
						$(' #Pressure ').hide();
						$(' #Noise ').hide();
						$(' .hide_c02 ').hide();
						$(' #co2 ').hide();
						$( ' .strong_max ' ).css( {marginLeft : '15px'} );
						$( ' .humidity_left ' ).css( {marginLeft : '70px'} );
						$(' aside .battery ').show();
						
				} 
				if (type_station == 'NAModule4') {
						$(' #Pressure ').hide();
						$(' #Noise ').hide();
						$(' #co2 ').show();				
						$(' .hide_c02 ').show();
						$( ' .strong_max ' ).css( {marginLeft : '15px'} );
						$( ' .humidity_left ' ).css( {marginLeft : '0'} );
						(' aside .battery ').show();

				}	
				if (type_station == 'NAMain') {
						$(' .hide_c02 ').show();
						$(' #Pressure ').show();
						$(' #Noise ').show();
						$(' .hide_c02 ').show();
						$(' #co2 ').show();	
						$( ' .strong_max ' ).css( {marginLeft : '15px'} );
						$( ' .humidity_left ' ).css( {marginLeft : '0'} );
						$(' aside .battery ').hide();
				}
		}		
	});
	
	$( "#module_option" ).selectmenu();	
	
	
	$( "button" ).button()
		.click(function( event ) {

			if ( $('#select_date_begin').val() == "" && $('#select_date_end').val() == ""  ) {
				var date_begin = parseInt(Math.round(+new Date() / 1000) - 24*3600*30 ) ,		
					date_end = Math.round(+new Date() );				
			}
			
			if ( $('#select_date_begin').val() != "" && $('#select_date_end').val() != ""  ) {
				var date_begin =  $('#timestamp_start').val() ,		
					date_end =  $('#timestamp_end').val();
			}
			
			if ( $('#select_date_begin').val() == "" && $('#select_date_end').val() != ""  ) {
				alert('il faut selectionner une date de debut')
				return;
			}			
			
			var device_id = $('.option_id:eq(0)').attr('value'),
				module_id = $( "#modules option:selected" ).attr('value'),
				scale = $( "#module_option option:selected" ).attr('value'),
				type = 'min_' + this.id + ',max_' + this.id + '',
				limit = '1024',
				subtitle = this.name,	
				real_time = 'FALSE';
				ajaxRequest(device_id,module_id,scale,type,date_begin,date_end,limit,subtitle,real_time);
			//	console.log(device_id,module_id,scale,type,date_begin,date_end,limit,subtitle,real_time)
	});	
	

    $( "#select_date_begin" ).datepicker( "option",
        $.datepicker.regional['fr']
	);
	
    $( "#select_date_end" ).datepicker( "option",
        $.datepicker.regional['fr']
	);
	  
	
	$('#select_date_begin').datepicker({
			format: 'd/m/Y',
			timepicker:false,
			onClose: function(dateString) {
				var myDate = $('#select_date_begin' ).datepicker('getDate') / 1000 ;
				console.log( myDate );
				$('#timestamp_start').attr({value : myDate});
				
			}
		}
	);
	
	
	$('#select_date_end').datepicker({
			format: 'd/m/Y',
			timepicker:false,
			onClose: function(dateString) {
				var myDate = $('#select_date_end' ).datepicker('getDate') / 1000 ; 
				console.log( myDate );
				$('#timestamp_end').attr({value : myDate});
				
			}
		}
	);
	
		
	
	function createGraph(data_module) {
		Highcharts.getSVG = function(charts) {
			var svgArr = [],
				top = 0,
				width = 0;
		
			$.each(charts, function(i, chart) {
				var svg = chart.getSVG();
				svg = svg.replace('<svg', '<g transform="translate(0,' + top + ')" ');
				svg = svg.replace('</svg>', '</g>');
		
				top += chart.chartHeight;
				width = Math.max(width, chart.chartWidth);
		
				svgArr.push(svg);
			});
		
			return '<svg height="'+ top +'" width="' + width + '" version="1.1" xmlns="http://www.w3.org/2000/svg">' + svgArr.join('') + '</svg>';
		};
	
	
		Highcharts.exportCharts = function(charts, options) {
			var form
				svg = Highcharts.getSVG(charts);
		
			// merge the options
			options = Highcharts.merge(Highcharts.getOptions().exporting, options);
		
			// create the form
			form = Highcharts.createElement('form', {
				method: 'post',
				action: options.url
			}, {
				display: 'none'
			}, document.body);
		
			// add the values
			Highcharts.each(['filename', 'type', 'width', 'svg'], function(name) {
				Highcharts.createElement('input', {
					type: 'hidden',
					name: name,
					value: {
						filename: options.filename || 'chart',
						type: options.type,
						width: options.width,
						svg: svg
					}[name]
				}, null, form);
			});
			//console.log(svg); return;
			// submit
			form.submit();
		
			// clean up
			form.parentNode.removeChild(form);
		};
	
	
	
	
		Highcharts.setOptions({
				lang: {
				months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 
					'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
				weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
				rangeSelectorFrom: "du",
			  rangeSelectorTo: "au"
			},
		
			global: {
				useUTC: false
			}
		});		
		
		
	
		var options = {
			chart: {
				renderTo: 'container1',
				zoomType: 'xy',
				type: 'line'
			},
			title: {
			  
			},
			subtitle: {
				},
				
			rangeSelector: {
					inputPosition: {
					   align: 'center'
					   },
					   
					  inputDateFormat: '%e-%m-%y',
					 buttons: [{
					   type: 'week',
					   count: 2,
					   text: '15j'
					   },       {
					   type: 'month',
					   count: 1,
					   text: '1m'
					   },       {
					   type: 'All',
					   
					   text: 'All'
					   }],
					 selected: 0,
					 
				},
				
 	
			
			tooltip: {
                crosshairs: true,
                shared: true,
				
	    	   formatter: function() {
				var s = '<b>'+ Highcharts.dateFormat('%A %e %b %Y %H:%M', this.x) +'</b>';

				$.each(this.points, function(i, point) {
					s += '<br/>' + '<span style="font-weight:bold;color:'+ this.series.color +'">' + this.series.name + ': </span>' + this.y ;
						switch (data_module.result.type_graph)
						{
						   case 'Température': var unit = '°C'
							break;
						   
						   case 'Humidité': var unit = ' %'
							break;	
							
						   case 'Pression': var unit = ' mbar'
							break;
							
						   case 'Bruit': var unit = ' dec'
						   break;	
						   
						   case 'C02': var unit = ' ppm'
						   break;		
							
						}	
					s += unit;				
				});
            
				return s;
			   }
				
	        				
				
	        },
		
			
			
		   xAxis: [{
					type: 'datetime',
					dateTimeLabelFormats: { // don't display the dummy year
						month: '%e. %b',
						year: '%b'
					}
				}],
		   yAxis: [{ // Primary yAxis
						title: {
							style:{
							}
						}
           		   }, {// secondary yAxis
						title: {
							style:{
							},
						},
						opposite: true			   
				   }],
			
			series: [{
			   }, {
			   }
					]
		};
		
		var data = data_module,
			obj = JSON.parse(data_module.result.infos),
			data1 = [];
			if (data_module.result.type_graph != 'C02') {
				data2 =[];
			}
			
			 $.each(obj, function(i,e){
				data1.push([i*1000, e[0]]);	
				if (typeof data2 != "undefined") {
				data2.push([i*1000, e[1]]);	
				}
			 })
			//if (typeof myVar != "undefined") {}
			
		options.title.text = data_module.result.name_module;
		options.subtitle.text = data_module.result.type_graph;
		
		switch (data_module.result.type_graph)
		{
		   case 'Température':
			options.series[0].name = 'Temperature max'
			options.series[0].data = data2;//max_temp
			options.series[0].color = '#DC143C'
			options.series[1].name = 'Temperature min'
			options.series[1].data = data1;//min_temp
			options.series[1].color = '#00008B'	
			options.yAxis[0].title.style.color = '#DC143C'
			options.yAxis[1].title.style.color  = '#00008B'
			
			break;
		   
		   case 'Humidité':
			options.series[0].name = 'Humidité max'
			options.series[0].data = data2;//max_temp
			options.series[0].color = '#00FFFF'
			options.series[1].name = 'Humidité min'
			options.series[1].data = data1;//min_temp
			options.series[1].color = '#7FFFD4'	
			
			break;	
			
		   case 'Pression':
			options.series[0].name = 'Pression max'
			options.series[0].data = data2;//max_temp
			options.series[0].color = '#800080'
			options.series[1].name = 'Pression min'
			options.series[1].data = data1;//min_temp
			options.series[1].color = '#D87093'	
			break;
			
		   case 'Bruit':
			options.series[0].name = 'Bruit max'
			options.series[0].data = data2;//max_temp
			options.series[0].color = '#0000CD'
			options.series[1].name = 'Bruit min'
			options.series[1].data = data1;//min_temp
			options.series[1].color = '#B0C4DE'	
			break;	
			
		   case 'C02':
			options.series[0].name = 'C02'
			options.series[0].data = data1;//max_temp
			options.series[0].color = '#191970'
			break;				
		}		



		new Highcharts.StockChart(options);	
		
	}
	
	function ajaxRequest(device_id,module_id,scale,type,date_begin,date_end,limit,subtitle,real_time) {	
			
	
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
					console.log(data)
					
					if (data.state != 'ok') {
						$('#div_alert').showAlert({message: data.result, level: 'danger'});
						return;
					}
					$(' .temp_module_left strong ').text(data.result.temperature_module);
					$(' .strong_max ').text('Max:' + data.result.max_temp_module + '°C');
					$(' .strong_min ').text('min:' + data.result.min_temp_module + '°C');
					$(' .strong_Co2 ').text(data.result.CO2_module);
					$(' .strong_hum ').text(data.result.Humidity_module);
					$(' .strong_hum ').text(data.result.Humidity_module);
					if (data.result.type != 'NAMain') {
						$(' aside .battery ').show();
						$(' .battery img ').attr('src','plugins/graphs/doc/icone/' + data.result.battery_vp);
						
					} else {
						$(' aside .battery ').hide();			
					}
					
					$(' .status img ').attr('src','plugins/graphs/doc/icone/' + data.result.rf_status);
					createGraph(data)
			
				}
			});	
	};
	



	
