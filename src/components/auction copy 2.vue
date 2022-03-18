 <!-- Public Access Page : Guardian ID  -->

<template>
    <div class="main-container">

        <!-- Wallet connection -->
        <div class="wallet-connection"  >
            <div class="token-wrap">
                <div class="cfx-balance">{{this.accountCFXBalance}} CFX</div>
                <div class="neko-balance">{{this.accountNekoBalance}} NEKO</div>
            </div>
            <el-button @click="updateData()" v-if="!display.isConnect"> connect wallet </el-button>
            <div  v-if="display.isConnect" style="padding-top:10px;"> Conflux Network : {{shortenAccount}} </div>
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


        <!-- Auction details -->
        <el-card  class="auction_detail" style="width: 500px; height:400px;">        
            <div v-if="NFT.name" >
                <div style="background-color:red; color:white; font-size:1.6em;padding:20px;">
                    For Sale
                </div>
                <div class="el-card-name">{{NFT.name}} , 
                    <span v-if="NFT.refCount" style="color:#0375fc;">
                        {{5 - NFT.refCount}} blessing left
                    </span>
                    <br> 
                    <span style="color:#0375fc;">MEOW 猫 <span style="font-weight:bold">#{{NFT.id}}</span></span> 
                     
                </div>
            </div>
            <!-- Action Button -->
            <el-button @click="buyNow()" style="float:right;">buy now</el-button>
            <div style="color:white; font-size:1.2em; padding-bottom:10px;"> Maneki Auction </div>
            <div class="chart-frame" >
                <div class="chart-left">
                    <div class="chart-start-price">{{parseInt(NFT.startingPrice/(10**18))}}</div>
                    <div class="chart-end-price">{{ parseInt(NFT.endingPrice/(10**18))}}</div>
                </div>
                <div class="chart-right">
                    <div class="chart-current-price" 
                       :style="{ 
                           'margin-top' : (lineChart.y_axis-5) + 'px', 
                           'margin-left' : (lineChart.x_axis + 20 ) + 'px', 
                           'background-image' : 'url(\'../../src/assets/images/coin-' + NFT.paymentTokenSymbol + '.png\')'  
                           }">
                                {{NFT.paymentTokenSymbol}} {{ parseInt(NFT.currentPrice/(10**18))}} 
                    </div>
                    <div class="chart-line" :style="{ 'width': lineChart.width + 'px','height': lineChart.height + 'px' }" >
                        <div class="chart-dot" :style="{ 'margin-top' : lineChart.y_axis + 'px', 'margin-left' : lineChart.x_axis + 'px' }">
                        </div>
                    </div>
                </div>

                <div style="clear:both;"></div>

                <div class="chart-bottom">
                    <div style="padding-bottom:10px;"> {{countdown}} </div>
                </div>
            </div>

            <div style="clear:both; margin-bottom:20px;"></div>
            <!--
            <ul>
                <li>auctionId : {{NFT.auctionId}}</li>

                <li>currentPrice :  {{NFT.paymentTokenSymbol}} {{ parseFloat(NFT.currentPrice/(10**18)).toFixed(4)}}</li>
                <li>Start Price :   {{NFT.paymentTokenSymbol}}  {{parseFloat(NFT.startingPrice/(10**18)).toFixed(4)}}</li>
                <li>End Price :     {{NFT.paymentTokenSymbol}}  {{ parseFloat(NFT.endingPrice/(10**18)).toFixed(4) }}</li>

                <li>duration : {{NFT.duration}}</li>
                <li>Start Datetime : {{NFT.startAt}}</li>
                <li>End Datetime : {{NFT.endAt}}</li>
                <li>time left : {{ countdown }}</li>
            </ul>
            -->

        </el-card>
        
        <!-- General details  -->
        <el-card  style="width: 500px; height:255px; background:none; margin-bottom:5px;">
            <div v-if="NFT.name" >
                <div style="background-color:red; color:white; font-size:1.6em;padding:20px;">
                    For Sale
                </div>
                <div class="el-card-name">{{NFT.name}} , 
                    <span v-if="NFT.refCount" style="color:#0375fc;">
                        {{5 - NFT.refCount}} blessing left
                    </span>
                    <br> 
                    <span style="color:#0375fc;">MEOW 猫 <span style="font-weight:bold">#{{NFT.id}}</span></span> 
                     
                </div>
            </div>
            <!-- Action Button -->
            <el-button @click="buyNow()" style="float:right;">buy now</el-button>
        </el-card>


        <el-card  style="width: 500px; height:200px; background : #ffe600;">
            <div v-if="NFT.description" >
                <div class="el-card-wish">
                    <div style="background:#00ba2c; height:80px; padding:10px; color:#3f3f3f; 
    border-radius: 10px 10px 0 0;">{{NFT.description}}</div>
                </div>
            </div>
            <div v-if="NFT.piggyBank" >
                <div class="el-card-neko"> 
                    <div style="background:#7ac943; height:40px; padding:10px; color:#3f3f3f; border-radius:0 0 10px 10px;">{{ NFT.piggyBank }} NEKO</div>
                </div>
            </div>
        </el-card>

        <!-- Auction details -->
        <el-card  class="auction_detail" style="width: 500px; height:400px;">        
            <div v-if="NFT.name" >
                <div style="background-color:red; color:white; font-size:1.6em;padding:20px;">
                    For Sale
                </div>
                <div class="el-card-name">{{NFT.name}} , 
                    <span v-if="NFT.refCount" style="color:#0375fc;">
                        {{5 - NFT.refCount}} blessing left
                    </span>
                    <br> 
                    <span style="color:#0375fc;">MEOW 猫 <span style="font-weight:bold">#{{NFT.id}}</span></span> 
                     
                </div>
            </div>
            <!-- Action Button -->
            <el-button @click="buyNow()" style="float:right;">buy now</el-button>
            <div style="color:white; font-size:1.2em; padding-bottom:10px;"> Maneki Auction </div>
            <div class="chart-frame" >
                <div class="chart-left">
                    <div class="chart-start-price">{{parseInt(NFT.startingPrice/(10**18))}}</div>
                    <div class="chart-end-price">{{ parseInt(NFT.endingPrice/(10**18))}}</div>
                </div>
                <div class="chart-right">
                    <div class="chart-current-price" 
                       :style="{ 
                           'margin-top' : (lineChart.y_axis-5) + 'px', 
                           'margin-left' : (lineChart.x_axis + 20 ) + 'px', 
                           'background-image' : 'url(\'../../src/assets/images/coin-' + NFT.paymentTokenSymbol + '.png\')'  
                           }">
                                {{NFT.paymentTokenSymbol}} {{ parseInt(NFT.currentPrice/(10**18))}} 
                    </div>
                    <div class="chart-line" :style="{ 'width': lineChart.width + 'px','height': lineChart.height + 'px' }" >
                        <div class="chart-dot" :style="{ 'margin-top' : lineChart.y_axis + 'px', 'margin-left' : lineChart.x_axis + 'px' }">
                        </div>
                    </div>
                </div>

                <div style="clear:both;"></div>

                <div class="chart-bottom">
                    <div style="padding-bottom:10px;"> {{countdown}} </div>
                </div>
            </div>

            <div style="clear:both; margin-bottom:20px;"></div>
            <!--
            <ul>
                <li>auctionId : {{NFT.auctionId}}</li>

                <li>currentPrice :  {{NFT.paymentTokenSymbol}} {{ parseFloat(NFT.currentPrice/(10**18)).toFixed(4)}}</li>
                <li>Start Price :   {{NFT.paymentTokenSymbol}}  {{parseFloat(NFT.startingPrice/(10**18)).toFixed(4)}}</li>
                <li>End Price :     {{NFT.paymentTokenSymbol}}  {{ parseFloat(NFT.endingPrice/(10**18)).toFixed(4) }}</li>

                <li>duration : {{NFT.duration}}</li>
                <li>Start Datetime : {{NFT.startAt}}</li>
                <li>End Datetime : {{NFT.endAt}}</li>
                <li>time left : {{ countdown }}</li>
            </ul>
            -->

        </el-card>



        <!-- Maneki Power -->
        <el-card  class="el-card-meta" style="width: 500px">
            <el-table class="el-table-meta" :data="NFT.attributes" stripe>
                <el-table-column prop="trait_type" label="MANEKI POWER" width="180"> </el-table-column>
                <el-table-column prop="value" label="value #" > </el-table-column>      
            </el-table>
        </el-card>

        <!-- Chart -->
        <el-card  class="el-card-chart" style="width: 440px">
            <radar-chart :chart-data="chartData" :options="options" style="background:white; padding:5px 20px;"></radar-chart>
        </el-card>

        <!-- Certificate -->
        <el-card style="width:440px">
            <el-table  :data="certificate" stripe>
                <el-table-column prop="trait_type" label="Birth Certificaiton" width="170"> </el-table-column>
                <el-table-column prop="value" > </el-table-column>      
            </el-table>
            <div style="background-color:white">
            <el-link :href=NFT.txURL target="_blank" class="linkVerify" type="primary" style="background-color:white; text-align:center; margin:0 auto; ">Varify Certificate</el-link>
            </div>
        <div style="clear:both;"></div>
        </el-card>

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

