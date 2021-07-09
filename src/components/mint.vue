<template>
    
    <div class="mint_container">
        <!-- Form -->

        <h1> Total Neko Collectibles Token (NCT): {{NFT.supply}} </h1>
        <div> Collectible Pool balance : {{poolBalance/10**18}} </div>
        <div> Collector Wallet address : {{account}}</div>
        <div> Collector $NEKO : {{this.accountNekoBalance/10**18}}</div>
        <div> Neko Collectibles Token (NCT) : {{NFT.balance}}</div>
        <div> LIST of NCT : {{NFT.owned}}</div>

        <div> we accept </div>


        <el-card>
            <div>Members:</div>
            <el-table :data="payment.tokens" stripe style="width: 100%">
                    <el-table-column prop="tokenSymbol" label="Token" > </el-table-column>
                    <el-table-column prop="tokenQtyDisplay" label="Price"> </el-table-column>  
                    <el-table-column prop="" label="Wallet Balance" > </el-table-column>   
            </el-table>

        </el-card>

        <el-dialog title="Make a wish!" :visible.sync="dialogFormVisible">

        <el-form :model="form" :rules="wishRules" ref="wishForm">

            <el-form-item label="Name" prop="name" :label-width="formLabelWidth">
                <el-input v-model="form.name" placeholder="Give your neko a name" ></el-input>
            </el-form-item>

            <el-form-item label="Message" prop="wish" :label-width="formLabelWidth">
                <el-input v-model="form.wish" placeholder="Write you wish here" ></el-input>
            </el-form-item>
            <el-form-item label="Guardian" prop="guardian" :label-width="formLabelWidth">
            <el-select v-model="form.guardian" placeholder="Select Guardian Neko">
                <el-option v-for="item in NFT.owned" :key="'neko-'+item" :label="'招き猫 #'+item" :value="item"></el-option>
            </el-select>
            </el-form-item>

            <el-form-item label="Payment" prop="payment" :label-width="formLabelWidth">
            <el-select v-model="form.payment" placeholder="Select token">
                <el-option v-for="(item,index) in payment.tokens" :key="index" :label="index +' : $' +item.tokenSymbol+ '  '+ (item.tokenQuantity)/10**18" :value="index"></el-option>
            </el-select>
            </el-form-item>

            <el-form-item class="dialog-footer">
                <el-button type="text" @click="generateNewNFT('2')">Painting</el-button>
                <el-button type="primary" @click="buyNFT('wishForm')">Confirm</el-button>
            </el-form-item>

        </el-form>
        </el-dialog>


        <el-dialog title="Step 1" :visible.sync="dialogApprove">
        <el-form :model="form">
            <span>Need 
                {{payment.tokenSymbol }} 
                {{payment.tokenQuantity/10**18}} to Mint a NFT</span>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button type="primary" @click="approvePayment(payment.tokens[form.payment].tokenQuantity)">Approve</el-button>
        </div>
        </el-dialog>


        <el-dialog title="Step 3" :visible.sync="dialogMint">
            <el-form :model="form">
                <span>Let's mint your NFT</span>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button type="primary" @click="payByERC20Token ( payment.tokens[form.payment].tokenAddress, form.guardian)">Confirm</el-button>
            </div>
        </el-dialog>


        <el-button>Buy</el-button>
        <el-button  type="primary" @click="dialogFormVisible = true">Make a wish!</el-button>
    </div>
</template>

<script>

import getWeb3 from '../web3/web3';

import abi_collectible from '../web3/abi_collectible';
const contract_collectible = '0x8e2F7e97f07bF6454a62FAECb4402A62B7C57e22';


// Contract : Collectibles Paymnet
import abi_payment from '../web3/abi_payment';
const contract_payment = '0xAFD04827187a6B1488F57cf10f1608ABd019C4Ea';


// Contract : ERC20 - $NEKO
import abi_neko from '../web3/abi_neko';
const contract_neko = '0xdF3CF86Faed8a1936F3dB48a374E981e3fFC3164';

