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
        
        <h1> Make your wish! </h1>

        <!-- Form - Part 1, Guadian  -->
        <el-form :model="form"  v-if="display.isGuardian" :rules="wishRules" ref="guardianForm">
            <el-form-item label="Guardian" prop="guardian" :label-width="formLabelWidth">

                <div> Pick a trustable Meow to be your wish </div>
                <div> 招き猫 {{ newNFT.guardian}} 
                <router-link :to="'/wallet'"> 
                    <el-button icon="el-icon-search" circle ></el-button> 
                </router-link>
                </div> 
            </el-form-item>

            <el-form-item class="dialog-footer">
                <el-button type="primary" @click="gotoWish()">Ok</el-button>
            </el-form-item>
        </el-form>


        <!-- Form - Part 2, Wish  -->

        <el-form :model="form" v-if="display.isWish" :rules="wishRules" ref="wishForm">

            <el-form-item label="Wish" prop="wish" :label-width="formLabelWidth">
                <div>Meow-meow would like to make you wish come true.</div>
                <el-input v-model="form.wish" placeholder="I'wish ... " ></el-input>
            </el-form-item>

            <el-form-item  class="dialog-footer">
                <el-button type="text" @click="gotoGuardian()">Back</el-button>
                <el-button type="primary" @click="gotoName('wishForm')">Ok</el-button>
            </el-form-item>
        </el-form>


        <!-- Form - Part 3, name  -->
        
        <el-form :model="form" v-if="display.isName" :rules="nameRules" ref="nameForm">
            <el-form-item label="Meow's Name" prop="name" :label-width="formLabelWidth">
                <div> Not all Meow would like to have name.</div>
                <el-input v-model="form.name" placeholder="But, I still want give a name" ></el-input>
            </el-form-item>

            <el-form-item class="dialog-footer">
                <el-button type="text" @click="backtoWish()">Back</el-button>
                <el-button type="primary" @click="gotoPayment('nameForm')">Ok</el-button>
            </el-form-item>
        </el-form>


        <!-- Form - Part 4, Payment  -->
        <el-form :model="form" v-if="display.isPayment" :rules="paymentRules" ref="paymentForm">
            <el-form-item  label="Payment" prop="payment" :label-width="formLabelWidth">


            <el-select v-model="form.payment" placeholder="Select token" clearable>
                <el-option v-for="(item,index) in payment.tokens" :key="index" :label="item.tokenSymbol+ '  '+ (item.tokenQuantity)/10**18" :value="index"></el-option>
            </el-select>



            </el-form-item>

            <el-form-item class="dialog-footer">
                <el-button type="text" @click="backtoName()">Back</el-button>
                <el-button type="primary" @click="buyNFT('paymentForm')">Make a wish</el-button>
            </el-form-item>

        </el-form>
        </el-card>


        <el-dialog :visible.sync="dialog.mint">
            <div> Sending you wish ... </div>
        </el-dialog>


        <el-dialog :visible.sync="dialog.gamma">
            <div> Required a guardian neko to send your wish</div>
                <router-link :to="'/wallet'"> 
                    <el-button icon="el-icon-search" > Select now!</el-button> 
                </router-link>
        </el-dialog>


        <el-dialog :visible.sync="dialog.success">
            <div> 招き猫 {{ newNFT.id }}</div>
            <div> Wish : {{ newNFT.wish }}</div>
            <div> Name : {{ newNFT.name }}</div>
            <div> Guardian {{ newNFT.guardian }}</div>
            <div> Gamma : {{ newNFT.gammaID }}</div>
            <div> Power {{ newNFT.power }}</div>
            <router-link :to="'/token/' + newNFT.id+ '/0x0'"> 
                <el-button size="mini" type="primary" > Peep now!</el-button>
            </router-link>
            
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


        
    </div>
</template>

<script>

import getWeb3 from '../web3/web3';

import abi_collectible from '../web3/abi_collectible';
const contract_collectible = '0xDA01f83Fc3483Df018034af5fe8aDa75373162aF';


