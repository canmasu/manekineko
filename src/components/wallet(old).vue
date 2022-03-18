<template>
    <div class="mint_container">
        <!-- Maneki Meow Logo -->
        <img src="../../src/assets/images/logo-maneki_logo.png" />

        <div style="background:none; margin:50px auto;">

            <!-- Inner Navigation Tab -->
            <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" router>
                <el-menu-item index="/receivedgift">Asset</el-menu-item>
                <el-menu-item index="/receivedgift">Maneki</el-menu-item>
                <el-menu-item index="/receivedgift">Gift</el-menu-item>
                <el-menu-item index="/sentgift">Sent</el-menu-item>
            </el-menu>

            <!-- Asset Summery : NFT and Coins -->
            <el-card class="Asset-Summery">
                <div> {{account}}</div>
                <div> Asset : {{ this.NFTs.length }} </div>
                <el-table :data="coinBalance" stripe style="width: 100%">
                    <el-table-column prop="label" label="Coin"> </el-table-column>
                    <el-table-column prop="balance" label="Balance"> </el-table-column>
                </el-table>
            </el-card>

            <ul class="list">
                <li class="item"  v-for="(item) in NFTs" :key="item.id" >
                    <div v-if="item.name">
                        <div style="width:50; height:50px; float:left;padding:5px;"><img src="../../src/assets/images/icon-meow.png" /></div>
                        <div style="float:left; padding:10px;"> {{item.name}} <br> Guardian: {{item.id}}</div>
                        <div style="clear:both;"></div>
                    </div>
                    <router-link :to="'/viewGuardian/' + item.id">
                        <el-card :body-style="{ padding: '0', }">
                            <img :src="item.url" class="image">
                        </el-card>
                    </router-link>
                    <div v-if="item.description">
                        <div style="width:50; height:50px; float:left;padding:5px;"><img src="../../src/assets/images/icon-wish.png" /></div>
                        <div style="float:left; padding:10px;">{{item.description}}</div>
                    </div>

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
    

    //connect Collectibles Contract 
    const contract_collectibles =  conflux.Contract({abi:abi_collectible, address:'cfxtest:acbz06p71gzn1e00c3j06gwgnebvgdw1cj50pg1teh'});
    //connect Exchange Contract
    const contract_exchange = conflux.Contract({abi:abi_exchange, address:'cfxtest:acbskvp4y17jg2ykfcc8akdr44ptw2gnpjj685zjnw'});
    //connect Neko Contract
    const contract_neko = conflux.Contract({abi:abi_neko, address:'cfxtest:acfc7ss6kzp1cga5f342wa9r8m71bkxczpfrapgn9d'});
    
    
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

            // NFT 
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
        if (typeof window.conflux !== 'undefined') {

    
            console.log('Conflux Portal is installed!');
            

            console.log('collectibles : ', contract_collectibles);
            console.log('exchange : ', contract_exchange);
            console.log('the neko : ', contract_neko);



            this.coinBalance.push({
                label : 'CFX',
                balance : 8190009988888899090000/10**18,
                price   : parseFloat('521.12'),
            })

            this.coinBalance.push({
                label : 'cUSDT',
                balance : 10000000000000000000/10**18,
                price   : parseFloat('0.1'),
            })

            
            this.coinBalance.push({
                label : 'cETH',
                balance : 90899889000010001122340/10**18,
                price   : parseFloat('3609'),
            })

            this.coinBalance.push({
                label : 'NEKO',
                balance : 9089988900000001122340/10**18,
                price   : parseFloat('0.001'),
            })


            window.conflux.send('cfx_requestAccounts').then((accounts) => {
                this.account = accounts[0];
                console.log('Wallet Addrs: ', this.account);
                
                this.getAccountBalance();
                this.getNFTOwned();

                //get BNB balance
                window.conflux.send('cfx_getBalance').then((cfxBalance) => {
                    
                    console.log('CFX bal :', cfxBalance);

                }).catch((err) => {
                    console.log(err, 'err!!');
                });
            }).catch((err) => {
                console.log(err, 'err!!');
            });
           
        } else {
            //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');

        }
    },
    methods :{

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

            const transactionHash = await contract_neko.transfer('cfxtest:aampn14sv576d0dn56ax4r04dx8pzagkdymfy5gyth',2000000000000000).call({from: this.account});
            console.log('transactionHash:', transactionHash);


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
.list {
    top:50px;
    width: 91%;
    padding:0;
    margin:0 auto;
}

.item {
    border: 1px solid white;
    width: 250px;
    float:left;
    margin:15px 20px;
    padding: 5px 10px;
    padding-bottom: 30px;
    background:rgba(128, 255, 209, .2); 
    border-radius: 10px;
    list-style: none;
}

</style>