<template>
  <div class="bar_chart">
    <h2 v-if="title">{{ title }}</h2>
    <canvas ref="chart" width="1000" height="200"></canvas>

    <!-- Сюда помещаем дополнительные элементы -->
    <slot></slot>
  </div>
</template>

<script>
import Chart from 'chart.js';
import 'chartjs-plugin-annotation';
import Vue from 'vue';
export default {
  props: {

    // Заголовок блока
    title: String,
    typeChart: {
      type: String,
      default: 'bar'
    },  
    // Массив объектов [{ title: 'Title', value: 8 }, ...]
    values: {
      type: Array,
      required: true
    },

    // Горизонтальная линия
    average: {
      type: Number,
      default: null
    },
    // Преобразование текста в тултипе, передаваемый аргумент - модель тултипа
    tooltipTextCb: {
      type: Function,
      default: ({ yLabel }) => yLabel
    }
  },

  data() {
    return {
      chart: null
    }
  },

  watch: {
    values: {
      handler (values) {
        console.log('values updated')
        if (values.length) {
          if (!this.chart) {
            this.createChart();
          } else {
            this.updateChart();
          }
        }
      },
      deep: true,
    },
  },
  methods: {
    createChart () {
      this.chart = new Chart(this.$refs.chart.getContext('2d'), {
        type: 'bar',
        data: {
          labels: [],
          datasets: this.setChartData(), 
        },
        options: {
          scales: {
            yAxes: [{
              position: 'right',
              stacked: true,
              gridLines: {
                color: '#d9e1e3',
                zeroLineColor: '#d9e1e3',
                borderDash: [2],
                drawBorder: false
              },
              ticks: {
                fontColor: '#7e8a90',
                fontSize: 14,
                maxTicksLimit: 6,
                padding: 10,
                callback: value => {
                  return Vue.filter('currency')(value, 0, 0);
                }
              }
            }],
            xAxes: [{
              maxBarThickness: 30,
              gridLines: { display: false },
            }]
          },
          legend: { display: false },
          responsive: true,
          tooltips: {
            titleFontFamily: 'Roboto-Regular',
            bodyFontFamily: 'Roboto-Light',
            displayColors: false,
            titleFontSize: 13,
            bodyFontSize: 13,
            xPadding: 10,
            yPadding: 10,
            cornerRadius: 0,
            callbacks: {
              title: items => this.values[items[0].index].title || '',
              label: this.tooltipTextCb
            }
          },
          annotation: {
            drawTime: 'beforeDatasetsDraw',
            annotations: [
              {
                type: "line",
                mode: "horizontal",
                scaleID: "y-axis-0",
                value: this.average,
                borderColor: "red",
                borderDash: [2, 2],
                borderDashOffset: 5,
                label: {
                  content: `avg. ${this.average}`,
                  enabled: true,
                  backgroundColor: '#0ecdee',
                  fontColor: "#fff",
                  xAdjust: 0,
                  position: "right"
                }
              }
            ]
          }
        }
      })
    },
    setChartData(){
      return [
        {
          type: this.typeChart,
          data: this.values.map(({ value }) => value),
          borderWidth: 0,
          backgroundColor: '#8fb4c7',
          hoverBackgroundColor: '#0ecdee'
        },
      ]
    },
    updateChart() {
      if(this.chart){
        let dataChart = this.setChartData();
        this.chart.data.datasets = dataChart;
        this.chart.options.animation.duration = 0;
        this.chart.options.annotation.annotations[0].value = this.average;
        this.chart.options.annotation.annotations[0].label.content = `avg. ${this.average}`;
        this.chart.update();
      }
    },
  }

}
</script>

<style lang="scss" scoped>

.bar_chart {
  background: #fff;
  position: relative;
  max-width: 1280px;
  margin: auto;
  padding: 30px;
  padding-right: 20px;

  h2 {
    font-size: 22px;
    color: #2e3a40;
    margin-bottom: 30px;
  }
}

</style>
