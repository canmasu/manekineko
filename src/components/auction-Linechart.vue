 <!-- Public Access Page : Guardian ID  -->

<template>
    <div class="main-container">

        <!-- Wallet connection -->
        <div class="wallet-connection" style="text-align:right;" >
            <el-button @click="updateButton()" v-if="!display.isConnect"> connect wallet </el-button>
            <div style="color:white" v-if="display.isConnect"> Conflux Network : {{shortenAccount}} </div>
        </div>


        <img src="../../src/assets/images/logo-maneki_logo.png" />

        <div class="sub-container-gaurdian-details">
        <el-card  style="width: 440px">
            <el-image :src="NFT.image" class="nft-image">
                <div slot="placeholder" class="image-slot">
                    loading<span class="dot">...</span>
                </div>
                <div slot="error" class="image-slot" @click="imageLoadError()">
                    <i class="el-icon-picture-outline"></i>
                </div>
            </el-image>
        </el-card> 



        <el-card  style="width: 500px; height:65px; background:none; margin-bottom:5px;">
            <div v-if="NFT.name" >
                <div class="el-card-name">{{NFT.name}} 
                    <br> 
                    <span style="color:#0375fc;">Guardian 招き猫 <span style="font-weight:bold">#{{NFT.id}}</span></span>
                </div>
            </div>
        </el-card>

        <el-card  style="width: 500px; height:100px; ">
            <div v-if="NFT.description" >
                <div class="el-card-wish">{{NFT.description}}</div>
            </div>
        </el-card>


        <el-card  class="auction_detail" style="width: 500px; height:800px;">

        <div class="chart"
            v-loading="loading"
            element-loading-text="Data loading ..."
            element-loading-spinner="el-icon-loading"
            element-loading-background="rgba(0, 0, 0, 0.2)" >

           <line-chart :chart-data="datacollection_linechart" :options="options_linechart"></line-chart>
        </div>    
            
            <ul>
                <li>auctionId : {{NFT.auctionId}}</li>

                <li>currentPrice :  {{NFT.paymentTokenSymbol}} {{ parseFloat(NFT.currentPrice/(10**18)).toFixed(4)}}</li>
                <li>Start Price :   {{NFT.paymentTokenSymbol}}  {{parseFloat(NFT.startingPrice/(10**18)).toFixed(4)}}</li>
                <li>End Price :     {{NFT.paymentTokenSymbol}}  {{ parseFloat(NFT.endingPrice/(10**18)).toFixed(4) }}</li>

                <li>duration : {{NFT.duration}}</li>
                <li>Start Datetime : {{NFT.startAt}}</li>
                <li>End Datetime : {{NFT.endAt}}</li>
                <li>time left : {{NFT.timeLeft}}</li>
            </ul>
        </el-card>


        <!-- Action Button -->
        <el-card  style="width: 500px; height:100px; ">
            <el-button @click="buyNow()">buy now</el-button>
        </el-card>

        <!-- valuation -->
        <el-card  style="width: 500px; height:445px;">
            <el-table  :data="valuation" stripe>
                <el-table-column prop="trait_type" label="VAULATION" width="250"> </el-table-column>
                <el-table-column prop="value"  width="250"> </el-table-column>      
            </el-table>

        </el-card>

        <!-- Chart -->
        <el-card  class="el-card-chart" style="width: 440px">
            <radar-chart :chart-data="chartData" :options="options" style="background:white; padding:5px 20px;"></radar-chart>
        </el-card>

        <!-- Maneki Power -->
        <el-card  class="el-card-meta" style="width: 500px">
            <el-table class="el-table-meta" :data="NFT.attributes" stripe>
                <el-table-column prop="trait_type" label="MANEKI POWER" width="120"> </el-table-column>
                <el-table-column prop="value" label="value #" width="380"> </el-table-column>      
            </el-table>
        </el-card>

        <div style="clear:both;"></div>
        </div>

        
        <div class="sub-container-tx-details">
            <h2> Blockchain Tx log </h2>
        <!-- Certificate -->
        <el-card style="width:960px">
            <el-table  :data="certificate" stripe>
                <el-table-column prop="trait_type" label="Birth Certificaiton" width="200"> </el-table-column>
                <el-table-column prop="value" > </el-table-column>      
            </el-table>
            <el-link :href=NFT.txURL target="_blank" class="linkVerify" type="primary">Varify Certificate</el-link>
        </el-card>


        
        <!-- Provenance 
        <el-card  style="width: 400px; height:1000px;">
            <div> Provenance </div>
        </el-card>
        -->
        <div style="clear:both;"></div>

        </div>


        <!-- Approve to sell -->
        <el-dialog  :visible.sync="dialog.approveTokenSpend" >  
            <div class="dialog-input-wrap">
            
                <div style ="color:white"> {{NFT.paymentTokenSymbol}} {{NFT.currentPrice}} </div>

            <div slot="footer" class="dialog-footer">
                <el-button @click="dialog.approveTokenSpend = false">Cancel</el-button>
                <el-button type="primary" @click="payWithToken()"> Pay now </el-button>
            </div>
            </div>
        </el-dialog>
        


        <!-- Want to sell -->
        <el-dialog  :visible.sync="dialog.wantToSell" >
            <div class="dialog-input-wrap">
                <h2 style="color:white;"> Want to Sell</h2>

                <el-form :model="sellForm">
                    <el-form-item  >
                    <el-select v-model="sellForm.tokenID" placeholder="Select NFT">
                        <el-option :key="'neko-'+sellForm.tokenID" :label="'招き猫 #'+sellForm.tokenID" :value="sellForm.tokenID"></el-option>
                    </el-select>
                    </el-form-item>

                    <el-form-item >
                        <el-select v-model="sellForm.paymentToken" placeholder="Pay by">
                            <el-option label="CFX" value="cfx:aathhv7a493sc2r6p5g5se7ehjpxsrtcy6863hna4v"></el-option>
                            <el-option label="USDT" value="cfxtest:acepe88unk7fvs18436178up33hb4zkuf62a9dk1gv"></el-option>
                            <el-option label="NEKO" value="cfxtest:acfc7ss6kzp1cga5f342wa9r8m71bkxczpfrapgn9d"></el-option>
                        </el-select>
                    </el-form-item>

                    <el-form-item >
                        <el-input v-model="sellForm.startPrice" placeholder="Start price"></el-input>
                    </el-form-item>

                    <el-form-item >
                        <el-input v-model="sellForm.endPrice" placeholder="End price"></el-input>
                    </el-form-item>

                    <el-form-item >
                        <el-input v-model="sellForm.duration" placeholder="Duration"></el-input>
                    </el-form-item>
                </el-form>

            <div slot="footer" class="dialog-footer">
                <el-button @click="dialog.wantToSell = false">Cancel</el-button>
                <el-button type="primary" @click="wantToSell()"> Approve </el-button>
            </div>
            </div>
        </el-dialog>


    </div>
