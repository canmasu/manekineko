<template>
  <div class="small">
    <line-chart :chart-data="datacollection"></line-chart>
    <button @click="fillData()">Randomize</button>
  </div>
</template>

<script>
  import LineChart from '../charts/LineChart.js'

  export default {
    components: {
      LineChart
    },
    data () {
      return {
        datacollection: null,
      }
    },
    mounted () {
      this.fillData()
    },
    methods: {
      fillData () {
        this.datacollection = {
          labels: [this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt()],
          datasets: [
            {
              label: 'Data One',
              fill : false,
              backgroundColor: 'rgba(255, 99, 132,0.5)',
              borderColor: 'rgb(54, 162, 235,)',
              pointBackgroundColor: 'rgb(255, 99, 132)',
              pointBorderColor: '#fff',
              pointHoverBackgroundColor: '#fff',
              pointHoverBorderColor: 'rgb(255, 99, 132)',
              data: [this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt()],
              tension : 0
            }
          ],
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                        min: 0,
                        max: 300,
                        stepSize: 10,
                        reverse: false,
                        beginAtZero: false
                        }
                    }]
                }
            }
        }
      },
      getRandomInt () {
        return Math.floor(Math.random() * (123 - 5 + 1)) + 5
      }
    }
  }
</script>

<style>
  .small {
    max-width: 400px;
    margin:  350px auto;
  }
</style>