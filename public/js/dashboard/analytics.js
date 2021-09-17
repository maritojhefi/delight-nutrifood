(function($) {
    /* "use strict" */
	
 var dzChartlist = function(){
	
	var screenWidth = $(window).width();	
		var lineChart2 = function(){
		var options = {
          series: [{
          name: 'Income',
          data: [420, 550, 650, 220, 650, 470, 310, 700, 290, 470]
        }, {
          name: 'Expenses',
          data: [270, 650, 201, 90, 250, 750, 470, 550, 650, 270]
        }],
          chart: {
          type: 'bar',
          height: 250,
		  toolbar: {
            show: false
          },
			
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
		grid:{
			borderColor:'transparent'
		},
		legend: {
			show: false,
			fontSize: '12px',
			fontWeight: 300,
			
			labels: {
				colors: 'black',
			},
			position: 'bottom',
			horizontalAlign: 'center', 	
			markers: {
				width: 19,
				height: 19,
				strokeWidth: 0,
				radius: 19,
				strokeColor: '#fff',
				fillColors:['#FFFFFF','#22DBBA'],
				offsetX: 0,
				offsetY: 0
			}
		},
		yaxis: {
			show: false
		},
        stroke: {
          show: true,
          width: 5,
          colors: ['transparent']
        },
        xaxis: {
          categories: ['06', '07', '08', '09', '10','11','12','13','14','15'],
		  labels: {
		   style: {
			  colors: '#fff',
			  fontSize: '14px',
			  fontFamily: 'Poppins',
			  fontWeight: 100,
			  
			},
		  },
		  axisTicks:{
			  show:false,
		  },
		   axisBorder:{
			   show:false,
		   },
        },
		colors:['#FFFFFF','#FB3E7A'],
        fill: {
		  colors:['#FFFFFF','#FB3E7A'],
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "$ " + val + " thousands"
            }
          }
        },
		responsive: [{
			breakpoint: 575,
			options: {
				chart: {
					height:200,
				},
				stroke: {
				  width: 3
				},
				plotOptions: {
				  bar: {
					columnWidth: '75%'
				  }
				}
			},
		}]
        };

        var chart = new ApexCharts(document.querySelector("#line-chart-2"), options);
        chart.render();	
	}
	
	var salesRavenue = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [100,200, 100, 300, 200, 400, 200, 300,100, 300],
				//radius: 12,	
			}, 				
		],
			chart: {
			type: 'line',
			height: 300,
			toolbar: {
				show: false,
			},
		},
		
		colors:['#0E8A74'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: true,
		},
		stroke: {
		  show: true,
		  width: 6,
		  curve:'smooth',
		  colors:['#0E8A74'],
		},
		
		grid: {
			show:true,
			borderColor: '#C8C8C8',
			strokeDashArray: 4,
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'Jun', 'Aug', 'Sep', 'Oct','Nov' ],
			axisBorder: {
				show: true,
			},
			axisTicks: {
				show: true
			},
			labels: {
				show: true,
				style: {
					fontSize: '14px',
					colors:'#759791',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: true,
			labels:{
				 offsetX: -10,
				 formatter: function (value) {
				  return value + "k";
				},
				 style:{
					fontWeight:200,
					 colors:'#759791',
					 fontSize: '14px',
				 },
			}
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		
		
		tooltip: {
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "k" + val + " thousands"
				}
			}
		},
		responsive: [{
			breakpoint: 575,
			options: {
				chart: {
					height:200,
				},
			},
		}]
		};

		var chartBar1 = new ApexCharts(document.querySelector("#salesRavenue"), options);
		chartBar1.render();
	 
	}
	var salesRavenueone = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [100,200, 100, 300, 200, 400, 200, 300,100, 300],
				//radius: 12,	
			}, 				
		],
			chart: {
			type: 'line',
			height: 300,
			toolbar: {
				show: false,
			},
		},
		
		colors:['#0E8A74'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: true,
		},
		stroke: {
		  show: true,
		  width: 6,
		  curve:'smooth',
		  colors:['#0E8A74'],
		},
		
		grid: {
			show:true,
			borderColor: '#C8C8C8',
			strokeDashArray: 4,
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'Jun', 'Aug', 'Sep', 'Oct','Nov' ],
			axisBorder: {
				show: true,
			},
			axisTicks: {
				show: true
			},
			labels: {
				show: true,
				style: {
					fontSize: '14px',
					colors:'#759791',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: true,
			labels:{
				 offsetX: -10,
				 formatter: function (value) {
				  return value + "k";
				},
				 style:{
					fontWeight:200,
					 colors:'#759791',
					 fontSize: '14px',
				 },
			}
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		
		
		tooltip: {
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "k" + val + " thousands"
				}
			}
		},
		responsive: [{
			breakpoint: 575,
			options: {
				chart: {
					height:200,
				},
			},
		}]
		};

		var chartBar1 = new ApexCharts(document.querySelector("#salesRavenueone"), options);
		chartBar1.render();
	 
	}
	var salesRavenuetwo = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [100,200, 100, 300, 200, 400, 200, 300,100, 300],
				//radius: 12,	
			}, 				
		],
			chart: {
			type: 'line',
			height: 300,
			toolbar: {
				show: false,
			},
		},
		
		colors:['#0E8A74'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: true,
		},
		stroke: {
		  show: true,
		  width: 6,
		  curve:'smooth',
		  colors:['#0E8A74'],
		},
		
		grid: {
			show:true,
			borderColor: '#C8C8C8',
			strokeDashArray: 4,
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'Jun', 'Aug', 'Sep', 'Oct','Nov' ],
			axisBorder: {
				show: true,
			},
			axisTicks: {
				show: true
			},
			labels: {
				show: true,
				style: {
					fontSize: '14px',
					colors:'#759791',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: true,
			labels:{
				 offsetX: -10,
				 formatter: function (value) {
				  return value + "k";
				},
				 style:{
					fontWeight:200,
					 colors:'#759791',
					 fontSize: '14px',
				 },
			}
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		
		
		tooltip: {
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "k" + val + " thousands"
				}
			}
		},
		responsive: [{
			breakpoint: 575,
			options: {
				chart: {
					height:200,
				},
			},
		}]
		};

		var chartBar1 = new ApexCharts(document.querySelector("#salesRavenuetwo"), options);
		chartBar1.render();
	 
	}
	var chartCircle = function(){
		var optionsCircle = {
			chart: {
				type: 'radialBar',
				height: 400,
				offsetY: 0,
				offsetX: 0,
				sparkline: {
					enabled: true,
				},
			},
			plotOptions: {
				radialBar: {
					size: undefined,
					inverseOrder: false,
					hollow: {
						margin: 0,
						size: '15%',
						background: 'transparent',
					},
			  
					track: {
						show: true,
						background: '#e1e5ff',
						strokeWidth: '10%',
						opacity: 1,
						margin: 15, // margin is in pixels
					},
				},
			},
			
		fill: {
			opacity: 1
        },
		stroke: {
			lineCap:'round',
		},
		colors:['#0E8A74', '#FB3E7A', '#FF7B31','#C8C8C8'],
		series: [80, 50, 75,30],
		labels: ['Ticket A', 'Ticket B', 'Ticket C','Ticket D'],
			
			legend: {
				fontSize: '14px',  
				show: true,
				fontWeight:600,
				position: 'bottom',
				markers:{
					radius:0,
				}
			},
			responsive: [{
			breakpoint: 575,
			options: {
				chart: {
					height:350,
				},
				plotOptions:{
					radialBar:{
						track:{
							margin: 15,
						}
					},
				},
			},
		}]
		
		}

		var chartCircle1 = new ApexCharts(document.querySelector('#chartCircle'), optionsCircle);
		chartCircle1.render();
	}
	var donutChart1 = function(){
		$("span.donut1").peity("donut", {
			width: "110",
			height: "110"
		});
	}
	var lineChart = function(){
		var optionsTimeline = {
			chart: {
				type: "bar",
				height: 350,
				stacked: true,
				toolbar: {
					show: false
				},
				sparkline: {
					//enabled: true
				},
				offsetX:0,
			},
			series: [
				 {
					name: "New Clients",
					data: [180, 150, 200, 100, 80, 70, 40]
				}
			],
			
			plotOptions: {
				bar: {
					columnWidth: "25%",
					endingShape: "rounded",
					startingShape: "rounded",
					
					colors: {
						backgroundBarColors: ['#F8F8F8', '#F8F8F8', '#F8F8F8', '#F8F8F8','#F8F8F8','#F8F8F8','#F8F8F8','#F8F8F8'],
						backgroundBarOpacity: 1,
						backgroundBarRadius: 5,
					},

				},
				distributed: true
			},
			colors:['#FB3E7A'],
			grid: {
				borderColor:'#F8F8F8'
			},
			legend: {
				show: false
			},
			fill: {
			  opacity: 1
			},
			dataLabels: {
				enabled: false,
				colors: ['#000'],
				dropShadow: {
				  enabled: true,
				  top: 1,
				  left: 1,
				  blur: 1,
				  opacity: 1
			  }
			},
			
			xaxis: {
			 categories: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
			  labels: {
			   style: {
				  colors: '#759791',
				  
				  fontSize: '14px',
				  fontFamily: 'poppins',
				  fontWeight: 400,
				  cssClass: 'apexcharts-xaxis-label',
				},
			  },
			  crosshairs: {
				show: false,
			  },
			  axisBorder: {
				  show: false,
				},
			},
			
			yaxis: {
				show: false
			},
			
			tooltip: {
				x: {
					show: true
				}
			},
			responsive: [{
				breakpoint: 1600,
				options: {
					chart: {
						height:300,
					},
					plotOptions: {
						bar: {
							columnWidth: "35%",
						}
					}
				},
			}]
		};
		var chartTimelineRender =  new ApexCharts(document.querySelector("#lineChart"), optionsTimeline);
		 chartTimelineRender.render();	
	}
	var pieChart = function(){
		 var options = {
          series: [35, 55, 10],
          chart: {
          type: 'donut',
		  width:300,
        },
		dataLabels: {
          enabled: false
        },
		stroke: {
          width: 7,
        },
		colors:['#0E8A74', '#FB3E7A', '#C8C8C8'],
		legend: {
              position: 'bottom',
			  show:false
            },
        responsive: [{
          breakpoint: 1600,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom',
			  show:false
            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#pieChart"), options);
        chart.render();
    
	}
	
	var widgetChart1 = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [500, 600, 500, 600, 500, 600, 500, 600,500, 600,500,300],
				//radius: 12,	
			}, 				
		],
			chart: {
			type: 'area',
			height: 75,
			toolbar: {
				show: false,
			},
			zoom: {
				enabled: false
			},
			sparkline: {
				enabled: true
			}
			
		},
		
		colors:['#0E8A74'],
		dataLabels: {
		  enabled: false,
		},
		markers: {
			shape: "circle",
			colors:['#FB3E7A'],
			hover: {
			  size: 10,
			}
		},

		legend: {
			show: false,
		},
		stroke: {
		  show: true,
		  width: 3,
		  curve:'smooth',
		  colors:['#0E8A74'],
		},
		
		grid: {
			show:false,
			borderColor: '#eee',
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'Jun', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',],
			axisBorder: {
				show: false,
			},
			axisTicks: {
				show: false
			},
			labels: {
				show: false,
				style: {
					fontSize: '12px',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: false,
		},
		fill: {
			type:"solid",
			opacity: 1,
			colors:'#0E8A74'
		},
		tooltip: {
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "$" + val + " thousands"
				}
			}
		}
		};

		var chartBar1 = new ApexCharts(document.querySelector("#widgetChart1"), options);
		chartBar1.render();
	 
	}
	var widgetChart2 = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [100,200, 100, 300, 200, 400, 200, 300,100, 300,200,300],
				//radius: 12,	
			}, 				
		],
			chart: {
			type: 'line',
			height: 70,
			toolbar: {
				show: false,
			},
			zoom: {
				enabled: false
			},
			sparkline: {
				enabled: true
			}
			
		},
		
		colors:['#0E8A74'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: false,
		},
		stroke: {
		  show: true,
		  width: 6,
		  curve:'smooth',
		  colors:['#0E8A74'],
		},
		
		grid: {
			show:false,
			borderColor: '#eee',
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'Jun', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',],
			axisBorder: {
				show: false,
			},
			axisTicks: {
				show: false
			},
			labels: {
				show: false,
				style: {
					fontSize: '12px',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: false,
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		tooltip: {
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "$" + val + " thousands"
				}
			}
		}
		};

		var chartBar1 = new ApexCharts(document.querySelector("#widgetChart2"), options);
		chartBar1.render();
	 
	}
	 var doughnutChart = function(){
		if(jQuery('#doughnut_chart1').length > 0 ){
			//doughut chart
			const doughnut_chart1 = document.getElementById("doughnut_chart1").getContext('2d');
			// doughnut_chart.height = 100;
			new Chart(doughnut_chart1, {
				type: 'doughnut',
				data: {
					weight: 5,	
					defaultFontFamily: 'Poppins',
					datasets: [{
						data: [35, 25, 25],
						borderWidth: 3, 
						borderColor: "rgba(255,255,255,1)",
						backgroundColor: [
							"rgba(251, 62, 122, 1)",
							"rgba(14, 138, 116, 1)",
							"rgba(255, 123, 49, 1)"
						],
						hoverBackgroundColor: [
							"rgba(251, 62, 122, 0.5)",
							"rgba(14, 138, 116, 0.5)",
							"rgba(255, 123, 49, 0.5)"
						]

					}],
					// labels: [
					//     "green",
					//     "green",
					//     "green",
					//     "green"
					// ]
				},
				options: {
					weight: 1,	
					 cutoutPercentage: 60,
					responsive: true,
					maintainAspectRatio: false
				}
			});
		}
	}
	
	/* Function ============ */
		return {
			init:function(){
			},
			
			
			load:function(){
				lineChart2();
				salesRavenue();	
				salesRavenueone();
				salesRavenuetwo();
				chartCircle();
				donutChart1();		
				lineChart();
				pieChart();
				widgetChart1();
				widgetChart2();
				doughnutChart();
				
					
			},
			
			resize:function(){
				
			}
		}
	
	}();

	jQuery(document).ready(function(){
	});
		
	jQuery(window).on('load',function(){
		setTimeout(function(){
			dzChartlist.load();
		}, 1000); 
		
	});

	jQuery(window).on('resize',function(){
		
		
	});     

})(jQuery);