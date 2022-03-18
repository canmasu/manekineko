<template>
    <div class="mint_container">
        
        <div class="wallet-connection" style="text-align:right;" >
            <el-button @click="updateButton()" v-if="!display.isConnect"> connect wallet </el-button>
            <div style="color:white" v-if="display.isConnect"> Conflux Network : {{shortenAccount}} </div>
        </div>


        <!-- Maneki Meow Logo -->
        <img src="../../src/assets/images/logo-maneki_logo.png" />
        
        <h1>Marketplace</h1>

        <div style="background:none; margin:50px auto;">


            <ul class="list">
                <li class="item"  v-for="(item) in NFTs" :key="item.id" >
                    <div class="el-card-name">
                        <div> 
                            <div v-if="item.name"> {{item.name}} </div>
                            <div v-if="!item.name">Maneki 🐱</div>
                            <span style="color:#0375fc;">Guardian 招き猫 <span style="font-weight:bold">#{{item.id}}</span></span>
                        </div>
                    </div>
                    <div class="item-wrap">
                        <router-link :to="'/auction/' + item.id +'/0x0/'">
                            <el-card :body-style="{ padding: '0', }">
                                <img :src="item.url" class="image">
                            </el-card>
                        </router-link>
                        <div v-if="item.description" >
                            <div class="el-card-wish">{{item.description}}</div>
                        </div>  
                        <ul>
                            <li>auctionId : {{item.auctionId}}</li>
                            <li>currentPrice : {{item.paymentToken}} {{ parseFloat(item.currentPrice/(10**18)).toFixed(4)}}</li>
                            <li>Start Price : {{item.paymentToken}}  {{parseFloat(item.startingPrice/(10**18)).toFixed(4)}}</li>
                            <li>End Price : {{item.paymentToken}}  {{ parseFloat(item.endingPrice/(10**18)).toFixed(4) }}</li>
                            <li>Start at :     {{ item.startAt}}  </li>
                            <li>duration : {{item.duration}}</li>
                        </ul>

                        <div class="bottom clearfix">

                            <router-link :to="'/auction/' + item.id +'/0x0'">
                                <el-button >  Buy now </el-button>
                            </router-link>
                        </div>
                    </div>

                </li>

                <div style="clear:both;"></div>
            </ul>
        </div>


    



    </div>
</template>

<script>

import abi_collectible from '../web3/abi_collectible';
import abi_exchange from '../web3/abi_exchange';
import abi_neko from '../web3/abi_neko';


import ConfluxPortalOnboarding from 'conflux-portal-onboarding'



    const { Conflux } = require('js-conflux-sdk');
    // In browser: const Conflux = window.TreeGraph.Conflux;

    const conflux = new Conflux({
    url: "https://test.confluxrpc.com",
    networkId: 1
    });
    

    //connect Collectibles Contract 
    const contract_collectibles =  conflux.Contract({abi:abi_collectible, address:'cfxtest:acbz06p71gzn1e00c3j06gwgnebvgdw1cj50pg1teh'});
    //connect Marketplace Contract
    const contract_marketplace = conflux.Contract({abi:abi_exchange, address:'cfxtest:acdn9hk6wcr195y2f7gu3uz5x0g0yn8136jvr6mfnn'});
    //connect Neko Contract
    const contract_neko = conflux.Contract({abi:abi_neko, address:'cfxtest:acfc7ss6kzp1cga5f342wa9r8m71bkxczpfrapgn9d'});
    //connect USDT Contract
    const contract_USDT = conflux.Contract({abi:abi_neko, address:'cfxtest:acepe88unk7fvs18436178up33hb4zkuf62a9dk1gv'});

    
    

    const forwarderOrigin = 'http://localhost:8080'
    const onboarding = new ConfluxPortalOnboarding({ forwarderOrigin })
    onboarding.startOnboarding()
    

