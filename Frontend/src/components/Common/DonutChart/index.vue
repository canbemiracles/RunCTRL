<template>
  <div class="donut_chart">
    <canvas ref="chart" width="130px" height="130px"></canvas>
    <slot></slot>
  </div>
</template>

<script>
import Chart from 'chart.js'
import palette from '../../../stylesheets/index'

export default {
  props: {
    values: { // Не более 3 значений
      type: Array,
      required: true
    }
  },

  data() {
    return {
      chart: null
    }
  },
  watch: {
    values(val){
      this.updateChart();
    }
  },
  mounted() {
    this.createChart();
  },
  methods: {
    createChart() {  
    let dataChart = this.setChartData();
    this.chart = new Chart(this.$refs.chart.getContext('2d'), {
      type: 'doughnut',
      data: {
        datasets: [dataChart]
      },
      options: {
        cutoutPercentage: 85,
        legend: { display: false },
        tooltips: { enabled: false }
      }
    })
    },
    setChartData(){
      let bgArray = [palette.green, palette.yellow, palette.red];
      let values;
      if(this.values){
        values = this.values;
        if(values[0]==values[1] && values[1]==values[2] && values[2]==0){
          values=[100,];
          bgArray= '#e6ebed';
        }
      }
      return {
          data: values,
          borderWidth: 0,
          backgroundColor: bgArray
      }
    },
    updateChart() {
      let dataChart = this.setChartData();
      if(this.chart){
        this.chart.data.datasets = [dataChart];
        this.chart.options.animation.duration = 0;
        this.chart.update();
      }
    },
  }
}
</script>

<style lang="scss" scoped>

.donut_chart {
  position: relative;

  canvas + * {
    padding: 30px;
    text-align: center;
    position: absolute;
    top: -5px;
    left: 0;
    right: 0;
    bottom: 0;
  }
}

</style>
