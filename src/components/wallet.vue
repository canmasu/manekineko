<template>
    <div class="mint_container">
        

        <!-- Wallet connectd -->
        <div v-if="display.isConnect" class="wallet-connection"  >
            <div class="token-wrap">
                <div class="cfx-balance">{{this.accountCFXBalance}} CFX</div>
                <div class="neko-balance">{{this.accountNekoBalance}} NEKO</div>
                <div class="meow-balance">{{this.totalGuardian}}</div>
                <div  style="float:left;font-size:0.6em; color:#666;"> Conflux Network : {{shortenAccount}} </div>
            </div>
        </div>


        <!-- Wallet not connected -->
        <div v-if="!display.isConnect" class="wallet-connection"  >
            <div class="token-wrap">
                <div class="cfx-balance"> Conflux networks</div>
                <el-button 
                    style="font-size:1.2em; margin-top:-5px; background-color:#ffe000; color:#ff3600; border:1px solid #ff3600;" 
                    @click="updateButton()" v-if="!display.isConnect"> Connect wallet
                </el-button>
            </div>
        </div>


        <!-- Maneki Meow Logo -->
        <img src="../../src/assets/images/logo-maneki_logo.png" />

        <h1>Asset </h1> 

        <div style="background:none; margin:50px auto;">

            <!-- Asset Summery : NFT and Coins 
            <div class="list">
                <el-card class="Asset-Summery" style="width: 45%; height:230px; color:white;">
                    <div> Guardian owned <br><span style="text-align:center; font-size:5em; ">{{ this.totalGuardian}} </span></div>
                </el-card>
                <el-card class="Asset-Summery" style="width: 45% ;height:230px;">
                    <el-table :data="coinBalance" stripe empty-text="loading...">
                        <el-table-column prop="label" label="Coin"> </el-table-column>
                        <el-table-column prop="balance" label="Balance"> </el-table-column>
                    </el-table>
                </el-card>
                <div style="clear:both"></div>
            </div>
            -->

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
                        <router-link :to="'/token/' + item.id +'/0x0/'">
                            <el-card :body-style="{ padding: '0', }">
                                <img :src="item.url" class="image">
                            </el-card>
                        </router-link>
                        <!--
                        <div v-if="item.description" >
                            <div class="el-card-wish">{{item.description}}</div>
                        </div>
                        -->
                        <!--
                        <div class="bottom clearfix">
                            <el-button size="mini"> 
                                <router-link :to="'/token/' + item.id +'/0x0'"> Share </router-link>
                            </el-button>
                            <el-button size="mini"> 
                                <router-link :to="'/token/' + item.id +'/0x0'"> View </router-link>
                            </el-button>

                            <el-button size="mini"> 
                                <router-link :to="'/wish/' + item.id "> Wish </router-link>
                            </el-button>

                            <el-button size="mini" @click="sendGift(item.id)">Gift</el-button>
                            <el-button size="mini" @click="wantToSell(item.id)">Sell</el-button>
                        </div>
                        -->
                    </div>

                </li>

                <div style="clear:both;"></div>
            </ul>
        </div>

        <!-- Send as gift -->
        <el-dialog title="Send as gift" :visible.sync="dialog.sendGift">
            <el-form :model="giftForm">
                <el-form-item label="NFT" :label-width="formLabelWidth">
                <el-select v-model="giftForm.tokenID" placeholder="Select NFT">
                    <el-option :key="'neko-'+giftForm.tokenID" :label="'招き猫 #'+giftForm.tokenID" :value="giftForm.tokenID"></el-option>
                </el-select>
                </el-form-item>

                <el-form-item label="Receiver" :label-width="formLabelWidth">
                <el-input v-model="giftForm.receiverAddr" placeholder="(BSC) Wallet Address"></el-input>
                </el-form-item>

            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialog.sendGift = false">Cancel</el-button>
                <el-button type="primary" @click="approveSendGift()"> Approve </el-button>
            </div>
        </el-dialog>

    
        <!-- List to exchange -->   
        <el-dialog title="List this NFT to exchange" :visible.sync="dialog.approveToExchange">
            <el-form :model="approvalForm">
                <el-form-item label="NFT" :label-width="formLabelWidth">
                <el-select v-model="approvalForm.tokenID" placeholder="Select NFT">
                    <el-option :key="'neko-'+approvalForm.tokenID" :label="'招き猫 #'+approvalForm.tokenID" :value="approvalForm.tokenID"></el-option>
                </el-select>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialog.approveToExchange = false">Cancel</el-button>
                <el-button type="primary" @click="approveTransfer()"> Approve </el-button>
            </div>
        </el-dialog>


        <!-- Create Auction -->
        <el-dialog title="Make an offer" :visible.sync="dialog.offerForm">
            <el-form :model="offerForm">
                <el-form-item label="NFT" :label-width="formLabelWidth">
                <el-select v-model="offerForm.tokenID" placeholder="Select NFT">
                    <el-option :key="'neko-'+approvalForm.tokenID" :label="'招き猫 #'+approvalForm.tokenID" :value="approvalForm.tokenID"></el-option>
                </el-select>
                </el-form-item>

                <el-form-item label="Coin" :label-width="formLabelWidth">
                    <el-radio-group v-model="offerForm.currency" size="medium">
                    <el-radio border label="BNB" value="BNB" primary></el-radio>
                    <el-radio border label="NEKO" value="NEKO"></el-radio>
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="Amout" :label-width="formLabelWidth">
                    <el-input v-model="offerForm.price"></el-input>
                </el-form-item>

            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialog.offerForm = false">Cancel</el-button>
                <el-button type="primary" @click="makeAnOffer()"> Confirm </el-button>
            </div>
        </el-dialog>   


        <!-- Download wallet -->
        <el-dialog title="Download or connect wallet" :visible.sync="downloadWallet">

            <div class="dialog-input-wrap">


                <div class="dialog-content">
                    <div style="margin: 0 auto; color:white; font-size:1.6em; text-align:center; padding-top:50px; padding-bottom:5px; width:350px; ">
                        <span style="color:yellow; font-size:0.8em;">Maneki-MeowをConfluxウォレットで<br>安全にチェーンに保管する</span><br>
                        Store Maneki-Meow <br>safely on-chain with
                        <br>
                        <img  style="margin-top:30px; width:220px;" src="https://portal.confluxnetwork.org/images/portal_logo.svg" />
                    </div>
                </div>

                <div slot="footer" class="dialog-footer">
                    <el-button style="font-size:1.2em; margin-top:30px; background-color:#ffe000; color:#ff3600; border:1px solid #ff3600; "  > 
                        <a href="https://chrome.google.com/webstore/detail/confluxportal/bjiiiblnpkonoiegdlifcciokocjbhkd" target="_blank">
                            Free downlaod 
                        </a>
                    </el-button>
                    <div style="margin-top:60px;"> 
                        New to Conflux? 
                        <a href="https://portal.confluxnetwork.org/" target="_blank"> Learn more about wallets</a>
                    </div> 
                </div>

            </div>
        </el-dialog>

    </div>
