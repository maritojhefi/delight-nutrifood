var randomScalingFactor = function() {
    return (Math.random() > 0.5 ? 1.0 : 1.0) * Math.round(Math.random() * 100);
  };
  
  // draws a rectangle with a rounded top
  Chart.helpers.drawRoundedTopRectangle = function(ctx, x, y, width, height, radius) {
    ctx.beginPath();
    ctx.moveTo(x + radius, y);
    // top right corner
    ctx.lineTo(x + width - radius, y);
    ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
    // bottom right	corner
    ctx.lineTo(x + width, y + height);
    // bottom left corner
    ctx.lineTo(x, y + height);
    // top left	
    ctx.lineTo(x, y + radius);
    ctx.quadraticCurveTo(x, y, x + radius, y);
    ctx.closePath();
  };
  
  Chart.elements.RoundedTopRectangle = Chart.elements.Rectangle.extend({
    draw: function() {
      var ctx = this._chart.ctx;
      var vm = this._view;
      var left, right, top, bottom, signX, signY, borderSkipped;
      var borderWidth = vm.borderWidth;
  
      if (!vm.horizontal) {
        // bar
        left = vm.x - vm.width / 2;
        right = vm.x + vm.width / 2;
        top = vm.y;
        bottom = vm.base;
        signX = 1;
        signY = bottom > top? 1: -1;
        borderSkipped = vm.borderSkipped || 'bottom';
      } else {
        // horizontal bar
        left = vm.base;
        right = vm.x;
        top = vm.y - vm.height / 2;
        bottom = vm.y + vm.height / 2;
        signX = right > left? 1: -1;
        signY = 1;
        borderSkipped = vm.borderSkipped || 'left';
      }
  
      
  
      // calculate the bar width and roundess
      var barWidth = Math.abs(left - right);
      var roundness = this._chart.config.options.barRoundness || 0.5;
      var radius = barWidth * roundness * 0.5;
      
      // keep track of the original top of the bar
      var prevTop = top;
      
      // move the top down so there is room to draw the rounded top
      top = prevTop + radius;
      var barRadius = top - prevTop;
  
      ctx.beginPath();
      ctx.fillStyle = vm.backgroundColor;
      ctx.strokeStyle = vm.borderColor;
      ctx.lineWidth = borderWidth;
  
      // draw the rounded top rectangle
      Chart.helpers.drawRoundedTopRectangle(ctx, left, (top - barRadius + 1), barWidth, bottom - prevTop, barRadius);
  
      ctx.fill();
      if (borderWidth) {
        ctx.stroke();
      }
  
      // restore the original top value so tooltips and scales still work
      top = prevTop;
    },
  });
  
  Chart.defaults.roundedBar = Chart.helpers.clone(Chart.defaults.bar);
  
  Chart.controllers.roundedBar = Chart.controllers.bar.extend({
    dataElementType: Chart.elements.RoundedTopRectangle
  });
  
  
  (function($) {
      /* "use strict" */
      
   var dzChartlist = function(){
      
      var screenWidth = $(window).width();	
      var widgetChart1 = function(){
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
          
          colors:['#FB3E7A'],
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
            colors:['#FB3E7A'],
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
            colors:'#FAC7B6'
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
      var chartBar = function(){
          if(jQuery('#widgetChart3').length > 0 ){
      
              const widgetChart3 = document.getElementById("widgetChart3").getContext('2d');
              //generate gradient
              
  
              // widgetChart1.attr('height', '100');
  
              new Chart(widgetChart3, {
                  type: 'roundedBar',
                  data: {
                      defaultFontFamily: 'Poppins',
                      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct"],
                      datasets: [
                          {
                              label: "My First dataset",
                              data: [15, 40, 55, 40, 25, 35, 40, 50, 85, 95],
                              borderColor: '#FE634E',
                              borderWidth: "0",
                              backgroundColor: '#FE634E', 
                              hoverBackgroundColor: '#FE634E'
                          }
                      ]
                  },
                  options: {
                      legend: false,
                      responsive: true, 
                      barRoundness: 1,
                      maintainAspectRatio: false,  
                      scales: {
                          yAxes: [{
                              display: false, 
                              ticks: {
                                  beginAtZero: true, 
                                  display: false, 
                                  max: 100, 
                                  min: 0, 
                                  stepSize: 10
                              }, 
                              gridLines: {
                                  display: false, 
                                  drawBorder: false
                              }
                          }],
                          xAxes: [{
                              display: false, 
                              barPercentage: 0.4, 
                              gridLines: {
                                  display: false, 
                                  drawBorder: false
                              }, 
                              ticks: {
                                  display: false
                              }
                          }]
                      }
                  }
              });
  
          }
          
          
      }
      var donutChart1 = function(){
          $("span.donut1").peity("donut", {
              width: "90",
              height: "90"
          });
      }
          
      var donutChart2 = function(){
          var options = {
            series: [45, 25, 30],
            chart: {
            type: 'donut',
            height:210,
          },
            legend:{
              show:false  
            },
            plotOptions: {
               pie: {
                  startAngle: -86,
                  donut: {
                       size: '40%',
                  }
               },
            },
            
            states: {
                  normal: {
                      filter: {
                          type: 'none',
                          value: 0,
                      }
                  },
                  hover: {
                      filter: {
                          type: 'lighten',
                          value: 0,
                      }
                  },
                  active: {
                      filter: {
                          type: 'lighten',
                          value: 0,
                      }
                  },
              },
            
            stroke:{
              width:'10'  
            },
            dataLabels: {
                formatter(val, opts) {
                  const name = opts.w.globals.labels[opts.seriesIndex]
                  return [ val.toFixed() + '%']
                },
                dropShadow: {
                  enabled: false
                },
                style: {
                  fontSize: '15px',
                  colors: ["#fff"],
                }
              },
            colors:['#0E8A74','#FB3E7A','#C8C8C8'],
          responsive: [{
            breakpoint: 1600,
            options: {
              chart: {
                height: 200
              },
            }
          }] 
          };
  
          var chart = new ApexCharts(document.querySelector("#donutChart2"), options);
          chart.render();
      }
      var donutChart3 = function(){
          var options = {
            series: [45, 25, 30],
            chart: {
            type: 'donut',
            height:210,
          },
            legend:{
              show:false  
            },
            plotOptions: {
               pie: {
                  startAngle: -86,
                  donut: {
                       size: '40%',
                  }
               },
            },
            
            states: {
                  normal: {
                      filter: {
                          type: 'none',
                          value: 0,
                      }
                  },
                  hover: {
                      filter: {
                          type: 'lighten',
                          value: 0,
                      }
                  },
                  active: {
                      filter: {
                          type: 'lighten',
                          value: 0,
                      }
                  },
              },
            
            stroke:{
              width:'10'  
            },
            dataLabels: {
                formatter(val, opts) {
                  const name = opts.w.globals.labels[opts.seriesIndex]
                  return [ val.toFixed() + '%']
                },
                dropShadow: {
                  enabled: false
                },
                style: {
                  fontSize: '15px',
                  colors: ["#fff"],
                }
              },
            colors:['#0E8A74','#FB3E7A','#C8C8C8'],
          responsive: [{
            breakpoint: 1600,
            options: {
              chart: {
                height: 200
              },
            }
          }] 
          };
  
          var chart = new ApexCharts(document.querySelector("#donutChart3"), options);
          chart.render();
      }
      var donutChart4 = function(){
          var options = {
            series: [45, 25, 30],
            chart: {
            type: 'donut',
            height:210,
          },
            legend:{
              show:false  
            },
            plotOptions: {
               pie: {
                  startAngle: -86,
                  donut: {
                       size: '40%',
                  }
               },
            },
            
            states: {
                  normal: {
                      filter: {
                          type: 'none',
                          value: 0,
                      }
                  },
                  hover: {
                      filter: {
                          type: 'lighten',
                          value: 0,
                      }
                  },
                  active: {
                      filter: {
                          type: 'lighten',
                          value: 0,
                      }
                  },
              },
            
            stroke:{
              width:'10'  
            },
            dataLabels: {
                formatter(val, opts) {
                  const name = opts.w.globals.labels[opts.seriesIndex]
                  return [ val.toFixed() + '%']
                },
                dropShadow: {
                  enabled: false
                },
                style: {
                  fontSize: '15px',
                  colors: ["#fff"],
                }
              },
            colors:['#0E8A74','#FB3E7A','#C8C8C8'],
          responsive: [{
            breakpoint: 1600,
            options: {
              chart: {
                height: 200
              },
            }
          }] 
          };
  
          var chart = new ApexCharts(document.querySelector("#donutChart4"), options);
          chart.render();
      }
      var salesChart = function(){
          var options = {
            series: [
              {
                  name: 'Net Profit',
                  data: [100,200, 100, 300, 200, 400, 200, 300,100],
                  //radius: 12,	
              }, 				
          ],
              chart: {
              type: 'line',
              height: 380,
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
              categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'Jun', 'Aug', 'Sep', 'Oct'],
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
                      colors:'#a4a7ab',
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
                  offsetX:-10,
                  formatter: function (value) {
                    return value + "k";
                  },
                    style:{
                         colors:'#a4a7ab',
                         fontSize: '14px',
                    },
               },
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
                      height:250,
                  },
              },
          }]
          };
  
          var chartBar1 = new ApexCharts(document.querySelector("#salesChart"), options);
          chartBar1.render();
       
      }
      var salesChart1 = function(){
          var options = {
            series: [
              {
                  name: 'Net Profit',
                  data: [100,200, 100, 300, 200, 400, 200, 300,100],
                  //radius: 12,	
              }, 				
          ],
              chart: {
              type: 'line',
              height: 380,
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
              categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'Jun', 'Aug', 'Sep', 'Oct'],
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
                  offsetX:-10,
                  formatter: function (value) {
                    return value + "k";
                  },
                  style: {
                      fontSize: '14px',
                  }
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
                      height:250,
                  },
              },
          }]
          };
  
          var chartBar1 = new ApexCharts(document.querySelector("#salesChart1"), options);
          chartBar1.render();
       
      }
      var salesChart2 = function(){
          var options = {
            series: [
              {
                  name: 'Net Profit',
                  data: [100,200, 100, 300, 200, 400, 200, 300,100],
                  //radius: 12,	
              }, 				
          ],
              chart: {
              type: 'line',
              height: 380,
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
              categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'Jun', 'Aug', 'Sep', 'Oct'],
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
                  offsetX:-10,
                  formatter: function (value) {
                    return value + "k";
                  },
                  style: {
                      fontSize: '14px',
                  }
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
                      height:250,
                  },
              },
          }]
          };
  
          var chartBar1 = new ApexCharts(document.querySelector("#salesChart2"), options);
          chartBar1.render();
       
      }
      
   
      /* Function ============ */
          return {
              init:function(){
              },
              
              
              load:function(){
                  widgetChart1();
                  widgetChart2();
                  chartBar();
                  donutChart1();
                  donutChart2();
                  donutChart3();
                  donutChart4();
                  salesChart();
                  salesChart1();
                  salesChart2();
                      
              },
              
              resize:function(){
              }
          }
      
      }();
  
      
          
      jQuery(window).on('load',function(){
          setTimeout(function(){
              dzChartlist.load();
          }, 1000); 
          
      });
  
       
  
  })(jQuery);