</template>

<script>

import LineChart from '../charts/LineChart.js'

//import abi_collectible from '../web3/abi_collectible';
import abi_exchange from '../web3/abi_exchange';

// Contract : ERC20 - $NEKO
import abi_neko from '../web3/abi_neko';

import RadarChart from '../charts/RadarChart';

import ConfluxPortalOnboarding from 'conflux-portal-onboarding'
    
    const { Conflux} = require('js-conflux-sdk');

    // In browser: const Conflux = window.TreeGraph.Conflux;

    const conflux = new Conflux({
        url: "https://test.confluxrpc.com",
        networkId: 1
        });


    conflux.provider = window.conflux

    //connect Collectibles Contract 
    // const contract_collectibles =  conflux.Contract({abi:abi_collectible, address:'cfxtest:acbz06p71gzn1e00c3j06gwgnebvgdw1cj50pg1teh'});
    //connect Exchange Contract
    const contract_marketplace = conflux.Contract({abi:abi_exchange, address:'cfxtest:acb2dcx541fe61zs249byr3azpk95ftwea0a9dr8p1'});
    //connect Neko Contract
    const contract_neko = conflux.Contract({abi:abi_neko, address:'cfxtest:aca9u5eu3zvmjv6gdudvf21g9uj2cm60upya76k4e4'});
    //connect Neko Contract
    const contract_usdt = conflux.Contract({abi:abi_neko, address:'cfxtest:acepe88unk7fvs18436178up33hb4zkuf62a9dk1gv'});

    



    const forwarderOrigin = 'http://localhost:8080'
    const onboarding = new ConfluxPortalOnboarding({ forwarderOrigin })
    //onboarding.startOnboarding()


