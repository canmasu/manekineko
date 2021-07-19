<template>
    <div>

        <router-link :to="'/token/' + $route.params.id"> 
        <div>Neko collectibles Token : {{ $route.params.id }}</div>
        </router-link>

        <div> Neko's name :{{NFT.name}}</div>
        <div> Wish :{{NFT.description}}</div>
        <div> URL : {{NFT.external_url}} </div>
        <div> DNA : {{NFT.art_dna}} </div>
        <div> Guardian : {{NFT.guardian}} </div>
        <div> Gamma : {{NFT.gamma}} </div>
        <div> Scarcity : {{NFT.scarcity}} % </div>
        <div> Valuation : USD {{NFT.valuation}} </div>

        <el-image :src="NFT.image" class="nft-image">
            <div slot="placeholder" class="image-slot">
                loading<span class="dot">...</span>
            </div>
            <div slot="error" class="image-slot" @click="imageLoadError()">
                <i class="el-icon-picture-outline"></i>
            </div>
        </el-image>

        <radar-chart :chart-data="chartData" :options="options"></radar-chart>

        <el-table  :data="NFT.attributes" stripe style="width: 50%">
            <el-table-column prop="trait_type" label="trait_type" width="250"> </el-table-column>
            <el-table-column prop="value" label="value #" width="250"> </el-table-column>      
        </el-table>



    </div>
</template>

<script>
import RadarChart from '../charts/RadarChart';


import getWeb3 from '../web3/web3';

import abi_collectible from '../web3/abi_collectible';
const contract_collectible = '0xDA01f83Fc3483Df018034af5fe8aDa75373162aF';


export default {
    components: {
        RadarChart
    },
    data(){
        return{
            contractInstance:null,
            account:null,
            contract :{
                collectibles:null
            },
            newNFT : {
                id      : '',
                DNA     : '',
                gammaID : '',
                power   : '',
                refCount: '',
                wish    : ''
            },
            NFT: {
                valuation : null,
                scarcity  : null,
                name:null,
                description:null,
                external_url:null,
                art_dna:null,
                image:null,
                wish:null,
                attributes: [{
                    trait_type:null,
                    value:null,
                    display_type:null
                }]
            },
            energies : {
                wealth      : 0,
                opportunity : 0,
                relationship: 0,
                wisdom      : 0,
                health      : 0
            },
            // Radarchart Setting and data
            chartData : null,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scale: {
                    ticks: {
                    min: 0,
                    max: 100,
                    stepSize: 25
                    }
                }
            },
        }
    },
    mounted() {
        this.getMetadata(this.$route.params.id);

        if (typeof web3 !== 'undefined') {
    
            console.log('Metamask is installed!');

            getWeb3().then((res) => {
                this.web3 = res;

                //connect Contracts Collectibles
                this.contract.collectibles = new this.web3.eth.Contract(abi_collectible, contract_collectible);
   
                //get current signed wallet address
                this.web3.eth.getAccounts().then((accounts) => {
                    [this.account] = accounts;
                    this.getBNBPrice('BNBUSDT');
                    this.getBNBPrice('CAKEUSDT');
                    this.getBNBPrice('BAKEUSDT');

                }).catch((err) => {
                    console.log(err, 'err!!');
                });
            });
        } else {
            //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');

        }

    },
    methods :{
        fillData (a,b,c,d,e) {

            console.log('pre-filling :');

            this.chartData = {
                labels: [ "Wealth",	"Opportunity","Relationship", "Wisdom",	"Health"],
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
                    data: [a, b, c, d, e]
                },
                ]
            }
            console.log('filling oppotunity:',b);
        },
        getValuation (pow){
            //valuation return the decimal within 0 - 1
            //scarcity 0 - 100%
            var Power = pow/2;
            var Mean  = 250000;
            var Range = 250000;
            var BasePrice = 10.00;

            if (Power>250000){
                // power above 250k
                this.NFT.valuation = (((Power-Mean)/100000)**5)/97.65625;
            } else {
                // power below 250k
                this.NFT.valuation = (((Range-Power)/100000)**5)/97.65625;
            }
            
            // x10
            this.NFT.scarcity = parseFloat(this.NFT.valuation * 100).toFixed(4);
            this.NFT.valuation = parseFloat(BasePrice + this.NFT.valuation * 500).toFixed(4);


            console.log ('valuation :', this.NFT.valuation);
            console.log ('Power :', pow);


        },
        async getBNBPrice(symbol){
            const axios = require('axios');
            await axios.get('https://api.binance.com/api/v3/ticker/price?symbol='+symbol)
            .then((res) => {
                console.log ('BNB  :',res.data);
                //import data form metadata
            }).catch((err) => {
                console.log(err, 'err');
            });    

        },
        async getMetadata(id){
            const axios = require('axios');
            await axios.get('https://metadata.neko.exchange/token/'+id)
            .then((res) => {
                console.log ('metadata :',res.data);
                //import data form metadata
                this.NFT.name = res.data.name;
                this.NFT.id = res.data.id;
                this.NFT.guardian = res.data.guardian;
                this.NFT.gamma = res.data.gamma;
                this.NFT.description = res.data.description;
                this.NFT.external_url = res.data.external_url;
                this.NFT.art_dna = res.data.art_dna;
                this.NFT.image = res.data.image;
                this.NFT.wish = res.data.wish;
                this.NFT.attributes = res.data.attributes;

                //fill in data for charting
                this.fillData(
                    parseInt(res.data.attributes[5].value),
                    parseInt(res.data.attributes[6].value),
                    parseInt(res.data.attributes[7].value),
                    parseInt(res.data.attributes[8].value),
                    parseInt(res.data.attributes[9].value)
                    );

                //make valuation
                this.getValuation(res.data.attributes[3].value.toString().replace(',', ''));

                console.log('chartData wealth: ', res.data.attributes[6].value);

            }).catch((err) => {
                console.log(err, 'err');
            });    
        },
        imageLoadError () {
            console.log('Image failed to load');
            this.generateNewNFT(this.$route.params.id);
        },
        async generateNewNFT(_id){
            await this.contract.collectibles.methods.Nekos(_id).call({
            from: this.account
            }).then((res) => {
                this.newNFT.id      = _id;
                this.newNFT.DNA     = res.DNA;
                this.newNFT.gamma   = res.gammaNekoID;
                this.newNFT.power   = res.power; 
                this.newNFT.refCount= res.refCount;
                this.newNFT.wish    = '';

                console.log('gen new NFT',this.newNFT);
                this.generateFile ();
                
            })
        },
        async generateFile (){
                // generate Metadata and GIF
                const {data:res2} = await this.$http.post('/painter/', this.newNFT);
                console.log('api data 2 : ', res2);
                //this.$router.go();
        },

    }
}

</script>

<style scoped>
.nft-image {
    width:500px;
    height: 500px;
}
</style>