</template>

<script>

import abi_collectible from '../web3/abi_collectible';
import abi_exchange from '../web3/abi_exchange';
import abi_neko from '../web3/abi_neko';



const { Conflux } = require('js-conflux-sdk');
// In browser: const Conflux = window.TreeGraph.Conflux;

const conflux = new Conflux({
url: "https://test.confluxrpc.com",
networkId: 1
});

import ConfluxPortalOnboarding from 'conflux-portal-onboarding'


conflux.provider = window.conflux;

const forwarderOrigin = 'https://portal.confluxnetwork.org/'
const onboarding = new ConfluxPortalOnboarding({ forwarderOrigin })
    

    //connect Collectibles Contract 
    const contract_collectibles =  conflux.Contract({abi:abi_collectible, address:'cfxtest:acgrtg0ncb3jmj8621f8fa78bhbxrg38kazk4n25uw'});
    //connect Exchange Contract
    const contract_exchange = conflux.Contract({abi:abi_exchange, address:'cfxtest:acb2dcx541fe61zs249byr3azpk95ftwea0a9dr8p1'});
    //connect Neko Contract
    const contract_neko = conflux.Contract({abi:abi_neko, address:'cfxtest:aca9u5eu3zvmjv6gdudvf21g9uj2cm60upya76k4e4'});
    //connect Neko Contract
    const contract_USDT = conflux.Contract({abi:abi_neko, address:'cfxtest:acepe88unk7fvs18436178up33hb4zkuf62a9dk1gv'});
    

