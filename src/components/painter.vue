<template>
    
    <div class="mint_container">

        <div class="wallet-connection" style="text-align:right;" >
            <el-button @click="updateButton()" v-if="!display.isConnect"> connect wallet </el-button>
            <div style="color:white" v-if="display.isConnect"> Conflux Network : {{shortenAccount}} </div>
        </div>
        <img src="../../src/assets/images/logo-maneki_logo.png" />

        <el-card>
        <h1 style="color: white;"> Painter Machine </h1>
        <!-- Form - Part 1, Guadian  -->
        <el-form class ="gashapon" :model="form"  v-if="display.isGuardian" :rules="wishRules" ref="guardianForm">
            <el-form-item prop="guardian" style="height:95px;">
                <router-link :to="'/wallet'"> 
                 <el-image
                    style="max-width:195px; max-height:95px; padding:0 46px; margin-top:25px;"
                    :src="newNFT.guardianIMG"
                    >

                    <div slot="placeholder" class="image-slot">
                        Loading<span class="dot">...</span>
                    </div>
                    <div slot="error" class="image-slot" >
                        <div class="el-icon-picture"></div>
                    </div>
                
                </el-image>

                </router-link>       
            </el-form-item> 

            <el-form-item  style="padding-top:130px; text-align:center;">
                <img class="btn_twister" src="../../src/assets/images/twister.png" v-on:click="confirmToPaint" />
            </el-form-item>
      

        </el-form>

        <h1> Make a wish! </h1>

        <h1> Total Neko Collectibles Token (NCT): {{NFT.supply}} </h1>
        <div> Collectible Pool balance : {{poolBalance}} </div>
        <div> Collector Wallet address : {{account}}</div>
        <div> Collector $NEKO : {{this.accountNekoBalance}}</div>
        <div> Neko Collectibles Token (NCT) : {{NFT.balance}}</div>
        <div> LIST of NCT : {{NFT.owned}}</div>

        </el-card>


        <el-dialog :visible.sync="dialog.mint">

            <div class="dialog-input-wrap">
                <img src="../../src/assets/images/sending.gif" style="width:500px; height:350px; margin:0 auto;"/>
            </div>
        </el-dialog>




        <el-dialog :visible.sync="dialog.gamma">
            <div class="dialog-input-wrap">
            <div> Required a guardian neko to send your wish</div>
                <router-link :to="'/wallet'"> 
                    <el-button icon="el-icon-search" > Select now!</el-button> 
                </router-link>
            </div>
        </el-dialog>


        <el-dialog :visible.sync="dialog.success">
            
            <div class="dialog-input-wrap" style="color:white;">
                <div> 招き猫 {{ newNFT.id }}</div>
                <div> Wish : {{ newNFT.wish }}</div>
                <div> Name : {{ newNFT.name }}</div>
                <div> Guardian {{ newNFT.guardian }}</div>
                <div> Super Guardian : {{ newNFT.gammaID }}</div>
                <div> Power {{ newNFT.power }}</div>
                <router-link :to="'/token/' + newNFT.id+ '/0x0'"> 
                    <el-button type="gashapon"  > Peep now!</el-button>
                </router-link>
            </div>
        </el-dialog>

    </div>
</template>

<script>


// Contract : Collectibles 
import abi_collectible from '../web3/abi_collectible';


// Contract : ERC20 - $NEKO
import abi_neko from '../web3/abi_neko';



const { Conflux} = require('js-conflux-sdk');
const conflux = new Conflux({
    url: "https://test.confluxrpc.com",
    networkId: 1,
});



import ConfluxPortalOnboarding from 'conflux-portal-onboarding'


console.log('conflux version :', conflux.version);


conflux.provider = window.conflux

//connect Collectibles Contract 
const contract_collectibles =  conflux.Contract({abi:abi_collectible, address:'cfxtest:acgrtg0ncb3jmj8621f8fa78bhbxrg38kazk4n25uw'});

//connect Neko Contract
const contract_neko = conflux.Contract({abi:abi_neko, address:'cfxtest:aca9u5eu3zvmjv6gdudvf21g9uj2cm60upya76k4e4'});


const forwarderOrigin = 'http://localhost:8080'
const onboarding = new ConfluxPortalOnboarding({ forwarderOrigin })
//onboarding.startOnboarding()


