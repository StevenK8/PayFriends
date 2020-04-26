(function ($) {
  'use strict';
  $('#invitation').modal('show');


  if ($('[name^="notification"]').length > 0) {
    $('#notificationDropdown').append('<span class="count-symbol bg-danger"></span>');
  }

  $(function () {

    Chart.defaults.global.legend.labels.usePointStyle = true;
    if ($("#serviceSaleProgress").length) {
      var bar = new ProgressBar.Circle(serviceSaleProgress, {
        color: 'url(#gradient)',
        // This has to be the same size as the maximum width to
        // prevent clipping
        strokeWidth: 8,
        trailWidth: 8,
        easing: 'easeInOut',
        duration: 1400,
        text: {
          autoStyleContainer: false
        },
        from: {
          color: '#aaa',
          width: 6
        },
        to: {
          color: '#57c7d4',
          width: 6
        }
      });

      bar.animate(.65); // Number from 0.0 to 1.0
      bar.path.style.strokeLinecap = 'round';
      let linearGradient = '<defs><linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%" gradientUnits="userSpaceOnUse"><stop offset="20%" stop-color="#da8cff"/><stop offset="50%" stop-color="#9a55ff"/></linearGradient></defs>';
      bar.svg.insertAdjacentHTML('afterBegin', linearGradient);
    }
    if ($("#productSaleProgress").length) {
      var bar = new ProgressBar.Circle(productSaleProgress, {
        color: 'url(#productGradient)',
        // This has to be the same size as the maximum width to
        // prevent clipping
        strokeWidth: 8,
        trailWidth: 8,
        easing: 'easeInOut',
        duration: 1400,
        text: {
          autoStyleContainer: false
        },
        from: {
          color: '#aaa',
          width: 6
        },
        to: {
          color: '#57c7d4',
          width: 6
        }
      });

      bar.animate(.6); // Number from 0.0 to 1.0
      bar.path.style.strokeLinecap = 'round';
      let linearGradient = '<defs><linearGradient id="productGradient" x1="0%" y1="0%" x2="100%" y2="0%" gradientUnits="userSpaceOnUse"><stop offset="40%" stop-color="#36d7e8"/><stop offset="70%" stop-color="#b194fa"/></linearGradient></defs>';
      bar.svg.insertAdjacentHTML('afterBegin', linearGradient);
    }
    if ($("#points-chart").length) {
      var ctx = document.getElementById('points-chart').getContext("2d");

      var gradientStrokeViolet = ctx.createLinearGradient(0, 0, 0, 181);
      gradientStrokeViolet.addColorStop(0, 'rgba(218, 140, 255, 1)');
      gradientStrokeViolet.addColorStop(1, 'rgba(154, 85, 255, 1)');

      var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: [1, 2, 3, 4, 5, 6, 7, 8],
          datasets: [{
              label: "North Zone",
              borderColor: gradientStrokeViolet,
              backgroundColor: gradientStrokeViolet,
              hoverBackgroundColor: gradientStrokeViolet,
              pointRadius: 0,
              fill: false,
              borderWidth: 1,
              fill: 'origin',
              data: [20, 40, 15, 35, 25, 50, 30, 20]
            },
            {
              label: "South Zone",
              borderColor: '#e9eaee',
              backgroundColor: '#e9eaee',
              hoverBackgroundColor: '#e9eaee',
              pointRadius: 0,
              fill: false,
              borderWidth: 1,
              fill: 'origin',
              data: [40, 30, 20, 10, 50, 15, 35, 20]
            }
          ]
        },
        options: {
          legend: {
            display: false
          },
          scales: {
            yAxes: [{
              ticks: {
                display: false,
                min: 0,
                stepSize: 10
              },
              gridLines: {
                drawBorder: false,
                display: false
              }
            }],
            xAxes: [{
              gridLines: {
                display: false,
                drawBorder: false,
                color: 'rgba(0,0,0,1)',
                zeroLineColor: '#eeeeee'
              },
              ticks: {
                padding: 20,
                fontColor: "#9c9fa6",
                autoSkip: true,
              },
              barPercentage: 0.7
            }]
          }
        },
        elements: {
          point: {
            radius: 0
          }
        }
      })
    }
    if ($("#events-chart").length) {
      var ctx = document.getElementById('events-chart').getContext("2d");

      var gradientStrokeBlue = ctx.createLinearGradient(0, 0, 0, 181);
      gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
      gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');

      var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: [1, 2, 3, 4, 5, 6, 7, 8],
          datasets: [{
              label: "Domestic",
              borderColor: gradientStrokeBlue,
              backgroundColor: gradientStrokeBlue,
              hoverBackgroundColor: gradientStrokeBlue,
              pointRadius: 0,
              fill: false,
              borderWidth: 1,
              fill: 'origin',
              data: [20, 40, 15, 35, 25, 50, 30, 20]
            },
            {
              label: "International",
              borderColor: '#e9eaee',
              backgroundColor: '#e9eaee',
              hoverBackgroundColor: '#e9eaee',
              pointRadius: 0,
              fill: false,
              borderWidth: 1,
              fill: 'origin',
              data: [40, 30, 20, 10, 50, 15, 35, 20]
            }
          ]
        },
        options: {
          legend: {
            display: false
          },
          scales: {
            yAxes: [{
              ticks: {
                display: false,
                min: 0,
                stepSize: 10
              },
              gridLines: {
                drawBorder: false,
                display: false
              }
            }],
            xAxes: [{
              gridLines: {
                display: false,
                drawBorder: false,
                color: 'rgba(0,0,0,1)',
                zeroLineColor: '#eeeeee'
              },
              ticks: {
                padding: 20,
                fontColor: "#9c9fa6",
                autoSkip: true,
              },
              barPercentage: 0.7
            }]
          }
        },
        elements: {
          point: {
            radius: 0
          }
        }
      })
    }
    if (window.location.search.substr(1) != "") {
      $(document).ready(function () {
        $.ajax({
          url: window.location.protocol + "//" + window.location.hostname + "/" + window.location.pathname.split("/")[1] + "/data.php?" + window.location.search.substr(1),
          method: "GET",
          success: function (data) {
            if ($("#visit-sale-chart").length) {
              Chart.defaults.global.legend.labels.usePointStyle = true;
              var ctx = document.getElementById('visit-sale-chart').getContext("2d");

              var gradientStrokeViolet = ctx.createLinearGradient(0, 0, 0, 181);
              gradientStrokeViolet.addColorStop(0, 'rgba(218, 140, 255, 1)');
              gradientStrokeViolet.addColorStop(1, 'rgba(154, 85, 255, 1)');
              var gradientLegendViolet = 'linear-gradient(to right, rgba(218, 140, 255, 1), rgba(154, 85, 255, 1))';

              var gradientStrokeBlue = ctx.createLinearGradient(0, 0, 0, 360);
              gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
              gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');
              var gradientLegendBlue = 'linear-gradient(to right, rgba(54, 215, 232, 1), rgba(177, 148, 250, 1))';

              var gradientStrokeRed = ctx.createLinearGradient(0, 0, 0, 300);
              gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
              gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');
              var gradientLegendRed = 'linear-gradient(to right, rgba(255, 191, 150, 1), rgba(254, 112, 150, 1))';

              var datasets = [{}];
              var usernames = [];
              var prix = [];
              var mois = [];
              var gradientStroke = [gradientStrokeViolet, gradientStrokeBlue, gradientStrokeRed];
              var gradientLegend = [gradientLegendViolet, gradientLegendBlue, gradientLegendRed];
              var j = 0;
              var randStroke;
              var color1, color2;
              var colors = ['rgba(54, 215, 232, 1)', 'rgba(177, 148, 250, 1)', 'rgba(218, 140, 255, 1)', 'rgba(154, 85, 255, 1)', 'rgba(255, 191, 150, 1)', 'rgba(254, 112, 150, 1)', 'rgba(6, 185, 157, 1)', 'rgba(132, 217, 210, 1)', 'rgba(218, 140, 255, 1)', 'rgba(154, 85, 255, 1)', '#86A8E7', '#eaafc8', '#5E50F9', '#6610f2', '#6a008a', '#E91E63', '#f96868', '#f2a654', '#f6e84e', '#46c35f', '#58d8a3', '#57c7d4', '#6c757d', '#0f1531', '#6a008a', '#E91E63', '#f96868', '#f2a654', '#f6e84e', '#46c35f', '#58d8a3', '#57c7d4', '#434a54', '#aab2bd', '#e6e9ed', '#b66dff', '#c3bdbd', '#1bcfb4', '#198ae3', '#fed713', '#fe7c96', '#f8f9fa'];

              function randomNumber(min, max) {
                return Math.floor((Math.random() * max) + min);
              }

              function onlyUnique(value, index, self) {
                return self.indexOf(value) === index;
              }

              for (var i in data) {
                usernames.push(data[i].username);
                mois.push(data[i].date);
                prix[i] = [];
              }
              usernames = usernames.filter(onlyUnique);
              mois = mois.filter(onlyUnique);

              for (var i in data) { // Pour chaque donnée (mois - username - prix)
                for (var k in usernames) { // Pour chaque username distinct
                  if (usernames[k] == data[i].username) {
                    prix[k].push(data[i].prix); // On ajoute sa donnée
                  } else {
                    prix[k].push("");
                  }
                }
                j++;
                if (j > gradientStroke.length) {
                  randStroke = ctx.createLinearGradient(0, 0, 0, randomNumber(50, 300));
                  color1 = colors[randomNumber(0, colors.length - 1)];
                  color2 = colors[randomNumber(0, colors.length - 1)];
                  randStroke.addColorStop(0, color1);
                  randStroke.addColorStop(1, color2);

                  gradientLegend.push("linear-gradient(to right, " + color1 + ", " + color2);
                  gradientStroke.push(randStroke);
                }
              }
              // console.log(prix);

              for (k in usernames) {
                datasets[k] = {
                  label: usernames[k],
                  data: prix[k],
                  backgroundColor: gradientStroke[k],
                  hoverBackgroundColor: gradientStroke[k],
                  borderColor: gradientStroke[k],
                  legendColor: gradientLegend[k],
                  pointRadius: 0,
                  fill: false,
                  borderWidth: 1,
                  fill: 'origin',
                };
              }

              var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                  labels: mois,
                  datasets: datasets
                },
                options: {
                  responsive: true,
                  legend: false,
                  legendCallback: function (chart) {
                    var text = [];
                    text.push('<ul>');
                    for (var i = 0; i < chart.data.datasets.length; i++) {
                      text.push('<li><span class="legend-dots" style="background:' +
                        chart.data.datasets[i].legendColor +
                        '"></span>');
                      if (chart.data.datasets[i].label) {
                        text.push(chart.data.datasets[i].label);
                      }
                      text.push('</li>');
                    }
                    text.push('</ul>');
                    return text.join('');
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        display: false,
                        min: 0,
                        stepSize: 20
                      },
                      gridLines: {
                        drawBorder: false,
                        color: 'rgba(235,237,242,1)',
                        zeroLineColor: 'rgba(235,237,242,1)'
                      }
                    }],
                    xAxes: [{
                      gridLines: {
                        display: false,
                        drawBorder: false,
                        color: 'rgba(0,0,0,1)',
                        zeroLineColor: 'rgba(235,237,242,1)'
                      },
                      ticks: {
                        padding: 20,
                        fontColor: "#9c9fa6",
                        autoSkip: true,
                      },
                      categoryPercentage: 0.5,
                      barPercentage: 0.5
                    }]
                  }
                },
                elements: {
                  point: {
                    radius: 0
                  }
                }
              })
              $("#visit-sale-chart-legend").html(myChart.generateLegend());
            }

          },
          error: function (data) {
            console.log(data);
          }
        });
      });

      $(document).ready(function () {
        $.ajax({
          url: window.location.protocol + "//" + window.location.hostname + "/" + window.location.pathname.split("/")[1] + "/dataDonut.php?" + window.location.search.substr(1),
          method: "GET",
          success: function (data) {
            if ($("#traffic-chart").length) {
              var ctx = document.getElementById('traffic-chart').getContext("2d");

              var gradientStrokeBlue = ctx.createLinearGradient(0, 0, 0, 181);
              gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
              gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');
              var gradientLegendBlue = 'linear-gradient(to right, rgba(54, 215, 232, 1), rgba(177, 148, 250, 1))';

              var gradientStrokeRed = ctx.createLinearGradient(0, 0, 0, 50);
              gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
              gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');
              var gradientLegendRed = 'linear-gradient(to right, rgba(255, 191, 150, 1), rgba(254, 112, 150, 1))';

              var gradientStrokeGreen = ctx.createLinearGradient(0, 0, 0, 300);
              gradientStrokeGreen.addColorStop(0, 'rgba(6, 185, 157, 1)');
              gradientStrokeGreen.addColorStop(1, 'rgba(132, 217, 210, 1)');
              var gradientLegendGreen = 'linear-gradient(to right, rgba(6, 185, 157, 1), rgba(132, 217, 210, 1))';

              var usernames = [];
              var prix = [];
              var gradientStroke = [gradientStrokeBlue, gradientStrokeGreen, gradientStrokeRed];
              var gradientLegend = [gradientLegendBlue, gradientLegendGreen, gradientLegendRed];
              var j = 0;
              var randStroke;
              var color1, color2;
              var colors = ['rgba(54, 215, 232, 1)', 'rgba(177, 148, 250, 1)', 'rgba(255, 191, 150, 1)', 'rgba(254, 112, 150, 1)', 'rgba(6, 185, 157, 1)', 'rgba(132, 217, 210, 1)', 'rgba(218, 140, 255, 1)', 'rgba(154, 85, 255, 1)', '#86A8E7', '#eaafc8', '#5E50F9', '#6610f2', '#6a008a', '#E91E63', '#f96868', '#f2a654', '#f6e84e', '#46c35f', '#58d8a3', '#57c7d4', '#6c757d', '#0f1531', '#6a008a', '#E91E63', '#f96868', '#f2a654', '#f6e84e', '#46c35f', '#58d8a3', '#57c7d4', '#434a54', '#aab2bd', '#e6e9ed', '#b66dff', '#c3bdbd', '#1bcfb4', '#198ae3', '#fed713', '#fe7c96', '#f8f9fa'];

              function randomNumber(min, max) {
                return Math.floor((Math.random() * max) + min);
              }

              for (var i in data) {
                usernames.push(data[i].username);
                prix.push(data[i].prix);
                j++;
                if (j > gradientStroke.length) {
                  randStroke = ctx.createLinearGradient(0, 0, 0, randomNumber(50, 300));
                  color1 = colors[randomNumber(0, colors.length - 1)];
                  color2 = colors[randomNumber(0, colors.length - 1)];
                  randStroke.addColorStop(0, color1);
                  randStroke.addColorStop(1, color2);

                  gradientLegend.push("linear-gradient(to right, " + color1 + ", " + color2);
                  gradientStroke.push(randStroke);
                }
              }

              var trafficChartData = {
                datasets: [{
                  data: prix,
                  backgroundColor: gradientStroke,
                  hoverBackgroundColor: gradientStroke,
                  borderColor: gradientStroke,
                  legendColor: gradientLegend
                }],

                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: usernames,
              };
              var trafficChartOptions = {
                responsive: true,
                animation: {
                  animateScale: true,
                  animateRotate: true
                },
                legend: false,
                legendCallback: function (chart) {
                  var text = [];
                  text.push('<ul>');
                  for (var i = 0; i < trafficChartData.datasets[0].data.length; i++) {
                    text.push('<li><span class="legend-dots" style="background:' +
                      trafficChartData.datasets[0].legendColor[i] +
                      '"></span>');
                    if (trafficChartData.labels[i]) {
                      text.push(trafficChartData.labels[i]);
                    }
                    text.push('<span class="float-right">' + trafficChartData.datasets[0].data[i] + "€" + '</span>')
                    text.push('</li>');
                  }
                  text.push('</ul>');
                  return text.join('');
                }
              };
              var trafficChartCanvas = $("#traffic-chart").get(0).getContext("2d");
              var trafficChart = new Chart(trafficChartCanvas, {
                type: 'doughnut',
                data: trafficChartData,
                options: trafficChartOptions
              });
              $("#traffic-chart-legend").html(trafficChart.generateLegend());
            }
            if ($("#inline-datepicker").length) {
              $('#inline-datepicker').datepicker({
                enableOnReadonly: true,
                todayHighlight: true,
              });
            }
          },
          error: function (data) {
            console.log(data);
          }
        });
      });
    } else { // Si la donnée renvoyée est trop grande (page login au lieu d'un payload json)
      $("#visit-sale-chart-legend").html("<center><h5>Pas de données.</h5></center>");
      
      $(document).ready(function () {
        $.ajax({
          url: window.location.protocol + "//" + window.location.hostname + "/" + window.location.pathname.split("/")[1] + "/dataDonutMainPage.php?" + window.location.search.substr(1),
          method: "GET",
          success: function (data) {
            if ($("#traffic-chart").length && data.length!=0) {
              var ctx = document.getElementById('traffic-chart').getContext("2d");

              var gradientStrokeBlue = ctx.createLinearGradient(0, 0, 0, 181);
              gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
              gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');
              var gradientLegendBlue = 'linear-gradient(to right, rgba(54, 215, 232, 1), rgba(177, 148, 250, 1))';

              var gradientStrokeRed = ctx.createLinearGradient(0, 0, 0, 50);
              gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
              gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');
              var gradientLegendRed = 'linear-gradient(to right, rgba(255, 191, 150, 1), rgba(254, 112, 150, 1))';

              var gradientStrokeGreen = ctx.createLinearGradient(0, 0, 0, 300);
              gradientStrokeGreen.addColorStop(0, 'rgba(6, 185, 157, 1)');
              gradientStrokeGreen.addColorStop(1, 'rgba(132, 217, 210, 1)');
              var gradientLegendGreen = 'linear-gradient(to right, rgba(6, 185, 157, 1), rgba(132, 217, 210, 1))';

              var titles = [];
              var prix = [];
              var gradientStroke = [gradientStrokeBlue, gradientStrokeGreen, gradientStrokeRed];
              var gradientLegend = [gradientLegendBlue, gradientLegendGreen, gradientLegendRed];
              var j = 0;
              var randStroke;
              var color1, color2;
              var colors = ['rgba(54, 215, 232, 1)', 'rgba(177, 148, 250, 1)', 'rgba(255, 191, 150, 1)', 'rgba(254, 112, 150, 1)', 'rgba(6, 185, 157, 1)', 'rgba(132, 217, 210, 1)', 'rgba(218, 140, 255, 1)', 'rgba(154, 85, 255, 1)', '#86A8E7', '#eaafc8', '#5E50F9', '#6610f2', '#6a008a', '#E91E63', '#f96868', '#f2a654', '#f6e84e', '#46c35f', '#58d8a3', '#57c7d4', '#6c757d', '#0f1531', '#6a008a', '#E91E63', '#f96868', '#f2a654', '#f6e84e', '#46c35f', '#58d8a3', '#57c7d4', '#434a54', '#aab2bd', '#e6e9ed', '#b66dff', '#c3bdbd', '#1bcfb4', '#198ae3', '#fed713', '#fe7c96', '#f8f9fa'];

              function randomNumber(min, max) {
                return Math.floor((Math.random() * max) + min);
              }

              for (var i in data) {
                titles.push(data[i].title);
                prix.push(data[i].prix);
                j++;
                if (j > gradientStroke.length) {
                  randStroke = ctx.createLinearGradient(0, 0, 0, randomNumber(50, 300));
                  color1 = colors[randomNumber(0, colors.length - 1)];
                  color2 = colors[randomNumber(0, colors.length - 1)];
                  randStroke.addColorStop(0, color1);
                  randStroke.addColorStop(1, color2);

                  gradientLegend.push("linear-gradient(to right, " + color1 + ", " + color2);
                  gradientStroke.push(randStroke);
                }
              }

              var trafficChartData = {
                datasets: [{
                  data: prix,
                  backgroundColor: gradientStroke,
                  hoverBackgroundColor: gradientStroke,
                  borderColor: gradientStroke,
                  legendColor: gradientLegend
                }],

                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: titles,
              };
              var trafficChartOptions = {
                responsive: true,
                animation: {
                  animateScale: true,
                  animateRotate: true
                },
                legend: false,
                legendCallback: function (chart) {
                  var text = [];
                  text.push('<ul>');
                  for (var i = 0; i < trafficChartData.datasets[0].data.length; i++) {
                    text.push('<li><span class="legend-dots" style="background:' +
                      trafficChartData.datasets[0].legendColor[i] +
                      '"></span>');
                    if (trafficChartData.labels[i]) {
                      text.push(trafficChartData.labels[i]);
                    }
                    text.push('<span class="float-right">' + trafficChartData.datasets[0].data[i] + "€" + '</span>')
                    text.push('</li>');
                  }
                  text.push('</ul>');
                  return text.join('');
                }
              };
              var trafficChartCanvas = $("#traffic-chart").get(0).getContext("2d");
              var trafficChart = new Chart(trafficChartCanvas, {
                type: 'doughnut',
                data: trafficChartData,
                options: trafficChartOptions
              });
              $("#traffic-chart-legend").html(trafficChart.generateLegend());
            }else{
              $("#traffic-chart-legend").html("<center><h5>Pas de données.</h5></center>");
            }
            if ($("#inline-datepicker").length) {
              $('#inline-datepicker').datepicker({
                enableOnReadonly: true,
                todayHighlight: true,
              });
            }
          },
          error: function (data) {
            console.log(data);
          }
        });
      });
    }
  });

})(jQuery);