export default {
    data(){
        return{
            accountNekoBalance:0,
            accountCFXBalance:0,
            accountMeowBalance:0,
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
            downloadWallet: false,
        }
    },
    mounted() {
        if (typeof window.conflux !== 'undefined') {

            console.log('Fluent is installed!');
            
            
            console.log('collectibles : ', contract_collectibles);
            console.log('exchange : ', contract_exchange);
            console.log('the neko : ', contract_neko);

            this.updateData();
           
        } else {
            //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');
            //pop out 
            console.log('Fluent is NOT installed!');
            this.downloadWallet = true;
        }
    },
    methods :{
        async updateData(){
            await window.conflux.request({method: "cfx_requestAccounts"});

            if (typeof window.conflux !== "undefined"){
                console.log('Conflux Portal is installed 2!');

            try {

                const cfx = window["conflux"]
                const accounts = await cfx.request({method: "cfx_requestAccounts"})

                this.account = accounts[0]
                this.shortenAccount = accounts[0].substring(0, 6) + '...' + accounts[0].substring(44, 49)
                this.display.isConnect = true;

                console.log('Connect Accounts :', this.account);

                // Store Coins Balance 
                this.getUSDTTokenBalance();
                this.getNekoTokenBalance();
                this.getCFXBalance();
                this.getTotalGuardian();

                // Store Guardian owned
                this.getNFTOwned();

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
                    this.accountNekoBalance = parseInt(res.toString()/1e18);
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
                    balance : (res.toString()/1e18).toLocaleString(undefined, {minimumFractionDigits: 5}),
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
                    balance : (parseInt(res.toString()/1e18)).toLocaleString(),
                })

            }).catch((err) => {
                console.log(err, 'err');
            });

        },
        async getUSDTTokenBalance (){      

            await contract_USDT.balanceOf(this.account).then((res) => {


                console.log('Balance $USDT:', (res.toString()/1e18));
                this.coinBalance.push({
                    label : 'USDT',
                    balance : (res.toString()/1e18).toLocaleString(2),
                })

            }).catch((err) => {
                console.log(err, 'err');
            });

        },
        async getTotalGuardian (){
            const balance = await  await contract_collectibles.balanceOf(this.account);
            
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

           
            const newBal = await contract_neko.balanceOf(contract_neko.address);
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
            await contract_collectibles.approve(this.contract_exchange , this.approvalForm.tokenID).call({
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
    padding: 5px 10px;
    padding-bottom: 30px;
    background:rgba(0, 0, 0, .4); 
    border-radius: 10px;
    min-width: 350px;
    min-height: 300px;
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
.wallet-connection{
    width: 460px;
    text-align: right;
    color: #c6c6c6;
    float:right;
    margin-right:30px;
}

.dialog-footer {
    text-align: center;
    color: white;
}

.dialog-footer a{
    text-decoration: none;
    color:#ff3600;;
    padding:5px;
}
.dialog-input-wrap{
    width:500px; 
    height:420px;
    margin:0 auto; 
    padding-bottom:15px; 
    padding-top:20px; 
    padding-left:20px; 
    padding-right:20px; 
    border:1px solid #000;  
    background:rgba(0, 0, 99, 0.8);  
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    border-radius: 5px;
}

.dialog-content{
    height:250px;
}

.token-wrap{
    width: 460px;
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
    padding:2px;
    padding-left:30px;
    padding-right:5px;
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
    padding:2px;
    padding-left:30px;
    padding-right:5px;
    min-height: 27px;
    width:140px;
    color:#fcee21;
    float:left;
}
.meow-balance {
    text-align: left;
    background-image: url("../../src/assets/images/icon-meow.png");
    background-repeat: no-repeat;
    background-position: top left; 
    background-size : 23px 23px;
    padding:2px;
    padding-left:30px;
    padding-right:5px;
    min-height: 27px;
    width:60px;
    color:#fcee21;
    float:left;
}
</style>