export default {
  name: 'App',
  data() {
    return {
        web3: null,
        account:null,
        shortenAccount : null,
        accountNekoBalance:0,
        poolBalance:0,
        selected : 0,
        display :{
            isConnect : false,
            isGuardian : true,
            isName : false,
            isWish : true,
            isPayment : false,
        },
        NFT : {
            owned:0,
            supply:0,
            balance:0
        },
        contract :{
            collectibles:null,
            payment:null,
            neko:null,
            ERC20:null
        },
        payment : {
            tokens : [],
            key : 0,
        },
        dialog :{
            mint :false,
            success: false,
            gamma : false,
            wish : false,
            name : false,
            payment : false,
        },
        dialogTableVisible: false,
        dialogApprove : false,
        dialogMint:false,
        form: {
            name: '',
            guardian: '',
            payment: '',
            wish: '',
        },
        newNFT : {
            id: '',
            img: '',
            wish: '',
            name: '',
            guardian : '',
            guardianIMG :'',
            DNA: '',
            gammaID: '',
            power: '',
            refCount: '',
        },
        wishRules: {
                wish: [
                    { required: true, message: 'Please make a wish', trigger: 'blur' },
                    { min: 5, max: 200, message: 'Message within 5 to 200 charaters', trigger: 'blur' }
                ],
        },    
        nameRules: {
                name: [
                    { required: true, message: 'Give a name', trigger: 'blur' },
                    { min: 3, max: 20, message: 'Message within 3 to 20 charaters', trigger: 'blur' }
                ],
        },  
        paymentRules : {
                payment: [
                    { required: true, message: 'select a payment token', trigger: 'change' }
                ]
        },          
        formLabelWidth: '120',
        paymentSelected : {},
    };
  },
  created() {
        this.form.payment = this.payment.tokens[0];
  },
  mounted() {
      
    if (typeof window.conflux !== "undefined") {
        console.log('Conflux portal is installed!');
        this.updateData();

    } else {
        //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');
    }
  },
  methods:{
    isConfluxPortalInstalled(){
        return Boolean(window.conflux && window.conflux.isConfluxPortal)
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
    async getAccounts(){
      try {
        const accounts = await conflux.provider.send("cfx_requestAccounts")
        console.log('get account :',accounts[0]);

      } catch (error) {
        console.error(error)
      }
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


            // load data
            this.getGuardian();

            this.getNeko();
            this.getGeneralDetails();
            this.getNFTOwned();
            this.getNFTSupply();

            //this.checkNetworkID();
            this.checkNekoBalance();
            //this.getPaymentToken();
            this.initCFXPaymentToken();

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
    gotoGuardian () {
            this.display.isGuardian = true;
    },
    confirmToPaint () {
        if (this.form.guardian!='guardian'){ 
            this.display.isGuardian = true;
            //trigger painting
            this.generateNewNFT(this.form.guardian);
            
        } else {
            this.dialog.gamma = true;
        }
    },
    getGuardian (){
        this.newNFT.guardian = this.$route.params.guardian;
        this.newNFT.guardianIMG = 'https://nft.neko.exchange/gif/' + this.$route.params.guardian + '.gif'
        this.form.guardian = this.newNFT.guardian;

        console.log('Guardian id : ', this.newNFT.guardian);
    },

    generateMachineInput (){

        // 3D = 999  ORIGIN
        // 4D = 0001 PAINTER : Source Code that use to generate art work 0001
        // 4D = 0001 SERIES  : New Designs added by batch 0001
        // 11D = BIRTHDAY 01610117854

        var machineInput1 = "999000100010";
        var machineInput2 = "99900010001";
        var machineInput = null;

        var localtime = Math.round((new Date()).getTime() / 1000);
        var tokyo_offset = 540*60;
        var tokyoTime = (localtime + tokyo_offset);

        if (tokyoTime<10000000000){
        machineInput = machineInput1.concat(tokyoTime);
        } else {
        machineInput = machineInput2.concat(tokyoTime);
        }
        return machineInput;
    },
 
    async generateNewNFT(_id){

        await contract_collectibles.Nekos(_id).call({
        from: this.account
        }).then((res) => {

            /**
             * power       : _power,
             * DNA         : _DNA,
             * refCount    : _refCount,
             * gammaNekoID : _gammaNekoID
             */

            let _transactionHash = 0;

            console.log('Painter :',res);

            this.newNFT.transactionHash = _transactionHash;
            this.newNFT.id      = _id;
            this.newNFT.img     = 'https://nft.neko.exchange/' + _id + '.svg'
            this.newNFT.wish    = this.form.wish;
            this.newNFT.name    = this.form.name;
            this.newNFT.guardian= this.form.guardian;
            this.newNFT.DNA     = res.DNA.toString();
            this.newNFT.gammaID = res.gammaNekoID.toString();
            this.newNFT.power   = res.power[0].toString(); 
            this.newNFT.refCount= res.refCount.toString();

            console.log('gen new NFT',this.newNFT);
            this.generateFile ();

            //display success tab
            this.dialog.mint = false;
            this.dialog.success = true;
            
        })
    },
    async generateFile (){
            // generate Metadata and GIF
            const {data:res2} = await this.$http.post('/painter/', this.newNFT);
            console.log('api data : ', res2);
    },

// Retrieve General Information
    async getNeko(){

        await contract_neko.balanceOf(contract_collectibles.address).then((res) => {
            this.poolBalance = (res.toString()/1e18).toFixed(5);
            console.log('Collectible Contract $NEKO Bal :',(res.toString()/1e18).toFixed(5));
        }).catch((err) => {
            console.log(err, 'err');
        });
    },
    async getGeneralDetails(){
        await contract_collectibles.balanceOf(this.account).call().then((res) => {
            this.NFT.balance = parseInt(res);
            console.log('No. NFT owned :', parseInt(res));
        }).catch((err) => {
            console.log(err, 'err');
        });
    },
    async getNFTOwned (){        
        await contract_collectibles.ownedNekos().call({
            from: this.account,
        }).then((res) => {
            this.NFT.owned = res.toString();
            //console.log('async Neko owned :', res);
        }).catch((err) => {
            console.log(err, 'err');
        });

    },
    async getNFTSupply (){        
        await contract_collectibles.totalSupply().call({
            from: this.account,
        }).then((res) => {
            this.NFT.supply = parseInt(res);
            //console.log('async Neko owned :', res);
        }).catch((err) => {
            console.log(err, 'err');
        });

    },
    checkNetworkID(){


        conflux.cfx.getId().then((res) => {
            console.log('network id: ', res);
            //console.log('async accountNekoBalance :', res);
        }).catch((err) => {
            console.log(err, 'err');
        });
    },
    async checkNekoBalance(){


        await contract_neko.balanceOf(this.account).then((res) => {
            this.accountNekoBalance = (res.toString()/1e18).toFixed(2);
            console.log('Balance $NEKO:', (res.toString()/1e18).toFixed(2));
        }).catch((err) => {
            console.log(err, 'err');
        });


        await conflux.getBalance(this.account).then((res) => {
            console.log('DIP :', res.toString());
            console.log('$CFX :', (res.toString()/1e18).toFixed(2));
            //.div(1e18).toFixed()

        }).catch((err) => {
            console.log(err, 'err');
        });
    },
    initCFXPaymentToken(){

            //CFX Coin
            this.payment.tokens.push({
                tokenAddress: '0x0000000000000000000000000000000000001002',
                tokenSymbol: 'CFX',
                tokenQuantity : 10**18,
                tokenQtyDisplay : parseFloat(1).toFixed(10),
                tokenStatus: true
            });
                    console.log ('CFX Token :', this.payment.tokens[0].tokenAddress, ', ', this.payment.tokens[0].tokenSymbol, ', ', this.payment.tokens[0].tokenQuantity, ', ',this.payment.tokens[0].tokenStatus)
    },

  }
}
</script>

<style scoped>
.el-header{
    justify-content: left;
}
.el-card {
    background:none;
    margin:auto 0;
    border: none;
}
.el-icon-picture{
    width:195px;
    height: 95px;
    background-image: url('../../src/assets/images/slot-in.png');
}
.el-icon-picture:before {
    content:none;
}
.gashapon{
    width:293px;
    height:462px;
    background-image: url('../../src/assets/images/gashapon.png');
    margin: 0 auto;
}
.el-button--gashapon {
    background-color: yellow;
    color:tomato;
    border: 1px solid tomato;
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
.dialog-button-wrap {
    width: 500px;
    margin:0 auto;
}
.btn_twister{

}
.dailog_wish {
}
.dialog-footer {
    text-align: right;
}
</style>