// Contract : Collectibles Paymnet
import abi_payment from '../web3/abi_payment';
const contract_payment = '0xB98ACE202eEf57896263CfC89257A78a9C6B29cF';


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
        selected : 0,
        display :{
            isGuardian : true,
            isName : false,
            isWish : false,
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
            wish: '',
            name: '',
            guardian : '',
            DNA: '',
            gammaID: '',
            power: '',
            refCount: '',
        },
        wishRules: {
                wish: [
                    { required: true, message: 'Please make a wish', trigger: 'blur' },
                    { min: 5, max: 60, message: 'Message within 5 to 60 charaters', trigger: 'blur' }
                ],
        },    
        nameRules: {
                name: [
                    { required: true, message: 'Give a name', trigger: 'blur' },
                    { min: 1, max: 20, message: 'Message within 1 to 20 charaters', trigger: 'blur' }
                ],
        },  
        paymentRules : {
                payment: [
                    { required: true, message: 'select a payment token', trigger: 'change' }
                ]
        },          
        formLabelWidth: '120px'
    };
  },
  created() {
    this.payment.key = "1244";
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

            this.getGuardian();

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
    gotoGuardian () {
            this.display.isGuardian = true;
            this.display.isWish = false;
    },
    gotoWish () {
        if (this.form.guardian!='guardian'){ 
            this.display.isGuardian = false;
            this.display.isWish = true;
        } else {
            this.dialog.gamma = true;
        }
    },
    backtoWish () {
        this.display.isName = false;
        this.display.isWish = true;
    },
    gotoName (form) {
        this.$refs[form].validate((valid) => {
            if (valid) {
                this.display.isWish = false;
                this.display.isName = true;
            }
        })
    },
    backtoName () {
        this.display.isPayment = false;
        this.display.isName = true;
    },
    gotoPayment (form) {
        this.$refs[form].validate((valid) => {
            if (valid) {
                this.display.isName = false;
                this.display.isPayment = true;
            }
        })
    },
    getGuardian (){
        this.newNFT.guardian = this.$route.params.guardian;
        this.form.guardian = this.newNFT.guardian;

        console.log('Guardian id : ', this.newNFT.guardian);
    },
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
    buyNFT (form){
        if (this.form.guardian!='guardian'){

            this.$refs[form].validate((valid) => {
            if (valid) {
                
                // select payment token  
                let index = this.form.payment;
                let refID = this.form.guardian;

                if(index==0){
                    // display minting tab
                    this.dialog.mint = true;

                    // Pay by native coin
                    this.payByCoins( this.generateMachineInput(), refID );
                } else {
                    // Pay by Other ERC20 Token
                    this.payment.tokenQuantity = this.payment.tokens[index].tokenQuantity;
                    this.payment.tokenAddress  = this.payment.tokens[index].tokenAddress;
                    this.payment.tokenSymbol   = this.payment.tokens[index].tokenSymbol;
                    this.contract.ERC20 = new this.web3.eth.Contract(abi_neko, this.payment.tokenAddress);
                    this.dialogApprove = true;
                }
            } else {
                console.log('error submit!!');
                return false;
            }
            });

        } else {
            this.dialog.gamma = true;
        }


        
    },
    async generateNewNFT(_id, _transactionHash){

        await this.contract.collectibles.methods.Nekos(_id).call({
        from: this.account
        }).then((res) => {
            this.newNFT.transactionHash = _transactionHash;
            this.newNFT.id      = _id;
            this.newNFT.wish    = this.form.wish;
            this.newNFT.name    = this.form.name;
            this.newNFT.guardian= this.form.guardian;
            this.newNFT.DNA     = res.DNA;
            this.newNFT.gammaID = res.gammaNekoID;
            this.newNFT.power   = res.power; 
            this.newNFT.refCount= res.refCount;

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
    payByCoins (_machine,_refNekoId){
        // pay with Coin 
        this.contract.payment.methods.paymentByCoin(_machine,_refNekoId).send({
        from: this.account,
        value: this.payment.tokens[0].tokenQuantity
        }).then((res) => {
            this.generateNewNFT(res.events.PAYMENT.returnValues._nftID, res.transactionHash );
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

            this.generateNewNFT(res.events.PAYMENT.returnValues._nftID, res.transactionHash );
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
            this.payment.key = this.payment.tokens[0];
            
            console.log ('Payment Key ', this.payment.key);
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