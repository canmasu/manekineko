<template>
    <div>
        <!-- Image -->
        <el-card  style="width: 40%">
            <el-image :src="NFT.image" class="nft-image">
                <div slot="placeholder" class="image-slot">
                    loading<span class="dot">...</span>
                </div>
                <div slot="error" class="image-slot" @click="imageLoadError()">
                    <i class="el-icon-picture-outline"></i>
                </div>
            </el-image>
        </el-card> 


        <!-- valuation -->
        <el-card  style="width: 40%">
            <el-table  :data="valuation" stripe>
                <el-table-column prop="trait_type" label="VAULATION" width="250"> </el-table-column>
                <el-table-column prop="value"  width="250"> </el-table-column>      
            </el-table>

            <el-button size="mini" @click="sendGift(scope.row.id)">Gift</el-button>
            <el-button size="mini" @click="wantToSell(scope.row.id)">Sell</el-button>
            <router-link :to="'/wish/' + $route.params.id">
                <el-button size="mini" type="primary" >Wish</el-button>
            </router-link>
        </el-card>


        <!-- Certificate -->
        <el-card style="width: 82%">
            <el-table  :data="certificate" stripe>
                <el-table-column prop="trait_type" label="Birth Certificaiton" width="200"> </el-table-column>
                <el-table-column prop="value" > </el-table-column>      
            </el-table>
            <el-link :href=NFT.txURL target="_blank" class="linkVerify" type="primary">Varify Certificate</el-link>
        </el-card>

        <!-- Chart -->
        <el-card  style="width: 40%">
            <radar-chart :chart-data="chartData" :options="options"></radar-chart>
        </el-card>

        <!-- Maneki Power -->
        <el-card  style="width: 40%">
            <el-table  :data="NFT.attributes" stripe>
                <el-table-column prop="trait_type" label="MANEKI POWER" width="250"> </el-table-column>
                <el-table-column prop="value" label="value #" width="250"> </el-table-column>      
            </el-table>
        </el-card>

 



       



    </div>
</template>

<script>
import RadarChart from '../charts/RadarChart';


import getWeb3 from '../web3/web3';

import abi_collectible from '../web3/abi_collectible';
const contract_collectible = '0xDA01f83Fc3483Df018034af5fe8aDa75373162aF';


// Contract : Collectibles Paymnet
import abi_payment from '../web3/abi_payment';
const contract_payment = '0xB98ACE202eEf57896263CfC89257A78a9C6B29cF';


export default {
    components: {
        RadarChart
    },
    data(){
        return{
            contractInstance:null,
            account:null,
            contract :{
                collectibles:null,
                payment:null,
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
                transactionHash : null,
                txURL     : null,
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
                    max: 99999,
                    stepSize: 30000
                    }
                }
            },
            valuation : [],
            certificate : [],
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
                this.contract.payment = new this.web3.eth.Contract(abi_payment, contract_payment);
   
                //get current signed wallet address
                this.web3.eth.getAccounts().then((accounts) => {
                    [this.account] = accounts;
                    this.getBNBPrice('BNBUSDT');
                    this.getBNBPrice('CAKEUSDT');
                    this.getBNBPrice('BAKEUSDT');

                    // get transaction hash
                    //this.gettransactionHash();


                }).catch((err) => {
                    console.log(err, 'err!!');
                });
            });
        } else {
            //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');

        }

    },
    methods :{
        popWish(name, msg) {
            this.$notify.success({
                title: msg,
                message: name,
                showClose: false,
                duration: 50000,
                position: 'bottom-right'
            });
        },
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
        getCertificate (){

            this.certificate.push ({
                trait_type: 'Token ID',
                value: this.$route.params.id,
            });

            this.certificate.push ({
                trait_type: 'Name',
                value: this.NFT.name,
            });

            this.certificate.push ({
                trait_type: 'Guardian',
                value: this.NFT.guardian,
            });
            this.certificate.push ({
                trait_type: 'Gamma',
                value: this.NFT.gamma,
            });
            this.certificate.push ({
                trait_type: 'DNA',
                value: this.NFT.art_dna,
            });
            this.certificate.push ({
                trait_type: 'Blockchain Certificate',
                value: this.NFT.transactionHash,
            });


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
            if (this.NFT.valuation * 100 < 18.8 ){
                this.NFT.scarcity = 18.8 + this.NFT.valuation * 10;
            } else {
                this.NFT.scarcity = this.NFT.valuation * 100;
            }
            this.NFT.valuation = parseFloat(BasePrice + this.NFT.valuation * 500).toFixed(4);

            // store data to valuation

            this.valuation.push ({
                trait_type: 'Valuation',
                value: this.NFT.valuation,
            })

            this.valuation.push ({
                trait_type: 'Luckiness',
                value: this.NFT.scarcity,
            })

            this.valuation.push ({
                trait_type: 'Limited',
                value: 'Yes',
            })

            this.valuation.push ({
                trait_type: 'Transcacted',
                value: 5,
            })

            this.valuation.push ({
                trait_type: 'Coins invited',
                value: 123132132,
            })

            this.valuation.push ({
                trait_type: 'Gamma',
                value: this.NFT.gamma,
            })

            this.valuation.push ({
                trait_type: 'Guardian',
                value: this.NFT.guardian,
            })

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
                this.NFT.transactionHash = res.data.transactionHash;
                this.NFT.txURL  = 'https://testnet.bscscan.com/tx/'+ res.data.transactionHash;
                this.NFT.name = res.data.name;
                this.NFT.id = res.data.id;
                this.NFT.guardian = res.data.guardian;
                this.NFT.gamma = res.data.gamma;
                this.NFT.description = res.data.description;
                this.NFT.external_url = res.data.external_url;
                this.NFT.art_dna = res.data.art_dna;
                this.NFT.image = res.data.image;
                this.NFT.attributes = res.data.attributes;

                //fill in data for charting
                this.fillData(
                    parseInt(res.data.attributes[5].value.toString().replace(',', '')),
                    parseInt(res.data.attributes[6].value.toString().replace(',', '')),
                    parseInt(res.data.attributes[7].value.toString().replace(',', '')),
                    parseInt(res.data.attributes[8].value.toString().replace(',', '')),
                    parseInt(res.data.attributes[9].value.toString().replace(',', ''))
                    );

                //make valuation
                this.getValuation(res.data.attributes[3].value.toString().replace(',', ''));

                //pop out wish message
                this.popWish(this.NFT.name +' bring this wish come true!', this.NFT.description);

                //birth certification 
                this.getCertificate();

                console.log('chartData wealth: ', res.data.attributes[6].value);

            }).catch((err) => {
                console.log(err, 'err');
            });    
        },
        imageLoadError () {
            console.log('Image failed to load');
            this.generateNewNFT(this.$route.params.id,);
        },
        async generateNewNFT(_id){

            // get transactionHash
            await this.contract.payment.getPastEvents('PAYMENT', {
                filter: { payee: this.account },
                fromBlock: 0,
                toBlock: 'latest'
            }).then((events) => {  
            
            //received transactionHash
            for( let i =0 ; i < events.length ; i++){
                    if(events[i].returnValues._nftID==this.$route.params.id){
                        this.NFT.transactionHash = events[i].transactionHash;
                        console.log('transactionHash : ',events[i].transactionHash);
                    }
            }

            //start generate Image and Metadata


            this.contract.collectibles.methods.Nekos(_id).call({
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


            }).catch((err) => {
                console.log(err, 'err');
            });


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
    width:400px;
    height: 400px;
}
.el-card {
    float:left;
}
.linkVerify {
    padding: 10px;
    float:right;
}
</style>