export default {
    components: {
      LineChart,
      RadarChart
    },
    data(){
        return{
            datacollection_linechart: null,
            options_linechart: null,
            contractInstance:null,
            account:null,
            contract :{
                collectibles:null,
                payment:null,
            },
            display :{
                isConnect : false,
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
                id : null,
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
                }],
                auctionId     : null,
                paymentTokenAddress   : null,
                paymentTokenSymbol    : null,
                tokenID       : null,
                startingPrice : null,
                endingPrice   : null,
                duration      : null,
                startAt       : null,
                endAt         : null,
                currentPrice  : null,
                timeLeft      : null,
                
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
            dialog :{
                approveToExchange : false,
                offerForm : false,
                wantToSell : false,
                approveTokenSpend : false
            },
            giftForm : {
                tokenID: '',
                receiverAddr:''
            },
            sellApproveForm : {
                tokenID: '',
            },
            sellForm : {
                sellerAddr :'',
                tokenID: '',
                paymentToken : '',
                startPrice : '',
                endPrice : '',
                duration :'',
            },
            formLabelWidth : '120px',
        }
    },
    created (){
      this.fillData_linechart();
    },
    mounted() {
        this.updateData();
        this.getMetadata(this.$route.params.id);
        this.getauctionDetails(this.$route.params.id);
        console.log('in auction page')
    },
    methods :{      
        fillData_linechart () {
            this.datacollection_linechart = {
                labels: [7,6,5,4,3,2,1],
                datasets: [
                {
                    borderWidth:1,
                    label: 'Maneki $NEKO',
                    fill : true,
                    backgroundColor: 'rgba(255, 99, 132,0.5)',
                    borderColor: 'rgb(0, 150, 255)',
                    pointBackgroundColor: 'rgb(255, 99, 132)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgb(255, 99, 132)',
                    //data: [this.data[6],this.data[5],this.data[4],this.data[3],this.data[2],this.data[1],this.data[0],],
                    data: [10,9,8,7,6,5,4,3,2,1],
                    tension : 0
                }
                ],
            };

            this.options_linechart = {
                scales: {
                    yAxes: [{
                        ticks: {
                        min: this.limit.min,
                        max: this.limit.max,
                        reverse: false,
                        beginAtZero: false,
                        },
                        gridLines: {
                        display: false,
                        },
                    }],
                    xAxes: [{
                        ticks: {
                        beginAtZero: false,
                        },
                        gridLines: {
                        display: false,
                        },
                    }],
                },
            
            responsive: true,
            maintainAspectRatio: false,
            };

        },
        async updateData(){

            // initialize wallet connection 
            // Store init data

            await window.conflux.enable();

            if (typeof window.conflux !== "undefined"){
                console.log('Conflux Portal is installed!');

            try {

                const cfx = window["conflux"]
                const accounts = await cfx.send("cfx_requestAccounts")
                this.account = accounts[0]
                this.shortenAccount = accounts[0].substring(0, 6) + '...' + accounts[0].substring(44, 49)
                this.display.isConnect = true;

                console.log('Connect Accounts :', this.account);


            } catch (error) {
                if (error.code === 4001) {
                    // EIP 1193 userRejectedRequest error
                    console.log("Please connect to ConfluxPortal.")
                } else {
                    console.error(error)
                }
            }
            }


        },
        async updateButton(){        
            if (!ConfluxPortalOnboarding.isConfluxPortalInstalled()) {
                console.log ('Click here to install ConfluxPortal!')
                onboarding.startOnboarding()
                
            } else if (this.accounts && this.accounts.length > 0) {
                console.log('Connected :', this.accounts)
            } else {
                console.log('Connect')
                window.conflux.enable()
            }
        },
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
                    backgroundColor: 'rgba(255, 99, 132,0.2)',
                    borderColor: 'rgb(255, 99, 132,0.2)',
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
        async getMetadata(id){
            const axios = require('axios');
            await axios.get('https://metadata.neko.exchange/token/'+id)
            .then((res) => {
                console.log ('metadata :',res.data);
                //import data form metadata
                this.NFT.id = id;
                this.NFT.transactionHash = res.data.transactionHash;
                this.NFT.txURL  = 'https://testnet.confluxscan.io/transaction/'+ res.data.transactionHash;
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

                console.log('Guardian Metadata', this.NFT);

            }).catch((err) => {
                console.log(err, 'err');
            });    
        },
        imageLoadError () {
            console.log('Image failed to load');
        },

        openWantToSellDialog (_tokenID) {
            console.log('open want to sell dialog')
            this.dialog.wantToSell = true;
            this.sellForm.tokenID = _tokenID;
        },
        buyNow(){
            console.log('Payment token apply :', this.NFT.paymentTokenSymbol);

            if (this.NFT.paymentTokenSymbol == 'CFX') {
                this.payByCoin ();
            } else {
                this.OpenTokenApproveDialog ();
            }
        },
        async OpenTokenApproveDialog(){

            await window.conflux.enable().then((currentWalletAddr) => {

                console.log('token approval ', currentWalletAddr)
                console.log('token selected ', this.NFT.paymentTokenSymbol, this.NFT.currentPrice)

                if(this.NFT.paymentTokenSymbol=='NEKO'){
                        contract_neko.approve(contract_marketplace.address, this.NFT.currentPrice).sendTransaction({
                            from: currentWalletAddr,
                            gas: '0x249F0', //150,000
                            storageLimit: '0x1F4', //500
                            gasPrice: '0x1388', //5000
                        }).confirmed().then((receipt) => {
                            console.log ('approve use NEKO :', receipt)
                            
                            this.dialog.approveTokenSpend = true;

                        })

                } else if (this.NFT.paymentTokenSymbol=='USDT'){
                        contract_usdt.approve(contract_marketplace.address, this.NFT.currentPrice).sendTransaction({
                            from: currentWalletAddr,
                            gas: '0x249F0', //150,000
                            storageLimit: '0x1F4', //500
                            gasPrice: '0x1388', // 5000
                        }).confirmed().then((receipt) => {
                            console.log ('approve use USDT :', receipt)
                            this.dialog.approveTokenSpend = true;
                            

                        })
                }
            })
            
        },
        async payWithToken(){  
            
            await window.conflux.enable().then((currentWalletAddr) => {
                //pay with ERC20 Token
                console.log ('pay for TokenID :', this.NFT.tokenID) 
                console.log ('pay by Token :', this.NFT.paymentTokenAddress) 
                console.log ('current price :', this.NFT.currentPrice) 
                
                contract_marketplace.bidByERC20(this.NFT.tokenID, this.NFT.paymentTokenAddress, this.NFT.currentPrice).sendTransaction({
                    from: currentWalletAddr,
                    gas: '0x493E0', //300,000
                    storageLimit: '0x1F4', // 500
                    gasPrice: '0xBB8', //3000
                }).confirmed().then((receipt) => {
                    console.log ('Succesfully bought!', receipt )

                })
            })

        },
        async payByCoin(){
            console.log('buy this NFT : ',this.NFT.tokenID )

            await window.conflux.enable().then((currentWalletAddr) => {        
                    contract_marketplace.bid(this.NFT.tokenID).sendTransaction({
                        from: currentWalletAddr,
                        gas: '0x493E0', //300,000
                        storageLimit: '0x1F4', //35
                        gasPrice: '0xBB8', //3000
                        value: this.NFT.currentPrice,
                    }).confirmed().then((receipt) => {
                        console.log ('Succesfully bought!', receipt )

                    })

            })

        },
        async getauctionDetails(_tokenId){

            // get premittive details 
            await contract_marketplace.getAuctionByTokenId(_tokenId).call().then((res) => {
                console.log('auction details :', res);


     


                // get current price 
                contract_marketplace.getCurrentPriceByTokenId(_tokenId).call().then((res2) => {
                    console.log('current price:', res2.toString());

                        this.NFT.auctionId              = res[0].toString()
                        this.NFT.sellerAddr             = res[1]
                        this.NFT.tokenID                = res[2].toString()
                        this.NFT.paymentTokenAddress    = res[3]
                        this.NFT.paymentTokenSymbol     = this.determinePaymentToken(res[3])
                        this.NFT.startingPrice          = res[4].toString()
                        this.NFT.endingPrice            = res[5].toString()
                        this.NFT.duration               = res[6].toString()
                        this.NFT.startAt                = res[7].toString()
                        this.NFT.endAt                  = parseInt(res[7]) + parseInt(res[6])
                        this.NFT.timeLeft               = this.getTimeRemaining (this.NFT.endAt)
                        this.NFT.currentPrice           = res2.toString()

                })
            })

        },
        getTimeRemaining(endtime){
            const total = (endtime*1000) - Date.parse(new Date());
            const seconds = Math.floor( (total/1000) % 60 );
            const minutes = Math.floor( (total/1000/60) % 60 );
            const hours = Math.floor( (total/(1000*60*60)) % 24 );
            const days = Math.floor( total/(1000*60*60*24) );

        return { total, days, hours, minutes, seconds };
        },
        determinePaymentToken (_tokenAddress){
            console.log('pay token address',_tokenAddress);
            let lable = 0;
            if(_tokenAddress == "cfxtest:acepe88unk7fvs18436178up33hb4zkuf62a9dk1gv"){
                lable = 'USDT'
            } else if (_tokenAddress == "cfxtest:acfc7ss6kzp1cga5f342wa9r8m71bkxczpfrapgn9d"){
                lable = 'NEKO'
            } else {
                lable = 'CFX'
            }
            return lable
        },

        
     
    }
}