import abi_collectible from '../web3/abi_collectible';
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
    const contract_collectibles =  conflux.Contract({abi:abi_collectible, address:'cfxtest:acgrtg0ncb3jmj8621f8fa78bhbxrg38kazk4n25uw'});
    //connect Exchange Contract
    const contract_marketplace = conflux.Contract({abi:abi_exchange, address:'cfxtest:acg512fh0g276nar1n2te23js1808hzrcp2vdc2d2e'});
    //connect Neko Contract
    const contract_neko = conflux.Contract({abi:abi_neko, address:'cfxtest:aca9u5eu3zvmjv6gdudvf21g9uj2cm60upya76k4e4'});
    //connect Neko Contract
    const contract_usdt = conflux.Contract({abi:abi_neko, address:'cfxtest:acepe88unk7fvs18436178up33hb4zkuf62a9dk1gv'});

    



    const forwarderOrigin = 'http://localhost:8080'
    const onboarding = new ConfluxPortalOnboarding({ forwarderOrigin })
    //onboarding.startOnboarding()


export default {
    components: {
      RadarChart
    },
    data(){
        return{
            accountNekoBalance:0,
            accountCFXBalance:0,
            lineChart : {
                width  : 0,
                height : 0,
                x_axis : 0,
                y_axis : 197,
            },
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
                piggyBank : null,
                attributes: [{
                    trait_type:null,
                    value:null,
                    display_type:null
                }],
                auctionId     : null,
                paymentTokenAddress   : null,
                paymentTokenSymbol    : null,
                tokenID       : null,
                startingPrice : 0,
                endingPrice   : 0,
                duration      : 0,
                startAt       : 0,
                endAt         : 0,
                currentPrice  : 0,
                timeLeft      : 0,
                
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
            countdown :0,
            localTime: '',
            runTimer : null
        }
    },
    mounted() {
        this.getMetadata(this.$route.params.id);
        this.getauctionDetails(this.$route.params.id);
        this.getNFTDetails(this.$route.params.id);
        this.runTimer = setInterval(this.showTime, 1000);

    },
    methods :{   

        async getNFTDetails(_id){

            await contract_collectibles.Nekos(_id).call().then((res) => {

                this.NFT.id           = _id;
                this.NFT.power        = res[0]; 
                this.NFT.DNA          = res[1];
                this.NFT.piggyBank    = parseInt(res[4].toString()/10**18).toLocaleString() ;


                if (res[2].toString()!=0){
                    this.NFT.refCount        = res[2].toString();
                } else {
                    this.NFT.refCount        = null;
                }


                if (res[3].toString()!=0){
                    this.NFT.gamma        = res[3].toString();
                } else {
                    this.NFT.gamma        = null;
                }

                console.log('NFT details : ',res[4].toString()/10**18);
                
            })    

        },
        async updateData(){
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

                this.checkWalletBalance();


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

        async checkWalletBalance(){
                // NEKO Balance
                await contract_neko.balanceOf(this.account).then((res) => {
                    this.accountNekoBalance = (parseInt(res.toString()/1e18)).toLocaleString();
                    console.log('Balance $NEKO:', (parseInt(res.toString()/1e18)));
                }).catch((err) => {
                    console.log(err, 'err');
                });

                // CFX Balance
                await conflux.getBalance(this.account).then((res) => {
                    this.accountCFXBalance = (res.toString()/1e18).toFixed(5);
                }).catch((err) => {
                    console.log(err, 'err');
                });

                // Total NFT Owned 
                await contract_collectibles.totalSupply().call({
                    from: this.account,
                }).then((res) => {
                    this.accountMeowBalance = parseInt(res);
                }).catch((err) => {
                    console.log(err, 'err');
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
                //this.popWish(this.NFT.name +' bring this wish come true!', this.NFT.description);

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
                        this.NFT.currentTime            = Math.floor(Date.now() / 1000);
                        this.NFT.timeLeft               = this.getTimeRemaining (this.NFT.endAt)
                        this.NFT.currentPrice           = res2.toString()

                        this.setChartData(
                            270,
                            200,
                            this.NFT.startingPrice, 
                            this.NFT.endingPrice, 
                            this.NFT.currentPrice, 
                            this.NFT.startAt, 
                            this.NFT.endAt, 
                            this.NFT.currentTime 
                        );

                })
            })

        },
        getTimeRemaining(endtime){
            var total = 0;

            if((endtime*1000)>Date.parse(new Date())){
                total = (endtime*1000) - Date.parse(new Date());
                console.log('total :', total);
                
                const seconds = Math.floor( (total/1000) % 60 );
                const minutes = Math.floor( (total/1000/60) % 60 );
                const hours = Math.floor( (total/(1000*60*60)) % 24 );
                const days = Math.floor( total/(1000*60*60*24) );

                this.countdown ='';

                if (days!=0){
                    this.countdown = days + 'd ';
                } 

                if(hours!=0){
                    this.countdown = this.countdown.concat(hours + 'h ');
                }

                if(minutes!=0){
                    this.countdown = this.countdown.concat(minutes + 'm ');
                }

                if(seconds!=0){
                    this.countdown = this.countdown.concat(seconds + 's ');
                }
            } else {
                this.countdown =0;

                //clearInterval(this.runTimer);
                this.currentTime = this.endAt;
            }

            //this.countdown = days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's';

            //setInterval( this.getTimeRemaining(endtime-1000),1000);

            console.log('countdown : ', this.countdown);

            //return { total, days, hours, minutes, seconds };
        },
        getCurrentPrice(){
            let secondsPassed = (Date.parse(new Date())/1000) -  (this.NFT.startAt);

            console.log('secondsPassed : ', secondsPassed)
            console.log('duration : ', this.NFT.duration)

            if (secondsPassed >= this.NFT.duration) {
                this.NFT.currentPrice = this.NFT.endingPrice;
                this.NFT.currentTime  = this.NFT.endAt;
            } else {
                let totalPriceChange = this.NFT.startingPrice - this.NFT.endingPrice;
                let currentPriceChange = totalPriceChange * secondsPassed / this.NFT.duration;
                let currentPrice = this.NFT.startingPrice - currentPriceChange;
                this.NFT.currentPrice = currentPrice;

            console.log('currentPrice : ', currentPriceChange)
            }
        },
        showTime() {

            //let dateTime= new Date();
            //let time = dateTime.toLocaleTimeString();
            this.getTimeRemaining (this.NFT.endAt);
            this.getCurrentPrice();

            this.setChartData(
                270,
                200,
                this.NFT.startingPrice, 
                this.NFT.endingPrice, 
                this.NFT.currentPrice, 
                this.NFT.startAt, 
                this.NFT.endAt, 
                this.NFT.currentTime +=1 
            );

            //console.log('time :', time);
        },
        determinePaymentToken (_tokenAddress){
            console.log('pay token address',_tokenAddress);
            let lable = 0;
            if(_tokenAddress == "cfxtest:acepe88unk7fvs18436178up33hb4zkuf62a9dk1gv"){
                lable = 'USDT'
            } else if (_tokenAddress == "cfxtest:aca9u5eu3zvmjv6gdudvf21g9uj2cm60upya76k4e4"){
                lable = 'NEKO'
            } else {
                lable = 'CFX'
            }
            return lable
        },
        setChartData (_width, _height, _startingPrice, _endingPrice, _currentprice, _startAt, _endAt, _currentTime){


            _startingPrice = (_startingPrice / 10e17);
            _endingPrice   = (_endingPrice / 10e17);
            _currentprice  = (_currentprice / 10e17);

            let yAxis = parseInt ( (_startingPrice - _currentprice) / (_startingPrice - _endingPrice) * _height );
            let xAxis = 0;

            if(_currentTime < _endAt){
                xAxis = parseInt ( (_startAt - _currentTime) / (_startAt - _endAt) * _width );
            } else {
                xAxis = _width;
                yAxis = _height;
            }

            //let yAxis = 300;
            //let xAxis = 300;

            //var yAxis = (_startingPrice - _endingPrice) / _endingPrice * width;
            //var xAxis = (_endAt - _startAt) / _startAt * height;

            let bias = 4;

            this.lineChart.width = _width;
            this.lineChart.height = _height;
            this.lineChart.y_axis = yAxis - bias;
            this.lineChart.x_axis = xAxis - bias; 

            console.log('startingPrice :' + _startingPrice + ' endingPrice :' + _endingPrice + ' current price :' + _currentprice);


            console.log('_startAt :' + _startAt + ' _endAt :' + _endAt + ' _currentTime :' + _currentTime);

            console.log('yAxis :' + yAxis);
            console.log('xAxis :' + xAxis);

        }
        
     
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
.el-card-neko {
    background-image: url("../../src/assets/images/icon-neko.png");
    background-repeat: no-repeat;
    background-position: top left; 
    padding-left:60px;
    color:white; 
    min-height: 100px;
    font-size: 2em;
    
}
.el-card-meta {
    background:rgba(0, 0, 0, .4); 
}
.el-table-meta {
    background-color: none;
}
.linkVerify {
    padding: 10px;
    margin:0 auto;
    text-align: right;
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
.chart-frame{
    background: white;
    height: 200px;
    padding: 20px;
    padding-bottom :40px;
}
.chart-left {
    float: left;
    width: 60px;
    font-size:0.8em;
}
.chart-right {
    float: left;
}
.chart-bottom {
    margin:0 auto;
    text-align: right;
    padding-right:10px;
    padding-top:5px;
}
.chart-label{
    position: relative;
}
.chart-line {
    border: 1px solid white;
    border-left: 2px solid seagreen;
    border-bottom: 2px solid seagreen;
    background-image: url('../../src/assets/images/line_chart_bg-01.png');
    background-repeat: no-repeat;
    background-size: initial;
    
}
.chart-current-price {
    float: left;
    font-size:0.8em;
}
.chart-start-price {
    text-align: right;
    width:55px;
}
.chart-end-price {
    text-align: right;
    margin-top: 170px;
    width:55px;
}
.chart-dot {
    width:10px; height:10px; border-radius: 25px; background-color:green;
}
.wallet-connection{
    width: 400px;
    text-align: right;
    color: #c6c6c6;
    float:right;
    margin-right:30px;
}
.token-wrap{
    width: 400px;
    height: 25px;
    padding:20px;
    margin-top:-20px;
    background: #3f3f3f;
    border-radius: 0 0 10px 10px;
    background:rgba(0, 0, 0, .4); 
    border:solid 1px #0086ff;
}
.cfx-balance {
    text-align: left;
    background-image: url("../../src/assets/images/icon-cfx.png");
    background-repeat: no-repeat;
    background-position: top left; 
    background-size : 23px 23px;
    padding:2px 30px;
    min-height: 27px;
    width:140px;
    float:left;
    color:white;
}
.neko-balance {
    text-align: left;
    background-image: url("../../src/assets/images/icon-neko.png");
    background-repeat: no-repeat;
    background-position: top left; 
    background-size : 23px 23px;
    padding:2px 30px;
    min-height: 27px;
    width:140px;
    color:#fcee21;
    float:left;
}
</style>