export default {
    data(){
        return{
            activeIndex: '1',
            contractInstance:null,
            account:null,
            isCollapse:false,
            tokenSupply:0,
            name:null,
            loading : true,
            
            display :{
                isConnect : false,
            },
            // NFT 
            totalGuardian :0,
            NFT : {
                owned:0,
                supply:0,
                balance:0
            },
            NFTs :[],
            search: '',
            contract :{
                collectibles:null,
                exchange :null
            },
            dialog :{
                approveToExchange : false,
                offerForm : false,
                sendGift : false,
            },
            approvalForm: {
                tokenID: ''
            },
            offerForm: {
                tokenID: '',
                price: '',
                currency:'',
                buyer:''
            },
            giftForm : {
                tokenID: '',
                receiverAddr:''
            },
            formLabelWidth: '120px',
            coinBalance : [],
            coinPrice :'',
            web3:null,

        }
    },
    mounted() {
            
            console.log('collectibles : ', contract_collectibles);
            console.log('exchange : ', contract_marketplace);
            console.log('the neko : ', contract_neko);
            //this.updateData();
            this.getAuctions();
 
    },
    methods :{
        async getAuctions() {
            let latestEpoch  = 0;

            // get epochNumber 
            await conflux.getEpochNumber().then((receipt) => {
                console.log('epochNumber :', receipt)
                latestEpoch = receipt - 1;
            })

            // Filter by :
            /**  
             * event 
             * AuctionCreated(
             * uint64 auctionId, 
             * uint256 tokenId, 
             * IERC20 paymentToken, 
             * uint256 startingPrice, 
             * uint256 endingPrice, 
             * uint256 duration); 
             * */

            const { address, topics } = contract_marketplace.AuctionCreated(null, null,null, null, null, null); 

            for (let i=59617330; i<latestEpoch ;i+=999){

                let valTo = i + 999;

                if(valTo>latestEpoch){
                    valTo = latestEpoch;
                }

                await conflux.getLogs({ address, topics, fromEpoch: i, toEpoch: valTo }).then((logs) => {
                
                    if(logs.length != 0){
                        console.log ('auction log :', logs);

                        for ( let j=0; j<logs.length; j++){

                            let decoded = contract_marketplace.abi.decodeLog(logs[j]);
                            console.log('decoded :',decoded);
                            console.log('decoded :',decoded.array[3].toString());

                            // get current price 
                            contract_marketplace.getCurrentPriceByAuctionId(decoded.array[0].toString()).call().then((res) => {
                                let currentPrice = res.toString();
                                console.log ('currentPrice :', currentPrice);

                                this.pumpNekos(
                                    decoded.array[0].toString(), // _auctionId
                                    decoded.array[1].toString(), // _tokenId
                                    decoded.array[1].toString(), // _seller
                                    this.determinePaymentToken(decoded.array[2].toString()),    // _paymentToken
                                    decoded.array[3].toString(), // _startingPrice
                                    decoded.array[4].toString(), // _endingPrice
                                    decoded.array[5].toString(), // _duration
                                    currentPrice,                // _currentPrice
                                    );


                            }).catch((err) => {
                                console.log(err, 'err');
                            });

                        

                        }
                    }
                })
            }



        },
        pumpNekos(_auctionId, _tokenId, _seller, _paymentToken, _startingPrice, _endingPrice, _duration, _currentPrice ){

                // retrive NFT Details
                const axios = require('axios');
                axios.get('https://metadata.neko.exchange/token/'+ _tokenId)
                .then((metadata) => {

                    this.NFTs.push({
                        id: _tokenId,
                        power: metadata.data.attributes[3].value,
                        DNA: metadata.data.art_dna,
                        url: metadata.data.image,

                        name : metadata.data.name,
                        description : metadata.data.description,
                        image : metadata.data.image,
                        gamma : metadata.data.gamma,

                        auctionId : _auctionId,
                        seller : _seller,
                        paymentToken : _paymentToken,
                        startingPrice : _startingPrice,
                        endingPrice : _endingPrice,
                        duration : _duration,
                        currentPrice : _currentPrice

                    });

                }).catch((err) => {
                    console.log(err, 'err');
                });
                console.log ("NFTs :",this.NFTs)


        },
        determinePaymentToken (_tokenAddress){
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

                // Store Coins Balance 
                this.getCFXBalance();
                this.getNekoTokenBalance();
                this.getUSDTTokenBalance();
                this.getTotalGuardian();

                // Store Guardian owned
                this.getNFTOwned();


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

        async getCFXBalance(){

            await conflux.getBalance(this.account).then((res) => {

                this.coinBalance.push({
                    label : 'CFX',
                    balance : (res.toString()/1e18).toFixed(2),
                })

                console.log('DIP :', res.toString());
                console.log('$CFX :', (res.toString()/1e18).toFixed(2));

            }).catch((err) => {
                console.log(err, 'err!!');
            });

        },
        async getNekoTokenBalance (){      
            await contract_neko.balanceOf(this.account).then((res) => {

                console.log('Balance $NEKO:', (res.toString()/1e18).toFixed(2));
                this.coinBalance.push({
                    label : 'NEKO',
                    balance : (res.toString()/1e18).toFixed(2),
                })

            }).catch((err) => {
                console.log(err, 'err');
            });

        },
        async getUSDTTokenBalance (){      
            await contract_USDT.balanceOf(this.account).then((res) => {

                console.log('Balance $USDT:', (res.toString()/1e18).toFixed(2));
                this.coinBalance.push({
                    label : 'cUSDT',
                    balance : (res.toString()/1e18).toFixed(2),
                })

            }).catch((err) => {
                console.log(err, 'err');
            });

        },
        async getTotalGuardian (){
            const balance = await contract_collectibles.balanceOf(this.account);
            this.totalGuardian = balance[0];
            console.log('Total Guardian Owned:',balance); 
        },

        async  getAccountBalance() {

            await window.conflux.send('cfx_getBalance').then((cfxBalance) => {
                
                console.log('CFX 2 bal :', cfxBalance);

                }).catch((err) => {
                    console.log(err, 'err!!');
                });


            const balance = await contract_collectibles.balanceOf(this.account);
            console.log('No. of NFT owned :',balance); // "4999998839889983249999999950307784"

            const nekoBal = await contract_neko.balanceOf(this.account);
            console.log('MY balance: ', nekoBal);

            const nekos = await contract_collectibles.Nekos(1);
            console.log('Nekos : ', nekos);

            await contract_collectibles.ownedNekos().call({
                from: this.account,
                }).then((NFTowned)=> {
                    console.log('NFT owned 2: ', NFTowned[0]);
                });

           
            const newBal = await contract_neko.balanceOf('cfxtest:aampn14sv576d0dn56ax4r04dx8pzagkdymfy5gyth');
            console.log('AAMP balance: ', newBal[0]);


        },

        async getNekoBalance (){
            await this.contract.neko.balanceOf(this.account).then((res) => {
                console.log('neko bal:',res);
                console.log('neko acc:',this.account);
            })
        },



      
        async getNFTOwned (){
      
            // retrive owned NFTs
            await contract_collectibles.ownedNekos().call({
                from: this.account,
            }).then((res) => {
        
                console.log ('NFT :',res);

            for (let i = 0; i < res.length; i += 1) {
                // retrive NFT Details
                const axios = require('axios');
                axios.get('https://metadata.neko.exchange/token/'+ res[i])
                .then((metadata) => {

                  //make valuation
                  let pow = metadata.data.attributes[3].value.toString().replace(',', '')

                  //valuation return the decimal within 0 - 1
                  //scarcity 0 - 100%
                  var Power = pow/2;
                  var Mean  = 250000;
                  var Range = 250000;
                  var BasePrice = 0.0025;
                  var VFactor = 0;      // valuation Factor
                  var valuation = 0;
                  var scarcity = 0;
                  var PFactor  = 5;     // price Factor 

                  if (Power>250000){
                      // power above 250k
                      VFactor = (((Power-Mean)/100000)**5)/97.65625;
                  } else {
                      // power below 250k
                      VFactor = (((Range-Power)/100000)**5)/97.65625;
                  }
                  
                  valuation = VFactor * PFactor;

                  // x10
                  if (valuation * 100 < 18.8 ){
                      scarcity = 18.8 + VFactor * 10;
                  } else {
                      scarcity = VFactor * 100;
                  }


                    var generation = parseInt(metadata.data.attributes[0].value);
                    var gen_Display = 0;

                    if (generation<6){
                        if(generation==1) { gen_Display = "α" }
                        if(generation==2) { gen_Display = "β" }
                        if(generation==3) { gen_Display = "γ" }
                        if(generation==4) { gen_Display = "δ" }
                        if(generation==5) { gen_Display = "ε" }
                    } else {
                        gen_Display = generation;
                    }


                    this.NFTs.push({
                        id: res[i][0],
                        power: metadata.data.attributes[3].value,
                        DNA: metadata.data.art_dna,
                        url: metadata.data.image,
                        gen : gen_Display,
                        
                        name : metadata.data.name,
                        description : metadata.data.description,
                        image : metadata.data.image,
                        gamma : metadata.data.gamma,
                        BNB : BasePrice + valuation,
                        BUSD : (BasePrice + valuation)*this.coinBalance[0].price,
                        scarcity : scarcity,
                    });

                }).catch((err) => {
                    console.log(err, 'err');
                });
            }



            this.loading = false;

            }).catch((err) => {
                console.log(err, 'err');
            });

        },
        wantToSell (_tokenID) {
            this.approvalForm.tokenID = _tokenID;
            this.dialog.approveToExchange = true;
        },
        sendGift (_tokenID) {
            this.giftForm.tokenID = _tokenID;
            this.dialog.sendGift = true;
        },
        async approveSendGift (){   
            // Approve :to send this NFT as a gift to this address
            console.log('sender', this.account);
            console.log('reciever', this.giftForm.receiverAddr);
            await contract_collectibles.sendAsGift(this.giftForm.receiverAddr, this.giftForm.tokenID).call({
            from: this.account,
            }).then((res) => {
                console.log('approve gift sending',res);
                this.dialog.sendGift = false;
            })

        },
        async approveTransfer (){
            // Approve : this NFT transfer to Exchange
            await contract_collectibles.approve(this.contract_marketplace , this.approvalForm.tokenID).call({
            from: this.account,
            }).then((res) => {
                console.log('approve ',res);
                
                this.offerForm.tokenID = this.approvalForm.tokenID;
                this.dialog.approveToExchange = false;
                this.dialog.offerForm = true;
            })
        },

        makeAnOffer (){
            // Offer : Set the price for sale
            if (this.offerForm.currency=="BNB"){
                this.offerForm.currency = true;
            } else {
                this.offerForm.currency = false;
            }

            this.contract.exchange.methods.offer(
                this.offerForm.tokenID,
                this.offerForm.price,
                this.offerForm.currency,
                ).send({
            from: this.account,
            }).then((res) => {
                console.log('deal ',res);
                this.dialog.offerForm = false;
            })

        }

    }
}
</script>

<style scoped>
h1{
    width: 85%;
    font-size: 3em;
    color:rgba(255, 255, 0, 0.7);
    margin:0 auto;
    font-weight: 100;
    margin-bottom: 50px;
}
.Asset-Summery {
    float: left;
    min-width: 350px;
    background:rgba(128, 255, 209, .2); 
    margin:15px 20px;
    padding: 5px 10px;
}
.list {
    top:50px;
    width: 91%;
    padding:0;
    margin:0 auto;
}
.item {
    width: 250px;
    float:left;
    list-style: none;
    min-width: 350px;
    min-height: 500px;
    margin:50px 20px;
    margin-right:50px;
}
.item-wrap {
    border: 1px solid white;
    width: 250px;
    padding: 5px 10px;
    padding-bottom: 30px;
    background:rgba(128, 255, 209, .2); 
    border-radius: 10px;
    min-width: 350px;
    min-height: 500px;
}
.el-card-wish{
    background-image: url("../../src/assets/images/icon-wish.png");
    background-repeat: no-repeat;
    background-position: top left; 
    padding-top: 5px;
    margin-top:5px;
    padding-left:60px;
    color:white; 
    font-size:1.2em;
    min-height: 100px;
}
.el-card-name{
    background-image: url("../../src/assets/images/icon-meow.png");
    background-repeat: no-repeat;
    background-position: top left; 
    padding-left:60px;
    color:white; 
    font-size:1.2em;
    min-height: 70px;
}
</style>