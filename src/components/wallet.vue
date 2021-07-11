<template>
    <div>

      <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" >
        <el-menu-item index="1">Gift received</el-menu-item>
        <el-menu-item index="3">Gift sent</el-menu-item>
      </el-menu>

    <el-card>
        <div> My Walllet address : {{account}}</div>
        <div> BALANCE </div>
        <div> BNB  : {{parseFloat(balance.BNB/10**18).toFixed(2)}}</div>
        <div> NEKO : {{(balance.NEKO/10**18).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}}</div>
        <div> CAKE : {{(balance.CAKE/10**18).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}}</div>
        <div> BAKE : {{(balance.BAKE/10**18).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}}</div>



    <el-table  :data="NFTs.filter(data => !search || data.id.toLowerCase().includes(search.toLowerCase()))" stripe style="width: 100%">
        <el-table-column prop="url" label="url">
            <template slot-scope="scope">
                <el-image :src="scope.row.url" class="nft-image">
                    <div slot="placeholder" class="image-slot">
                        Loading...<span class="dot">...</span>
                    </div>
                    <div slot="error" class="image-slot">
                        <i class="el-icon-picture-outline"></i>
                    </div>
                </el-image>
            </template>
        </el-table-column>

 

        <el-table-column prop="id" label="招き猫 #"> </el-table-column>
        <el-table-column prop="gen" label="Generation"> </el-table-column>
        <el-table-column prop="DNA" label="DNA"> </el-table-column>
        <el-table-column prop="power" label="power"> </el-table-column>

        <el-table-column align="right" :min-width="120">
            <template slot-scope="scope">
                    <el-button size="mini"> <router-link :to="'/token/' +scope.row.id+ '/0x0'">View </router-link></el-button>   
                    <el-button size="mini" type="info" @click="sendGift(scope.row.id)"> Gift </el-button>
                    <el-button size="mini" type="primary" @click="wantToSell(scope.row.id)"> Sell </el-button>
            </template>

        </el-table-column>   
             
    </el-table>


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
    </el-card>
    </div>
</template>

<script>

import getWeb3 from '../web3/web3';

import abi_collectible from '../web3/abi_collectible';
const contract_collectible = '0x8e2F7e97f07bF6454a62FAECb4402A62B7C57e22';

import abi_exchange from '../web3/abi_exchange';
const contract_exchange = '0x3Cd4B1b772629dCe341D8f1Db0B94aEba9964236';

// Contract : ERC20 - $NEKO
import abi_neko from '../web3/abi_neko';
const contract_neko = '0xdF3CF86Faed8a1936F3dB48a374E981e3fFC3164';
// Contract : ERC20 - $CAKE
const contract_cake = '0xf7D0A2f1B4e8A1c44eF67019C3d0D8F89107fEA1';
// Contract : ERC20 - $BAKE
const contract_bake = '0x595724E0851ac2325dc509c17D0cF80A940581f2';


export default {
    data(){
        return{
            activeIndex: '1',
            contractInstance:null,
            account:null,
            isCollapse:false,
            tokenSupply:0,
            name:null,

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
            balance : {
                BNB :0,
                NEKO : 0,
                CAKE : 0,
                BAKE : 0
            }

        }
    },
    mounted() {
        if (typeof web3 !== 'undefined') {
    
            console.log('Metamask is installed!');

            getWeb3().then((res) => {
                this.web3 = res;

                //connect Collectibles Contract 
                this.contract.collectibles = new this.web3.eth.Contract(abi_collectible, contract_collectible);
                //connect Exchange Contract
                this.contract.exchange = new this.web3.eth.Contract(abi_exchange, contract_exchange);
                //connect Neko Contract
                this.contract.neko = new this.web3.eth.Contract(abi_neko, contract_neko);
                this.contract.cake = new this.web3.eth.Contract(abi_neko, contract_cake);
                this.contract.bake = new this.web3.eth.Contract(abi_neko, contract_bake);
                
                //get current signed wallet address
                this.web3.eth.getAccounts().then((accounts) => {
                    this.account = accounts[0];
                    this.getNFTOwned();
                    this.getNekoBalance();
                    this.getCakeBalance();
                    this.getBakeBalance();

                    //get BNB balance
                    this.web3.eth.getBalance(this.account).then((res) => {
                        
                        console.log('BNB bal :', res);
                        this.balance.BNB = res;

                    }).catch((err) => {
                        console.log(err, 'err!!');
                    });



                }).catch((err) => {
                    console.log(err, 'err!!');
                });
           


            });
            } else {
                //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');

            }
    },
    methods :{
        getNekoBalance (){
            this.contract.neko.methods.balanceOf(this.account).call({
            }).then((res) => {
                this.balance.NEKO = res;
                console.log('NEKO bal: ', res);
            })
        },
        getCakeBalance (){
            this.contract.cake.methods.balanceOf(this.account).call({
            }).then((res) => {
                this.balance.CAKE = res;
                console.log('CAKE bal: ', res);
            })
        },
        getBakeBalance (){
            this.contract.bake.methods.balanceOf(this.account).call({
            }).then((res) => {
                this.balance.BAKE = res;
                console.log('BAKE bal: ', res);
            })
        },
        getNFTOwned (){        

            // retrive owned NFTs
            this.contract.collectibles.methods.ownedNekos().call({
                from: this.account,
            }).then((res) => {

            for (let i = 0; i < res.length; i += 1) {
                // retrive NFT Details
                this.contract.collectibles.methods.getNekoDetails(res[i]).call({
                    from: this.account,
                }).then((Neko) => {
                    var generation = parseInt(Neko[2].substr(32, 6));
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
                        id: Neko[0],
                        power: Neko[1],
                        DNA: Neko[2],
                        url: 'https://nft.neko.exchange/' + Neko[0] + '.svg',
                        gen : gen_Display,
                    });

                }).catch((err) => {
                    console.log(err, 'err');
                });
            }

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
        approveSendGift (){   
            // Approve :to send this NFT as a gift to this address
            console.log('sender', this.account);
            console.log('reciever', this.giftForm.receiverAddr);
            this.contract.collectibles.methods.sendAsGift(this.giftForm.receiverAddr, this.giftForm.tokenID).send({
            from: this.account,
            }).then((res) => {
                console.log('approve gift sending',res);
                this.dialog.sendGift = false;
            })

        },
        approveTransfer (){
            // Approve : this NFT transfer to Exchange
            this.contract.collectibles.methods.approve(contract_exchange , this.approvalForm.tokenID).send({
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