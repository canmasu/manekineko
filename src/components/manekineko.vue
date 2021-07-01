<template>
    <div>
        <h1> Maneki $Neko </h1>
        <h2> Invited Lucky Token </h2>
        <div> TOTAL Invited token : $NEKO {{totalLuckyCoinsByWallet}} </div>

        <el-table  :data="LuckyCoinsByWallet" stripe style="width: 100%">
            <el-table-column prop="nekoId" label="招き猫 #" width="80"> </el-table-column>
            <el-table-column prop="amount" label="$NEKO" width="200"> </el-table-column>
            <el-table-column prop="timestamp" label="Time" width="80"> </el-table-column>
            <el-table-column prop="walletAddress" label="walletAddress"> </el-table-column>
        </el-table>


        <h2> Gamma Bonus </h2>
        <h2> Referral Incentive </h2>
    </div>
</template>
<script>



import getWeb3 from '../web3/web3';
import abi_collectible from '../web3/abi_collectible';
const contract_collectible = '0x8Ae1a085AA58bB96D1395e2c64C89483F6ac1F45';


export default ({
    data () {
        return {
            account:null,
            LuckyCoinsByWallet :[],
            totalLuckyCoinsByWallet:0,
            contract :{
                collectibles:null
            }
        }
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
        getLucyCoinsByWallet() {
            // event LUCKY_COINS (address indexed luckyWallet, uint256 indexed luckyNeko, uint256 amount, uint256 timestamp);
            this.contract.collectibles.getPastEvents('LUCKY_COINS', {
                filter: { luckyWallet: this.account },
                fromBlock: 0,
                toBlock: 'latest'
            }).then((events) => {

            
            for( let i =0 ; i < events.length ; i++){

                var current = Math.round((new Date()).getTime() / 1000);
                var msPerMinute = 60;
                var msPerHour = msPerMinute * 60;
                var msPerDay = msPerHour * 24;
                var msPerMonth = msPerDay * 30;
                var msPerYear = msPerDay * 365;
                var elapsed = current - events[i].returnValues.timestamp;
                var engDate ='';

                if (elapsed < msPerMinute) { engDate = Math.round(elapsed/1000) + ' s'; }
                else if (elapsed < msPerHour) { engDate = Math.round(elapsed/msPerMinute) + ' s'; }
                else if (elapsed < msPerDay) { engDate = Math.round(elapsed/msPerHour) + ' h'; }
                else if (elapsed < msPerMonth) { engDate = Math.round(elapsed/msPerDay) + ' d'; }
                else if (elapsed < msPerYear) { engDate = Math.round(elapsed/msPerMonth) + ' M'; }
                else { engDate = '>M';}

                this.LuckyCoinsByWallet.push({
                nekoId    : events[i].returnValues.luckyNeko,
                amount    : events[i].returnValues.amount/1000000000000000000,
                timestamp : engDate,
                walletAddress : events[i].returnValues.luckyWallet,
                });
                //total coins invited
                this.totalLuckyCoinsByWallet += events[i].returnValues.amount/1000000000000000000;

            }
            }).catch((err) => {
                console.log(err, 'err');
            });
            
        },
    } 
})
</script>
