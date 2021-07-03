import { Radar } from 'vue-chartjs'

export default {
  extends: Radar,
  data () {
    return {
      chartData: {
        labels: [ "Wealth",	"Opportunity","Wisdom",	"Relation",	"Health"],
        datasets: [
          {
            label: 'Maneki Power',
            borderWidth: 0.5,
            fill: true,
            backgroundColor: 'rgba(255, 99, 132,0.5)',
            borderColor: 'rgb(255, 99, 132)',
            pointBackgroundColor: 'rgb(255, 99, 132)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(255, 99, 132)',
           
            data: [73, 98, 32, 51, 45]
          },

          {
            label: 'Today Lucks',
            borderWidth: 0.5,
            fill: true,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgb(54, 162, 235,)',
            pointBackgroundColor: 'rgb(255, 99, 132)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(255, 99, 132)',
           
            data: [72, 90, 61, 61, 32]
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scale: {
            ticks: {
              min: 0,
              max: 100,
              stepSize: 100
            }
          }
      }
    }
  },
  mounted () {
    this.renderChart(this.chartData, this.options)
  }
}

  