</script>

<style scoped>
.main-container{
}
.sub-container-gaurdian-details, .sub-container-tx-details{
    padding-top: 50px;
    width:1020px;
    margin: 0 auto;
    padding-bottom: 30px;
}
.nft-image {
    padding:0px;
    margin:0px;
    width:400px;
    height: 400px;
}
.el-card {
    float:left;
    margin-left: 20px;
    margin-bottom: 30px;
    background:rgba(0, 0, 0, .4); 
    border: none;
}
.el-card-chart {
    float:left;
    margin-left: 20px;
    margin-bottom: 30px;
    background:rgba(0, 0, 0, .4); 
    border: none;
}
.el-card-wish{
    background-image: url("../../src/assets/images/icon-wish.png");
    background-repeat: no-repeat;
    background-position: top left; 
    padding-left:60px;
    color:white; 
    font-size:1.2em;
    min-height: 100px;
}
.el-card-name {
    background-image: url("../../src/assets/images/icon-meow.png");
    background-repeat: no-repeat;
    background-position: top left; 
    padding-left:60px;
    color:white; 
    font-size:1em;
    min-height: 100px;
}

.el-card-meta {
    background:rgba(0, 0, 0, .4); 
}
.el-table-meta {
    background-color: none;
}
.linkVerify {
    padding: 10px;
    float:right;
}

.dialog-input-wrap{
    width:500px; 
    margin:0 auto; 
    padding-bottom:15px; 
    padding-top:30px; 
    padding-left:20px; 
    padding-right:20px; 
    border:1px solid #62cca3;  
    background:rgba(128, 255, 209, .2);  
    margin-bottom:50px; 
    margin-top:80px; 
    border-radius: 5px;
}
.auction_detail{
}
.auction_detail ul{
    list-style: none;
    color: #ccc;
}
.chart {
padding:10px;
max-width:800px;
margin:10px auto;
border: 1px solid seagreen;
background-color: white;
}
</style>