export default {
  name: 'App',
  data() {
    return {
        web3: null,
        account: null,
        accountNekoBalance:0,
        poolBalance:0,
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
            tokens :[],
            count:0,
            tokenAddress:null,
            tokenSymbol:null,
            tokenQuantity:null
        },
        dialogTableVisible: false,
        dialogFormVisible: false,
        dialogApprove : false,
        dialogMint:false,
        form: {
            name: '',
            guardian: '',
            payment: '',
            wish: '',
        },
        newNFT : {
            id      : '',
            DNA     : '',
            gammaID : '',
            power   : '',
            refCount: '',
            wish    : ''
        },
        wishRules: {
                wish: [
                    { required: true, message: 'Please make a wish', trigger: 'blur' },
                    { min: 5, max: 60, message: 'Message within 5 to 60 charaters', trigger: 'blur' }
                ],
                guardian: [
                    { required: true, message: 'required a guardian', trigger: 'change' }
                ],
                payment: [
                    { required: true, message: 'select a payment token', trigger: 'change' }
                ]
        },      
        formLabelWidth: '120px'
    };
  },
  mounted() {
      if (typeof web3 !== 'undefined') {
  
        console.log('Metamask is installed!');

        getWeb3().then((res) => {
            this.web3 = res;

            //connect Contracts Collectibles, Payment and Neko
            this.contract.collectibles = new this.web3.eth.Contract(abi_collectible, contract_collectible);
            this.contract.payment = new this.web3.eth.Contract(abi_payment, contract_payment);
            this.contract.neko = new this.web3.eth.Contract(abi_neko, contract_neko);

            //get current signed wallet address
            this.web3.eth.getAccounts().then((accounts) => {
                [this.account] = accounts;


            // some function
            //this.buyDirect(this.account,'999000100010','12');
            this.getNeko();
            this.getGeneralDetails();
            this.getNFTOwned();
            this.getNFTSupply();

            this.checkNetworkID();
            this.checkNekoBalance();
            this.getPaymentToken();

            }).catch((err) => {
                console.log(err, 'err!!');
            });
        });
        } else {
            //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');

        }
  },
  methods:{
    async buyDirect(_buyer,_machine,_refNekoId){
        console.log('inner acc :', this.account);
        await this.contract.collectibles.methods.mintCollectible(_buyer,_machine,_refNekoId).send({
            from: this.account,
        });
                
    },
/** 
    painting (form) {
``
        this.$refs[form].validate( async (valid) => {
        if (valid) {

            
            console.log ('post :', this.form);
            const {data:res} = await this.$http.post('/painter/', this.form);
            
            if (res.Code==200){
                console.log('success');
            } else {
                console.log('failed!');
            }
            console.log('api data : ', res);

        } else {
            console.log('error submit!!');
            return false;
          }
        });
    },
*/

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
    buyNFT (wishForm){

        this.$refs[wishForm].validate((valid) => {
          if (valid) {

            // select payment token  
            let index = this.form.payment;
            let refID = this.form.guardian;

            if(index==0){
                // Pay by native coin
                this.payByCoins( this.generateMachineInput(), refID );
            } else {
                // Pay by Other ERC20 Token
                this.payment.tokenQuantity = this.payment.tokens[index].tokenQuantity;
                this.payment.tokenAddress  = this.payment.tokens[index].tokenAddress;
                this.payment.tokenSymbol   = this.payment.tokens[index].tokenSymbol;
                this.contract.ERC20 = new this.web3.eth.Contract(abi_neko, this.payment.tokenAddress);
                this.dialogFormVisible = false;
                this.dialogApprove = true;
            }
          } else {
            console.log('error submit!!');
            return false;
          }
        });


        
    },
    async generateNewNFT(_id){

        await this.contract.collectibles.methods.Nekos(_id).call({
        from: this.account
        }).then((res) => {
            this.newNFT.id      = _id;
            this.newNFT.DNA     = res.DNA;
            this.newNFT.gammaID = res.gammaNekoID;
            this.newNFT.power   = res.power; 
            this.newNFT.refCount= res.refCount;
            this.newNFT.wish    = this.form.wish;

            console.log('gen new NFT',this.newNFT);
            this.generateFile ();
            
        })
    },
    async generateFile (){
            // generate Metadata and GIF
            const {data:res2} = await this.$http.post('/painter/', this.newNFT);
            console.log('api data : ', res2);
    },
    payByCoins (_machine,_refNekoId){
        // pay with Coin 
        this.contract.payment.methods.paymentByCoin(_machine,_refNekoId).send({
        from: this.account,
        value: this.payment.tokens[0].tokenQuantity
        }).then((res) => {
            this.dialogFormVisible = false;
            this.generateNewNFT(res.events.PAYMENT.returnValues._nftID);
            console.log('pay with coin',res);
            console.log('New NFT ID : ',res.events.PAYMENT.returnValues._nftID);
            
        })
    },
    async approvePayment (_amount){
        // Approve : amount of ERC20 to transfer
        await this.contract.ERC20.methods.approve(contract_payment,_amount).send({
        from: this.account,
        }).then((res) => {
            console.log('approve ',res);
            
            this.dialogApprove = false;
            this.dialogMint = true;
            //this.allowancePayment ();
        })
    },
    async payByERC20Token ( _tokenAddress, _refNekoId){
        // pay with other ERC20 tokens
        let _machine = this.generateMachineInput();

        await this.contract.payment.methods.paymentByToken(_tokenAddress,_machine,_refNekoId).send({
        from: this.account,
        }).then((res) => {
            this.dialogMint = false;
            this.generateNewNFT(res.events.PAYMENT.returnValues._nftID);
            console.log('pay with ERC20 Token',res);
            console.log('New NFT ID : ',res.events.PAYMENT.returnValues._nftID);
        })
    },

// Retrieve General Information
    getNeko(){
        this.contract.neko.methods.balanceOf(contract_collectible).call().then((res) => {
            this.poolBalance = res;
            //console.log('async poolBalance :', res);
        }).catch((err) => {
            console.log(err, 'err');
        });
    },
    getGeneralDetails(){
        this.contract.collectibles.methods.balanceOf(this.account).call().then((res) => {
            this.NFT.balance = res;
            //console.log('async You $neko Bal :', res);
        }).catch((err) => {
            console.log(err, 'err');
        });
    },
    getNFTOwned (){        
        this.contract.collectibles.methods.ownedNekos().call({
            from: this.account,
        }).then((res) => {
            this.NFT.owned = res;
            //console.log('async Neko owned :', res);
        }).catch((err) => {
            console.log(err, 'err');
        });

    },
    getNFTSupply (){        
        this.contract.collectibles.methods.totalSupply().call({
            from: this.account,
        }).then((res) => {
            this.NFT.supply = res;
            //console.log('async Neko owned :', res);
        }).catch((err) => {
            console.log(err, 'err');
        });

    },
    checkNetworkID(){
        this.web3.eth.net.getId().then((res) => {
            console.log('network id: ', res);
            //console.log('async accountNekoBalance :', res);
        }).catch((err) => {
            console.log(err, 'err');
        });
    },
    checkNekoBalance(){
        this.contract.neko.methods.balanceOf(this.account).call().then((res) => {
            this.accountNekoBalance = res;
            //console.log('async accountNekoBalance :', res);
        }).catch((err) => {
            console.log(err, 'err');
        });
    },
    getPaymentToken(){
        this.contract.payment.methods.totalTokens().call().then((res) => {
            this.payment.count = res;
            console.log('async Token count :', this.payment.count);
            this.payment.tokens.push({
                tokenAddress: '0x0000000000000000000000000000000000001002',
                tokenSymbol: 'BNB',
                //tokenQuantity: (1*10**17).toString(),
                tokenQuantity : 100000000,
                tokenQtyDisplay : parseFloat(100000000/10**18).toFixed(10),
                tokenStatus: true
            });

            
                
        for(let i=0; i< this.payment.count ;i++){ 
            
            this.contract.payment.methods.ERC20Tokens(i).call().then((res) => {
                if(res[3]===true){
                    this.payment.tokens.push({
                        tokenAddress: res[0],
                        tokenSymbol: res[1],
                        tokenQuantity: res[2],
                        tokenQtyDisplay : Number(res[2]/10**18).toLocaleString(),
                        tokenStatus: res[3],
                    });
                }
            }).catch((err) => {
                console.log(err, 'err');
            });
        }
            console.log ('Payment Tokens', this.payment.tokens);
        }).catch((err) => {
            console.log(err, 'err');
        });

    },

  }
}
</script>

<style scoped>
.dialog-footer {
    text-align: right;
}
</style>