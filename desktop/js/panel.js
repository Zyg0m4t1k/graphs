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
	$("ul.nav-tabs li:first").addClass("active");
	
	$(".btn-group button:first").addClass("active");
	
	$("#return_home").click(function() {
		$('.menu_list').show();
		$('.graph').show();
		$('#container_stat').empty();
	});
	
	$("#history a").click(function() {
	
		$('.menu_list').show();
		$('.graph').show();
		$('#container_stat').empty();
		var module = $('ul.nav-tabs li.active a').attr('value'),
			type = $(this).attr('value');
		var d = new Date();
		var n = d.getFullYear();
		graphYear(module, type)
	})
	

	$(".statistic_year a").click(function() {
		$(' #graphics').hide();
		$(' #statistics').show();
		var module = $(this).attr('value'),
			type = $(this).attr('title');
		getStats(module, type)
	})
	
	$("#statistic_month a").click(function() {
		$('.graph').hide();
		$('.menu_list').hide();
		var module = $(this).attr('value'),
			d = new Date(),
			n = d.getFullYear();
		console.log(module + ' ' + n)
		getStatMonth(module, n,'tem')
	})
	
	
	
	
	$(".delay").click(function() {
		var device_id = $('.option_id:eq(0)').attr('value'),
			module_id = $('ul.nav-tabs li.active a').attr('value');
		current_scale = $(this).attr('id');
		switch ($(this).attr('name')) {
			case 'day':
				var current_begin = parseInt(Math.round(+new Date() / 1000) - 24 * 3600),
					current_end = Math.round(+new Date() / 1000);
				ajaxRequest(device_id, module_id, current_scale, type, current_begin, current_end, limit, subtitle, real_time);
				break;
			case 'week':
				var current_begin = parseInt(Math.round(+new Date() / 1000) - 24 * 3600 * 7),
					current_end = Math.round(+new Date() / 1000);
				ajaxRequest(device_id, module_id, current_scale, type, current_begin, current_end, limit, subtitle, real_time);
				break;
			case 'month':
				var current_begin = parseInt(Math.round(+new Date() / 1000) - 24 * 3600 * 30),
					current_end = Math.round(+new Date() / 1000);
				ajaxRequest(device_id, module_id, current_scale, type, current_begin, current_end, limit, subtitle, real_time);
				break;
			case 'year':
				var current_begin = Math.round(+new Date(new Date().getFullYear(), 0, 1) / 1000),
					current_end = Math.round(+new Date() / 1000);
				ajaxRequest(device_id, module_id, current_scale, type, current_begin, current_end, limit, subtitle, real_time, info = false);
				break;
	
		}
		$(this).addClass('active').siblings().removeClass('active');
	});
	
	$('ul.nav-tabs li a').click(function(e) {
		var device_id = $('.option_id:eq(0)').attr('value'),
			module_id = $(this).attr('value'),
			scale = '1hour',
			type = 'min_temp,max_temp',
			begin_date = current_begin,
			end_date = current_end,
			limit = '1024',
			subtitle = 'Température';
		ajaxRequest(device_id, module_id, scale, type, begin_date, end_date, limit, subtitle, real_time, info = true);
		$('ul.nav-tabs li.active').removeClass('active')
		$(this).parent('li').addClass('active')
		$('.btn-group button').removeClass('active');
		$(".btn-group button:first").addClass("active");
		$('#select_date_begin').val('');
		$('#select_date_end').val('');
	
	})
	
	$("#valid_time").click(function() {
		if ($('#select_date_begin').val() == "" && $('#select_date_end').val() == "") {
			var date_begin = parseInt(Math.round(+new Date() / 1000) - 24 * 3600),
				date_end = Math.round(+new Date());
		}
		if ($('#select_date_begin').val() != "" && $('#select_date_end').val() != "") {
			var date_begin = $('#timestamp_start').val(),
				date_end = $('#timestamp_end').val();
		}
		if ($('#select_date_begin').val() != "" && $('#select_date_end').val() == "") {
			var date_begin = $('#timestamp_start').val();
			date_end = parseInt(Math.round(date_begin) + 24 * 3600)
		}
		if ($('#select_date_begin').val() == "" && $('#select_date_end').val() != "") {
			$('#div_alert').showAlert({
				message: 'Il faut choisir une date de début',
				level: 'danger'
			});
			return;
		}
		var period = parseInt(Math.round($('#timestamp_end').val() - $('#timestamp_start').val()));
		if (period > 15552000) {
			var current_scale = '1week';
		} else if (period > 7776000) {
			var current_scale = '1day';
	
		} else if (period > 2592000) {
			var current_scale = '3hours';
		} else {
			var current_scale = '3hours';
		}
		var device_id = $('.option_id:eq(0)').attr('value'),
			module_id = $('ul.nav-tabs li.active a').attr('value'),
			type = 'min_temp,max_temp',
			limit = '1024',
			subtitle = 'Température';
		ajaxRequest(device_id, module_id, current_scale, type, date_begin, date_end, limit, subtitle, real_time, info = false);
		$('.btn-group button').removeClass('active');
	});
	
	$(".data").click(function() {
		if ($('.btn-group button.active').attr('name')) {
			switch ($('.btn-group button.active').attr('name')) {
				case 'day':
					var current_begin = parseInt(Math.round(+new Date() / 1000) - 24 * 3600),
						current_end = Math.round(+new Date() / 1000),
						current_scale = '1hour';
					break;
				case 'week':
					var current_begin = parseInt(Math.round(+new Date() / 1000) - 24 * 3600 * 7),
						current_end = Math.round(+new Date() / 1000)
					current_scale = '3hours';
					break;
				case 'month':
					var current_begin = parseInt(Math.round(+new Date() / 1000) - 24 * 3600 * 30),
						current_end = Math.round(+new Date() / 1000),
						current_scale = '1day'
					break;
				case 'year':
					var current_begin = Math.round(+new Date(new Date().getFullYear(), 0, 1) / 1000),
						current_end = Math.round(+new Date() / 1000),
						current_scale = '1week';
					break;
			}
		} else {
			if ($('#select_date_begin').val() == "" && $('#select_date_end').val() == "") {
				var current_begin = parseInt(Math.round(+new Date() / 1000) - 24 * 3600),
					current_end = Math.round(+new Date());
			}
			if ($('#select_date_begin').val() != "" && $('#select_date_end').val() != "") {
				var current_begin = $('#timestamp_start').val(),
					current_end = $('#timestamp_end').val();
			}
			if ($('#select_date_begin').val() != "" && $('#select_date_end').val() == "") {
				var current_begin = $('#timestamp_start').val();
				current_end = parseInt(Math.round(date_begin) + 24 * 3600)
			}
	
			if ($('#select_date_begin').val() == "" && $('#select_date_end').val() != "") {
				$('#div_alert').showAlert({
					message: 'Il faut choisir une date de début',
					level: 'danger'
				});
				return;
			}
			var period = parseInt(Math.round($('#timestamp_end').val() - $('#timestamp_start').val()));
			if (period > 15552000) {
				var current_scale = '1week';
			} else if (period > 7776000) {
				var current_scale = '1day';
	
			} else if (period > 2592000) {
				var current_scale = '3hours';
			} else {
				var current_scale = '3hours';
			}
		}
		var device_id = $('.option_id:eq(0)').attr('value'),
			module_id = module_id = $('ul.nav-tabs li.active a').attr('value'),
			type = 'min_' + this.id + ',max_' + this.id + '',
			subtitle = this.name;
		ajaxRequest(device_id, module_id, current_scale, type, current_begin, current_end, limit, subtitle, real_time, info = false);
	});
	
	$("#select_date_begin").datepicker("option",
		$.datepicker.regional['fr']
	);
	
	$("#select_date_end").datepicker("option",
		$.datepicker.regional['fr']
	);
	
	$('#select_date_begin').datepicker({
		format: 'd/m/Y',
		timepicker: false,
		onClose: function(dateString) {
			var myDate = $('#select_date_begin').datepicker('getDate') / 1000;
			$('#timestamp_start').attr({
				value: myDate
			});
	
		}
	});
	
	$('#select_date_end').datepicker({
		format: 'd/m/Y',
		timepicker: false,
		onClose: function(dateString) {
			var myDate = $('#select_date_end').datepicker('getDate') / 1000;
			$('#timestamp_end').attr({
				value: myDate
			});
		}
	});
	
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
		return '<svg height="' + top + '" width="' + width + '" version="1.1" xmlns="http://www.w3.org/2000/svg">' + svgArr.join('') + '</svg>';
	};
	
	Highcharts.exportCharts = function(charts, options) {
		var form
		svg = Highcharts.getSVG(charts);
		options = Highcharts.merge(Highcharts.getOptions().exporting, options);
		form = Highcharts.createElement('form', {
			method: 'post',
			action: options.url
		}, {
			display: 'none'
		}, document.body);
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
		form.submit();
		form.parentNode.removeChild(form);
	};
	
	Highcharts.setOptions({
		lang: {
			months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
				'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
			],
			weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
			rangeSelectorFrom: "du",
			rangeSelectorTo: "au"
		},
		global: {
			useUTC: false
		}
	});
	
	function createGraph(data_module) {
		var options = {
			chart: {
				renderTo: 'container',
				zoomType: 'xy',
				type: 'spline'
			},
			title: {
			},
			subtitle: {},
			rangeSelector: {
				inputEnabled: false
			},
			legend: {
				enabled: true,
				shadow: true
			},
			plotOptions: {
				line: {
					marker: {
						enabled: false
					}
				}
			},
	
			tooltip: {
				crosshairs: true,
				shared: true,
	
				formatter: function() {
					var s = '<b>' + Highcharts.dateFormat('%A %e %b %Y %H:%M', this.x) + '</b>';
	
					$.each(this.points, function(i, point) {
						s += '<br/>' + '<span style="font-weight:bold;color:' + this.series.color + '">' + this.series.name + ': </span>' + this.y;
						switch (data_module.result.type_graph) {
							case 'Température':
								var unit = '°C'
								break;
	
							case 'Humidité':
								var unit = ' %'
								break;
	
							case 'Pression':
								var unit = ' mbar'
								break;
	
							case 'Bruit':
								var unit = ' dec'
								break;
	
							case 'C02':
								var unit = ' ppm'
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
					style: {}
				},
				labels: {
					// format: '{value} °C'
				},
	
			}, { // secondary yAxis
				title: {
					style: {}
				},
				labels: {
					// format: '{value} °C'
				},
				opposite: true
			}],
	
			series: [{}, {}]
		};
		var data = data_module,
			obj = JSON.parse(data_module.result.infos),
			data1 = [];
		if (data_module.result.type_graph != 'C02') {
			data2 = [];
		}
	
		$.each(obj, function(i, e) {
			data1.push([i * 1000, e[0]]);
			if (typeof data2 != "undefined") {
				data2.push([i * 1000, e[1]]);
			}
		})
		console.log(data1)
		options.title.text = data_module.result.name_module;
		options.subtitle.text = data_module.result.type_graph;
		switch (data_module.result.type_graph) {
			case 'Température':
				options.series[0].name = 'Temperature max'
				options.series[0].data = data2; //max_temp
				options.series[0].color = '#DC143C'
				options.series[1].name = 'Temperature min'
				options.series[1].data = data1; //min_temp
				options.series[1].color = '#00008B'
				options.yAxis[0].title.style.color = '#DC143C'
				options.yAxis[1].title.style.color = '#00008B'
				options.yAxis[0].title.text = 'Temperature max';
				options.yAxis[1].title.text = 'Temperature min';
				options.yAxis[0].labels.format = '{value} °C';
				break;
			case 'Humidité':
				options.series[0].name = 'Humidité max'
				options.series[0].data = data2; //max_temp
				options.series[0].color = '#00FFFF'
				options.series[1].name = 'Humidité min'
				options.series[1].data = data1; //min_temp
				options.series[1].color = '#7FFFD4'
				options.yAxis[0].title.style.color = '#00FFFF'
				options.yAxis[1].title.style.color = '#7FFFD4'
				options.yAxis[0].title.text = 'Humidité max';
				options.yAxis[1].title.text = 'Humidité min';
				break;
			case 'Pression':
				options.series[0].name = 'Pression max'
				options.series[0].data = data2; //max_temp
				options.series[0].color = '#800080'
				options.series[1].name = 'Pression min'
				options.series[1].data = data1; //min_temp
				options.series[1].color = '#D87093'
				options.yAxis[0].title.style.color = '#800080'
				options.yAxis[1].title.style.color = '#D87093'
				options.yAxis[0].title.text = 'Pression max';
				options.yAxis[1].title.text = 'Pression min';
				options.yAxis[0].labels.format = '{value} mbar';
				break;
			case 'Bruit':
				options.series[0].name = 'Bruit max'
				options.series[0].data = data2; //max_temp
				options.series[0].color = '#0000CD'
				options.series[1].name = 'Bruit min'
				options.series[1].data = data1; //min_temp
				options.series[1].color = '#B0C4DE'
				options.yAxis[0].title.style.color = '#0000CD'
				options.yAxis[1].title.style.color = '#B0C4DE'
				options.yAxis[0].title.text = 'Bruit max';
				options.yAxis[1].title.text = 'Bruit min';
				options.yAxis[0].labels.format = '{value} dec';
				break;
			case 'C02':
				options.series[0].name = 'C02'
				options.series[0].data = data1; //max_temp
				options.series[0].color = '#191970'
				break;
		}
		$('#title_name').empty().append('Graphique Netatmo');
		new Highcharts.Chart(options)
	}
	
	function ajaxRequest(device_id, module_id, scale, type, date_begin, date_end, limit, subtitle, real_time, info) {
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
			error: function(request, status, error) {
				handleAjaxError(request, status, error);
			},
			success: function(data) {
				if (info) {
					if (data.state != 'ok') {
						$('#div_alert').showAlert({
							message: data.result,
							level: 'danger'
						});
						return;
					}
					$('.temp_module_left,.temp_max_left,.temp_min_left,.humidity_left').empty();
					$(' .temp_module_left').append(data.result.temperature_module);
					$(' .temp_max_left ').append(data.result.max_temp_module);
					$(' .temp_min_left ').append(data.result.min_temp_module);
					$(' .humidity_left').append(data.result.Humidity_module);
					if (data.result.type != 'NAMain') {
						$(' aside .battery ').show();
						$(' .battery img ').attr('src', 'plugins/graphs/doc/icone/' + data.result.battery_vp);
					} else {
						$(' aside .battery ').hide();
					}
					$(' .status img ').attr('src', 'plugins/graphs/doc/icone/' + data.result.rf_status);
				}
				var type_station = data.result.type;
				if (type_station == 'NAModule1') {
					$(' .pressure,.noise,.co2 ').hide();
					$('aside .battery').show();
				}
				if (type_station == 'NAModule4') {
					$(' .pressure,.noise ').hide();
					$(' .co2,aside .battery  ').show();
				}
				if (type_station == 'NAMain') {
					$('.pressure,.noise,.co2').show();
					$('aside .battery').hide();
				}				
				createGraph(data)
			}
		});
	};
	
	function graphYear(module, type) {
		$.ajax({
			type: 'GET',
			url: '/plugins/graphs/data/year.json',
			dataType: 'json',
			async: false,
			success: function(data) {
				result = data;
			}
		});
		var options = {
			chart: {
				renderTo: 'container',
				zoomType: 'xy',
				type: 'spline',
			},
			title: {
			},
			subtitle: {},
			rangeSelector: {
				inputEnabled: false
			},
			legend: {
				enabled: true,
				shadow: true
			},
			xAxis: {
				categories: ['Jan',
					'Fév',
					'Mar',
					'Avr',
					'Mai',
					'Jui',
					'Jui',
					'Aou',
					'Sep',
					'Oct',
					'Nov',
					'Déc'
				],
				min: 0,
				max: 11,
				crosshair: true
			},
			yAxis: { // Primary yAxis
				labels: {
					// format: '{value} °C'
				},
				title: {}
			},
			tooltip: {
				shared: true
			}
		};
		var seriesData = [];
		for (i = 0; i < result.length; i++) {
			if (result[i]['module_id'] == module) {
				$('#title_name').empty().append('Graphique Annuel');
				options.title.text = result[i]['name'];
				switch (type) {
					case 'temp':
						var title = "Température",
							prefix = "°C"
						break;
					case 'hum':
						var title = "Humidité",
							prefix = "%"
						break;
					case 'pressure':
						var title = "Pression",
							prefix = "mbar"
						break;
					case 'noise':
						var title = "Bruit",
							prefix = "dec"
						break;
					case 'co2':
						var title = "C02",
							prefix = "ppm"
						break;
				}
				options.yAxis.title.text = title;
				options.yAxis.labels.format = '{value} ' + prefix;
				var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
				for (var key in result[i]['year']) {
					var obj = result[i]['year'][key];
					var series = [];
					var j = 0;
					$.each(obj, function(i, e) {
						var date = new Date(i * 1000);
						if (monthNames[date.getMonth()] == monthNames[j]) {
							switch (type) {
								case 'temp':
									series.push([e[0]])
									break;
								case 'hum':
									series.push([e[1]])
									break;
								case 'pressure':
									series.push([e[2]])
									break;
								case 'noise':
									series.push([e[3]])
									break;
								case 'co2':
									series.push([e[4]])
									break;
							}
						} else {
							var k = date.getMonth();
							for (j = 0; j < k; j++) {
								series.push(null)
							}
							$.each(obj, function(i, e) {
								switch (type) {
									case 'temp':
										series.push([e[0]])
										break;
									case 'hum':
										series.push([e[1]])
										break;
									case 'pressure':
										series.push([e[2]])
										break;
									case 'noise':
										series.push([e[3]])
										break;
									case 'co2':
										series.push([e[4]])
										break;
								}
	
							})
							return false;
						}
						j++;
					})
					seriesData.push({
						name: key,
						tooltip: {
							valueSuffix: ' ' + prefix
						},
						type: 'spline',
						data: series
					});
				}
			}
		}
		options.series = seriesData;
		var chart = new Highcharts.Chart(options);
	}
	
	function colorTemp(data) {
		switch (true) {
			case (data > 27):
				return "#5F0000";
				break;
			case (data > 24):
				return "#780A0F";
				break;
			case (data > 21):
				return "#A60F14";
				break;
			case (data > 18):
				return "#CC181E";
				break;
			case (data > 15):
				return "#FC9272";
				break;
			case (data > 12):
				return "#FB6A4A";
				break;
			case (data > 9):
				return "#FC9272";
				break;
			case (data > 6):
				return "#FCBBAA";
				break;
			case (data > 3):
				return "##FFE0E0";
				break;
			case (data > 0):
				return "#FFF0F5";
				break;
			case (data > -2):
				return "#AADCE6";
				break;
			case (data > -4):
				return "#DBF5FF";
				break;
			case (data > -6):
				return "#AADCE6";
				break;
			case (data > -8):
				return "#78BFD6";
				break;
			case (data > -10):
				return "#5AA0CD";
				break;
			case (data > -12):
				return "#4292C7";
				break;
			case (data > -14):
				return "#072F6B";
				break;
			case (data > -16):
				return "#08529C";
				break;
			case (data > -18):
				return "#FFFFFF";
				break;
			default:
				return "#071E46";
				break;
		}
	}
	
	function colorHumidity(data) {
		switch (true) {
			case (data == 100):
				return "#0000C2";
				break;
			case (data > 90):
				return "#303EFF";
				break;
			case (data > 75):
				return "#3C83FF";
				break;
			case (data > 60):
				return "#33C1FF";
				break;
			case (data > 50):
				return "#00FFFF";
				break;
			case (data > 40):
				return "#80FFC7";
				break;
			case (data > 35):
				return "#FFFFBE";
				break;
			case (data > 30):
				return "#FFFF00";
				break;
			case (data > 25):
				return "#FEBD00";
				break;
			case (data > 20):
				return "#FF9000";
				break;
			case (data > 15):
				return "#FE5900";
				break;
			case (data > 10):
				return "#6E000D";
				break;
			case (data > 5):
				return "#A64443";
				break;
			default:
				return "#6E000D";
				break;
		}
	}
	
	function getStatMonth(module, year , type) {
		$('#container_stat').empty();
		var monthNames = ['Jan', 'fev', 'mar', 'avr', 'mai', 'juin', 'jui', 'aou', 'sep', 'oct', 'nov', 'dec'],
			NbrCol = 13, // nombre de colonnes
			NbrLigne = 31, // nombre de lignes
			div = "";
		div += '<table border="1" width="100%"><thead>';
		div += '<tr><th rowspan="2" style="background:#CCCCCC;">dates</th>';
		for (i = 0; i < monthNames.length; i++) {
			div += '<th colspan="2" style="background:#CCCCCC;"> ' + monthNames[i] + '</th>';
		}
		div += '<select class="form-control pull-right" id="sel_type" style="width: 140px;">';
		if (type =='tem') {
		div += '<option value="tem">{{Température}}</option><option value="hum">{{humidité}}</option></select>';
		} else {
		div += '<option value="hum">{{humidité}}</option><option value="tem">{{Température}}</option></select>';	
		}
		div += '</tr>';
		
		
		div += '<tr>';
		for (j = 1; j < 25; j++) {
			if (j / 2 == Math.round(j / 2)) {
				div += '<td>max</td>';
			} else {
				div += '<td>min</td>';
			}
		}
		div += '</tr>';
		for (i = 1; i <= NbrLigne; i++) {
			div += '<tr class="' + i + '">';
			div += '<td > ' + i + '</td>';
			for (j = 1; j < 25; j++) {
				div += '<td ></td>';
			}
			div += '</tr>';
		}
	
		div += '</table >';
		$("#container_stat").append(div);
		$.ajax({
			type: 'POST',
			url: 'plugins/graphs/core/ajax/graphs.ajax.php',
			data: {
				action: 'getData',
				type: 'month'
			},
			dataType: 'json',
			error: function(request, status, error) {
				handleAjaxError(request, status, error);
			},
			success: function(data) {
				if (data.state != 'ok') {
					$('#div_alert').showAlert({
						message: data.result,
						level: 'danger'
					});
					return;
				}
				result = data.result[0];
				for (i = 0; i < result.length; i++) {
					if (result[i].module_id == module) {
						$('#title_name').empty().append('Statistiques Mensuelles des températures pour ' + result[i]['name'] + ' pour l\'année : ' + year);
						var links = Object.keys(result[i].year).reverse();
						var menu = '<div class="btn-group menu_year">';
						for (var j = 0; j < links.length; j++) {
							menu += '<button type="button" class="btn btn-primary menu_year" value="' + module + '" name="' + links[j] + '">' + links[j] + '</button>';
						}
						menu += '</div>';
						$('#container_stat').prepend(menu);
						var obj = result[i]['year'][year];
						console.log(obj)
						$.each(obj, function(i, e) {
							var d = new Date(i * 1000);
								n = d.getDate();
								m = d.getMonth(),
								q = (m + 1) * 2,
								r = q - 1;

							switch (type) {
								case 'tem': data_min=e[6];data_max=e[5],colorMin = colorTemp(e[6]),colorMax = colorTemp(e[5]);break;
								case 'hum': data_min=e[8];data_max=e[7],colorMin = colorHumidity(e[8]),colorMax = colorHumidity(e[7]);break;
							}
							$("table tr." + n).find("td:eq(" + r + ")").append(data_min);
							$("table tr." + n).find("td:eq(" + r + ")").css("background-color", colorMin);
							$("table tr." + n).find("td:eq(" + q + ")").append(data_max);
							$("table tr." + n).find("td:eq(" + q + ")").css("background-color", colorMax);
						})
					}
				}
				$(".menu_year button").click(function() {
						year = $(this).attr('name');
					getStatMonth(module, year, type)
				})
				$( "#sel_type" ).change(function() {
					console.log($(this).val());
				    getStatMonth(module, year, $(this).val());
				});					
				
			}
		});
	}
	
	
	function getStats(module, type) {
		$('#container_stat').empty();
		$.ajax({
			type: 'POST',
			url: 'plugins/graphs/core/ajax/graphs.ajax.php',
			data: {
				action: 'getData',
				type: 'year'
			},
			dataType: 'json',
			error: function(request, status, error) {
				handleAjaxError(request, status, error);
			},
			success: function(data) {
				if (data.state != 'ok') {
					$('#div_alert').showAlert({
						message: data.result,
						level: 'danger'
					});
					return;
				}
				result = data.result[0];
				var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
				if (type == "tem") {
					var info = ['Température moyenne', 'Température maximum', 'Température minimum'];
				} else {
					var info = ['Humidité moyenne', 'Humidité maximum', 'Humidité minimum'];
				}
				var data = ['moy', 'maxi', 'mini'];
				for (var k = 0; k < data.length; k++) {
					div = '<div>';
					div += '<table id="' + data[k] + '" class="table_stat" border="1" style="width:100%;margin:auto"><caption>' + info[k] + '</caption><thead><tr><th></th><th style="text-align:center">jan</th><th>Fev</th><th>Mar</th><th>Avr</th><th>mai</th><th>Jui</th><th>Jui</th><th>Aou</th><th>Sept</th><th>Oct</th><th>Nov</th><th>Déc</th><tr></thead>';
					div += '<tbody>';
					var year = Object.keys(result[0]['year']).reverse();
					for (var i = 0; i < year.length; i++) {
						div += '<tr class="' + year[i] + '"><th >' + year[i] + '</th><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
					}
					$('.graph').hide();
					$('.menu_list').hide();
					$('#container_stat').append(div);
					for (i = 0; i < result.length; i++) {
						if (result[i]['module_id'] == module) {
							$('#title_name').empty().append('Statistique annuelle pour ' + result[i]['name']);
							for (var j = 0; j < year.length; j++) {
								var obj = result[i]['year'][year[j]];
								$.each(obj, function(i, e) {
									var d = new Date(i * 1000);
									var n = d.getMonth();
									switch (k) {
										case 0:
											if (type == "tem") {
												var temp = e[0],
													color = colorTemp(temp);
											} else {
												var temp = e[1],
													color = colorHumidity(temp);
											}
											break;
										case 1:
											if (type == "tem") {
												var temp = e[5],
													color = colorTemp(temp);
											} else {
												var temp = e[7],
													color = colorHumidity(temp);
											}
											break;
										case 2:
											if (type == "tem") {
												var temp = e[6],
													color = colorTemp(temp);
											} else {
												var temp = e[8],
													color = colorHumidity(temp);
											}
											break;
									}
									$('#' + data[k] + ' .' + year[j] + '').find('td:eq(' + n + ')').append(temp);
									$('#' + data[k] + ' .' + year[j] + '').find('td:eq(' + n + ')').css("background-color", color);
								})
							}
						}
					}
				}
			}
		});
	}