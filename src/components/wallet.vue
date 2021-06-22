<template>
    <div>
        <div> Pass in input: address(wallet) => below details, mainly is TESTIMONAL purpose </div>
        <div> Summary </div>
        <div> BNB Balance , Neko Balance, other accepted coin balance </div>
        <div> Total Maneki $Neko  <br><br><br></div>
        <div> records </div>
        <div> NFT Transcations (the trading record) </div>
        <div> Maneki $Neko (Coins invited record</div>
        <div> My Walllet address : {{account}}</div>




    <el-table  :data="NFTList.filter(data => !search || data.tokenID.includes(search))" stripe style="width: 100%">
        <el-table-column prop="date" label="Date" width="180"> </el-table-column>
        <el-table-column prop="tokenID" label="招き猫 #" width="180"> </el-table-column>
        <el-table-column prop="tokenDNA" label="DNA"> </el-table-column>

        <el-table-column align="right">
            <template  slot="header">
                <el-input v-model="search" size="mini" placeholder="Token ID"/>
            </template>

            <template slot-scope="scope">
                <router-link :to="'/token/' +scope.row.tokenID">
                    <el-button size="mini"> View </el-button>
                </router-link>
                    <el-button size="mini" type="primary" @click="wantToSell(scope.row.tokenID)"> Sell </el-button>
            </template>

        </el-table-column>   
             
    </el-table>

    
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

    </div>
</template>

<script>

import getWeb3 from '../web3/web3';

import abi_collectible from '../web3/abi_nekocollectible';
const contract_collectible = '0x9CCD560e93C2be416edE43C4E97941b7b443b9CE';

import abi_exchange from '../web3/abi_exchange';
const contract_exchange = '0xA858F4cA9EB9875105126bB3E027396A5fdC8BE1';


export default {
    data(){
        return{
            contractInstance:null,
            account:null,
            isCollapse:false,
            balance:0,
            tokenSupply:0,
            name:null,

            // NFT 
            NFT : {
                owned:0,
                supply:0,
                balance:0
            },
            // date, tokenID, tokenDNA
            NFTList :[],
            search: '',
            contract :{
                collectibles:null,
                exchange :null
            },
            dialog :{
                approveToExchange : false,
                offerForm : false
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
            formLabelWidth: '120px'

        }
    },
    mounted() {
        if (typeof web3 !== 'undefined') {
    
            console.log('Metamask is installed!');

            getWeb3().then((res) => {
                this.web3 = res;

                //connect Contracts Collectibles, 
                this.contract.collectibles = new this.web3.eth.Contract(abi_collectible, contract_collectible);
                //connect Contracts Exchange
                this.contract.exchange = new this.web3.eth.Contract(abi_exchange, contract_exchange);
                
                //get current signed wallet address
                this.web3.eth.getAccounts().then((accounts) => {
                    [this.account] = accounts;

                this.getNFTOwned();

                }).catch((err) => {
                    console.log(err, 'err!!');
                });
            });
            } else {
                //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');

            }
    },
    methods :{
        getNFTOwned (){        
            this.contract.collectibles.methods.ownedNekos().call({
                from: this.account,
            }).then((res) => {

            for(let i=0; i< res.length ; i++){
                this.NFTList.push({
                    date: '1st July 2021',
                    tokenID: res[i],
                    tokenDNA: '100 '+ i
                });
            }
            console.log('List of NFT owned :', res);

            }).catch((err) => {
                console.log(err, 'err');
            });

        },
        wantToSell (_tokenID) {
            this.approvalForm.tokenID = _tokenID;
            this.dialog.approveToExchange = true;
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
                this.account
                ).send({
            from: this.account,
            }).then((res) => {
                console.log('deal ',res);
            })

        }

    }
}
</script>