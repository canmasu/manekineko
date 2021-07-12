<template>
    <div>
        <el-card>
        <h1> Maneki $Neko </h1>
        <h2> Invited Lucky Token </h2>
        <div> Total Maneki $Neko  <br><br><br></div>
        <div> TOTAL Invited token : $NEKO {{totalLuckyCoinsByWallet}} </div>


        <el-alert title="Loading ..." type="warning" show-icon v-if="isAlert"></el-alert>

        <div class="chart">
           <line-chart :chart-data="datacollection" :options="options"></line-chart>
        </div>    
        </el-card>

        <el-table  :data="LuckyCoinsByWallet" stripe  style="width: 100%">
            <el-table-column prop="nekoId" label="招き猫 #" width="80"> </el-table-column>
            <el-table-column prop="amount" label="$NEKO" width="200"> </el-table-column>
            <el-table-column prop="timestamp" label="Time" width="80"> </el-table-column>
            <el-table-column prop="walletAddress" label="walletAddress"> </el-table-column>
            <el-table-column prop="elapsed" label="elapsed"> </el-table-column>
            
        </el-table>


        <h2> Gamma Bonus </h2>
        <h2> Referral Incentive </h2>
    </div>
</template>
<script>
import LineChart from '../charts/LineChart.js'
import getWeb3 from '../web3/web3';
import abi_collectible from '../web3/abi_collectible';
const contract_collectible = '0x8e2F7e97f07bF6454a62FAECb4402A62B7C57e22';


export default ({
    components: {
      LineChart,
    },
    data () {
        return {
            account:null,
            LuckyCoinsByWallet :[],
            amountByDay :[],
            totalLuckyCoinsByWallet:0,
            contract :{
                collectibles:null
            },
            data:[],
            datacollection: null,      
            limit: [],
            options: null,
            isAlert : false,
        }
    },
    created (){
      this.fillData();
    },
    mounted () {
    if (typeof web3 !== 'undefined') {
    
            console.log('Metamask is installed!');

            getWeb3().then((res) => {
                this.web3 = res;

                //connect Contracts Collectibles, 
                this.contract.collectibles = new this.web3.eth.Contract(abi_collectible, contract_collectible);


                //get current signed wallet address
                this.web3.eth.getAccounts().then((accounts) => {
                    [this.account] = accounts;

                //get current signed wallet address
                this.getLucyCoinsByWallet();


                }).catch((err) => {
                    console.log(err, 'err!!');
                });
            });
  
            } else {
                //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');

            }
    },
    methods : {      
        fillData () {
        this.datacollection = {
            labels: [7,6,5,4,3,2,1],
            datasets: [
            {
                borderWidth:1,
                label: 'Maneki $NEKO',
                fill : false,
                backgroundColor: 'rgba(255, 99, 132,0.5)',
                borderColor: 'rgb(0, 150, 255)',
                pointBackgroundColor: 'rgb(255, 99, 132)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(255, 99, 132)',
                data: [this.data[6],this.data[5],this.data[4],this.data[3],this.data[2],this.data[1],this.data[0],],
                tension : 0
            }
            ],
        };

        this.options = {
                scales: {
                    yAxes: [{
                        ticks: {
                        min: this.limit.min,
                        max: this.limit.max,
                        reverse: false,
                        beginAtZero: false,
                        }
                    }],
                    xAxes: [{
                        ticks: {
                        beginAtZero: true,
                        },
                        gridLines: {
                        display: false,
                        },
                    }],
                },
            
            responsive: true,
            maintainAspectRatio: false,
            };

        // data loaded 
        this.isAlert = false;


      },

        getLucyCoinsByWallet() {
            // show data fetching status
            this.isAlert = true;


            // event LUCKY_COINS (address indexed luckyWallet, uint256 indexed luckyNeko, uint256 amount, uint256 timestamp);
            this.contract.collectibles.getPastEvents('LUCKY_COINS', {
                filter: { luckyWallet: this.account },
                fromBlock: 0,
                toBlock: 'latest'
            }).then((events) => {

            
                var day1 = 0; 
                var day2 = 0; 
                var day3 = 0; 
                var day4 = 0; 
                var day5 = 0; 
                var day6 = 0; 
                var day7 = 0;

            for( let i =0 ; i < events.length ; i++){

                var current = Math.round((new Date()).getTime() / 1000);
                var msPerMinute = 60;
                var msPerHour = msPerMinute * 60;
                var msPerDay = msPerHour * 24;
                var msPerMonth = msPerDay * 30;
                var msPerYear = msPerDay * 365;
                var elapsed = current - events[i].returnValues.timestamp;
                var engDate = '';

              

                if (elapsed < msPerMinute) { engDate = Math.round(elapsed/1000) + ' s'; }
                else if (elapsed < msPerHour)  { engDate = Math.round(elapsed/msPerMinute) + ' s'; }
                else if (elapsed < msPerDay)   { engDate = Math.round(elapsed/msPerHour) + ' h'; }
                else if (elapsed < msPerMonth) { engDate = Math.round(elapsed/msPerDay) + ' d'; }
                else if (elapsed < msPerYear)  { engDate = Math.round(elapsed/msPerMonth) + ' M'; }
                else { engDate = '>M';}

                var dayframe = parseInt(elapsed/86499);
                var manekiAmount = events[i].returnValues.amount/10**18;

                if (dayframe==0){
                    day1 += manekiAmount;
                } else if (dayframe==1){
                    day2 += manekiAmount;
                } else if (dayframe==2){
                    day3 += manekiAmount;
                } else if (dayframe==3){
                    day4 += manekiAmount;
                } else if (dayframe==4){
                    day5 += manekiAmount;
                } else if (dayframe==5){
                    day6 += manekiAmount;
                } else if (dayframe==6){
                    day7 += manekiAmount;
                } 


                this.LuckyCoinsByWallet.push({
                nekoId          : events[i].returnValues.luckyNeko,
                amount          : manekiAmount,
                timestamp       : engDate,
                walletAddress   : events[i].returnValues.luckyWallet,
                elapsed         : parseInt(elapsed/86499)
                });

                //total coins invited
                this.totalLuckyCoinsByWallet += manekiAmount;
            }


            this.data[0]=parseInt(day1);
            this.data[1]=parseInt(day2);
            this.data[2]=parseInt(day3);
            this.data[3]=parseInt(day4);
            this.data[4]=parseInt(day5);
            this.data[5]=parseInt(day6);
            this.data[6]=parseInt(day7);    
            
            for(let i=0; i<this.data.length ; i++){
                
                if(i==0){
                    this.limit.min = this.data[i];
                    this.limit.max = this.data[i];
                } 
                if(this.limit.min > this.data[i]){
                    this.limit.min = this.data[i];
                }
                if(this.limit.max < this.data[i]){
                    this.limit.max = this.data[i];
                }
  
            }

            console.log ('amont by day :', this.data);
            console.log ('min :', this.limit.min);
            console.log ('max :', this.limit.max);

            this.fillData();


            }).catch((err) => {
                console.log(err, 'err');
            });
            
        },
    } 
})
</script>

<style scoped>
  .chart {
    padding:10px;
    max-width:800px;
    margin:10px auto;
    border: 1px solid seagreen;
  }
  canvas {
      border: 1px solid seagreen;
      background-color: bisque;
  }
</style>