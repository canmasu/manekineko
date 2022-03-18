<template>
    <div class="mint_container">
        <!--
        <div class="wallet-connection" style="text-align:right;" >
            <el-button @click="updateButton()" v-if="!display.isConnect"> connect wallet </el-button>
            <div style="color:white" v-if="display.isConnect"> Conflux Network : {{shortenAccount}} </div>
        </div>
        -->

        <div class="alert-msg" > 
            Thanks for joining testnet
            <br> Publish 3 (THREE) Maneki-Meow for sale and get rewarded <a href="https://medium.com/@Maneki.Market/1st-1-000-maneki-meow-minted-b597c23427a8" target="_blank">Read More...</a>
            <br> Report bugs <a href="https://discord.gg/gynHJnmV" target="_blank">DISCORD </a> or <a href="https://t.me/ManekiMarket" target="_blank"> Telegram </a>
        </div>

        <!-- Maneki Meow Logo -->
        <img src="../../src/assets/images/logo-maneki_logo.png" />
        
        <h1>Marketplace</h1>


        <div style="background:none; margin:50px auto;">


            <div  v-if="isLoadingListing" style="margin:0 auto; padding:30px; width:300px;">
                Network is busy ... be patience
            </div>

            <ul class="list">
                <li class="item"  v-for="(item) in NFTs" :key="item.id" >
                    <div class="item-wrap">
                        <div class="el-card-name">
                            <div> 
                                <div v-if="item.name"> {{item.name}} </div>
                                <div v-if="!item.name">Maneki 🐱</div>
                                <span style="color:#0375fc;">Guardian 招き猫 <span style="font-weight:bold">#{{item.id}}</span></span>
                            </div>
                        </div>
                        <router-link :to="'/auction/' + item.id +'/0x0/'">
                            <el-card :body-style="{ padding: '0', }">
                                <img :src="item.url" class="image">
                            </el-card>
                        </router-link>
                        <!--
                        <div v-if="item.description" >
                            <div class="el-card-wish">{{item.description}}</div>
                        </div>  
                        -->
                        <div class="wrap_price" style=" background:rgba(255, 255, 255, 0.8);  color:#ff4800; margin:0 auto width:300px ;height:40px;padding:20px; ">
                            <div style="float:left; width:150px;height:40px; "> 
                                <span style="font-size:1em;">Price </span><br>
                                <span style="font-size:1.5em; color:black;">{{item.paymentToken}} {{ parseInt(item.currentPrice/(10**18))}} </span>
                            </div>
                            <router-link :to="'/auction/' + item.id +'/0x0'"  style="float:right;">
                                <el-button >  Buy </el-button>
                            </router-link>
                        </div>
                        <!-- <div> Time left {{ item.timeLeft }} </div> -->

                        <!--
                        <ul>
                            <li>auctionId :     {{item.auctionId}}</li>
                            <li>currentPrice :  {{item.paymentToken}} {{ parseFloat(item.currentPrice/(10**18)).toFixed(4)}}</li>
                            <li>Start Price :   {{item.paymentToken}}  {{parseFloat(item.startingPrice/(10**18)).toFixed(4)}}</li>
                            <li>End Price :     {{item.paymentToken}}  {{ parseFloat(item.endingPrice/(10**18)).toFixed(4) }}</li>
                            <li>Start at :      {{item.startAt}}  </li>
                            <li>duration :      {{item.duration}}</li>
                            <li>  {{item.timeLeft}} left</li>
                            <li>  {{ item.timeLeft/3600 }} left</li>
                        </ul>
                        -->

                        <div class="bottom clearfix"></div>
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
    const contract_collectibles =  conflux.Contract({abi:abi_collectible, address:'cfxtest:acgrtg0ncb3jmj8621f8fa78bhbxrg38kazk4n25uw'});
    //connect Marketplace Contract
    const contract_marketplace = conflux.Contract({abi:abi_exchange, address:'cfxtest:acg512fh0g276nar1n2te23js1808hzrcp2vdc2d2e'});
    //connect Neko Contract
    const contract_neko = conflux.Contract({abi:abi_neko, address:'cfxtest:aca9u5eu3zvmjv6gdudvf21g9uj2cm60upya76k4e4'});
    //connect USDT Contract
    const contract_USDT = conflux.Contract({abi:abi_neko, address:'cfxtest:acepe88unk7fvs18436178up33hb4zkuf62a9dk1gv'});

    
    const forwarderOrigin = 'http://localhost:8080'
    const onboarding = new ConfluxPortalOnboarding({ forwarderOrigin })
    //onboarding.startOnboarding()
    

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
            isLoadingListing : true,
            
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
            contract_marketplace.auctionId().call().then((res) => {
                let auctionId = res[0];
                console.log('total auction : ',auctionId);


           
                for( let i=auctionId; i > auctionId-50; i--){
                    console.log (i)

                    let currentPrice = 0

                    contract_marketplace.getCurrentPriceByAuctionId(i).call().then((res) => {
                        currentPrice = res.toString();
                        console.log('current price :', currentPrice)
               
                        // get current auction details
                        contract_marketplace.getAuctionByAuctionId(i).call().then((res) => {

                            this.isLoadingListing = false;

                            console.log('Auction valid :', res);
                            if(res[0]!='-32603'){
                                //0 auction.id,
                                //1 auction.seller,
                                //2 auction.tokenId,
                                //3 auction.paymentToken,
                                //4 auction.startingPrice,
                                //5 auction.endingPrice,
                                //6 auction.duration,
                                //7 auction.startedAt

                                var startUnixTime = new Date(res[7].toString()*1000);

                                let nowUnixTime = Math.floor(Date.now() / 1000)

                                let timeLeft = nowUnixTime - res[7].toString()

                                console.log('start datetime :', startUnixTime.toLocaleString());
                                console.log('startUnixTime :', res[7].toString());
                                console.log('nowUnixTime :', nowUnixTime);
                                console.log('timeLeft :', timeLeft);

                                
                                this.pumpNekos(
                                    res[0].toString(), // _auctionId
                                    res[1],            // _seller
                                    res[2].toString(), // tokenId
                                    this.determinePaymentToken(res[3]),    // _paymentToken
                                    res[4].toString(), // _startingPrice
                                    res[5].toString(), // _endingPrice
                                    res[6].toString(), // _duration
                                    startUnixTime, // _startAt
                                    currentPrice,      // _currentPrice
                                    timeLeft           // _timeLeft
                                    );
                            
                            }


                        }).catch((err) => {
                            console.log(err, 'err');
                        });


                    })
                }
            })
        },


        pumpNekos(_auctionId, _seller,  _tokenId, _paymentToken, _startingPrice, _endingPrice, _duration, _startAt, _currentPrice, _timeLeft ){

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
                        currentPrice : _currentPrice,
                        startAt : _startAt,
                        timeLeft : _timeLeft

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
            } else if (_tokenAddress == "cfxtest:aca9u5eu3zvmjv6gdudvf21g9uj2cm60upya76k4e4"){
                lable = 'NEKO'
            } else {
                lable = 'CFX'
            }
            return lable
        },
        async updateData(){
            await window.conflux.request({method: "cfx_requestAccounts"});

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
                window.conflux.request({method: "cfx_requestAccounts"})
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
    border: 1px solid black;
    width: 250px;
    padding: 10px;
    background:rgba(0, 0, 0, .4); 
    border-radius: 10px;
    min-width: 350px;
    min-height: 350px;
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
    min-height: 60px;
}


.alert-msg {
    color: yellow; 
    padding:20px; 
    width:40%; 
    margin:0 auto; 
    border:0px solid red; 
    float:right; 
    background:rgba(0, 0, 0, .4);
}
.alert-msg a:link{
    color : white;
    text-decoration:none;
    font-weight: 600;
}

.alert-msg a:visited {
    color : white;
    text-decoration:none;
    font-weight: 600;
}
.alert-msg a:hover {
    color: hotpink;
}
</style>