<template>
    
    <div class="mint_container">
        <!-- Form -->

        <h1> Total Neko Collectibles Token (NCT): {{NFT.supply}} </h1>
        <div> Collectible Pool balance : {{poolBalance}} </div>
        <div> Collector Wallet address : {{account}}</div>
        <div> Collector $NEKO : {{this.accountNekoBalance}}</div>
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
                <el-button type="primary" @click="quickMint()">Quick Mint</el-button>
                <el-button type="primary" @click="mintConfirm()">Confirm Mint</el-button>
                <el-button type="primary" @click="ConnectWallet()">Connect Wallet</el-button>
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
                <el-option v-for="(item,index) in payment.tokens" :key="index" :label="item.tokenSymbol+ '  '+ (item.tokenQuantity)" :value="index"></el-option>
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


// Contract : Collectibles 
import abi_collectible from '../web3/abi_collectible';

// Contract : Collectibles Paymnet
import abi_payment from '../web3/abi_payment';

// Contract : ERC20 - $NEKO
import abi_neko from '../web3/abi_neko';



const { Conflux,Drip } = require('js-conflux-sdk');

const conflux = new Conflux({
    url: "https://test.confluxrpc.com",
    networkId: 1,
});

/** 
// Firstly initialize the Conflux object without url
// Here Conflux indicate the SDK, Conflux.Conflux is the class used to talk with blockchain
let cfx = new Conflux.Conflux({
  networkId: 1,
});
// "conflux" indicate the portal's browser object
cfx.provider = conflux;

*/


console.log('conflux version :', conflux.version);


const Sohem = conflux.wallet.addPrivateKey('0x58FD2116532E2CF7A443261F6ECF250D11FAD4309A6BDE97A391B26BBE4B24D9', 1);

//const Sohem = conflux.wallet.addPrivateKey('0xAA0CACF21CFFB7CBD53CA4765294475C444F743F760C313F0742AC99FBF1E734', 1);

//const Sohem = conflux.wallet.addPrivateKey('0xED7D29E2C84D7588421F17A88FF2CB0D4CB86F8D2A03193B9E00CF9294A3EC27', 1);


console.log('sohem :', Sohem);

//connect Collectibles Contract 
const contract_collectibles =  conflux.Contract({abi:abi_collectible, address:'cfxtest:acbz06p71gzn1e00c3j06gwgnebvgdw1cj50pg1teh'});
const contract_collectibles_addr = 'cfxtest:acbz06p71gzn1e00c3j06gwgnebvgdw1cj50pg1teh';
//connect Exchange Contract
const contract_payment = conflux.Contract({abi:abi_payment, address:'cfxtest:ace4szmrknum7e9m0ykjy9ghhgpkygy48paem6zevb'});
//connect Neko Contract
const contract_neko = conflux.Contract({abi:abi_neko, address:'cfxtest:acfc7ss6kzp1cga5f342wa9r8m71bkxczpfrapgn9d'});




export default {
  name: 'App',
  data() {
    return {
        web3: null,
        account:null,
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
      
    if (typeof window.conflux !== "undefined") {
    
        //const cfx = window["conflux"]
        console.log('Conflux portal is installed!');

        //get current signed wallet address
        this.ConnectWallet();

    } else {
        //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');
    }
  },
  methods:{
    async quickMint(){

        //await contract.inc(10).sendTransaction({ from: account1 }).confirmed();
        console.log("Mint contract_payment :", contract_payment);

        // send transaction

        let contract_pay = conflux.Contract({abi:abi_payment, address:'cfxtest:ace4szmrknum7e9m0ykjy9ghhgpkygy48paem6zevb'});


    if (typeof window.conflux !== "undefined"){
            console.log('Conflux Portal is installed!');

        try {

            const currentWalletAddr = await window.conflux.enable();

            //const cfx = window["conflux"]
            //const accounts = await cfx.send("cfx_requestAccounts")
            //const account = accounts[0]
            console.log('Connect Accounts :', currentWalletAddr);
            console.log('Connect Sohem :', Sohem.address);


            const receipt =  contract_pay.paymentByCoin('9000001000112345678901',this.newNFT.guardian).sendTransaction({
            from: Sohem.address,
            gas: '0x493E0', //300,000
            storageLimit: '0x12C', //300
            gasPrice: '0xBB8', //3000
            value: Drip.fromCFX(1),
 
        }).confirmed()

        console.log("purchase receipt", receipt)


        // you might need wait seconds here...
        console.log('waiting...');
        await new Promise(resolve => setTimeout(resolve, 30 * 1000));


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

    mintConfirm() {

        let _this = this;
        const {Conflux, Drip} = require('js-conflux-sdk');
        const {abi, bytecode} = require('../web3/paymentGateway.json');

        let cfx = new Conflux({
            url: 'https://test.confluxrpc.com',
            networkId: 1,
        });
        cfx.provider = conflux;

        async function main() {

            const contract = cfx.Contract(
                {
                    abi: abi,
                    address: 'cfxtest:ace4szmrknum7e9m0ykjy9ghhgpkygy48paem6zevb',
                    bytecode: bytecode
                }
            );

            const [currentWalletAddr] = await window.conflux.enable();

            console.log('this account :', _this.account);
            console.log('payment contract :', contract)
            console.log('this guardian :',  _this.newNFT.guardian)           

            let transaction = await contract.paymentByCoin(
                '9000001000112345678901',
                _this.newNFT.guardian,
            ).sendTransaction({
                from: currentWalletAddr[0],
                gas: '0x493E0', //300,000
                gasPrice: '0xBB8', //3000
                storageLimit: '0x12C', //300
                value: Drip.fromCFX(1),
                // gas: 300000
            }).executed();
            _this.$message.success("Successfully buy NFT!");
            _this.$message.success(transaction);
        }
            main().catch(e => {
                console.error(e);
                this.$message.error("Fail to Mint NFT!");
            });
        },

    async ConnectWallet(){
        await window.conflux.enable();

        if (typeof window.conflux !== "undefined"){
            console.log('Conflux Portal is installed!');

        try {

            const cfx = window["conflux"]
            const accounts = await cfx.send("cfx_requestAccounts")
            this.account = accounts[0]

            console.log('Connect Accounts :', this.account);


            // load data
            this.getGuardian();

            this.getNeko();
            this.getGeneralDetails();
            this.getNFTOwned();
            this.getNFTSupply();

            //this.checkNetworkID();
            this.checkNekoBalance();
            this.getPaymentToken();

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
    //发送交易
    /** 
    async sendTransaction () {

        const currentWalletAddr = await window.conflux.enable();

        return new Promise((resolve, reject) => {
        conflux.sendAsync({
            method: 'cfx_sendTransaction',
            from: currentWalletAddr,
            gasPrice: '0x09184e72a000', // customizable by user during ConfluxPortal confirmation.
            gas: '0x2710',  // customizable by user during ConfluxPortal confirmation.
            value: 'DE0B6B3A7640000',
        }, (err, data) => {
            if (err) {
            reject(err)
            } else {
            resolve(data)
            }
        })
        })
    },
    */
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

        await contract_collectibles.Nekos(_id).call({
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
    async payByCoins (_machine,_refNekoId){

        console.log('enter payByCoins',_machine, ', ',_refNekoId, ', ', this.account);
        console.log('enter tokenQuantity',this.payment.tokens[0].tokenQuantity);
    

/** 
    const receipt = await contract.purchase(2).sendTransaction({ from: me.address, value: 2e18 }).executed()
    console.log("purchase receipt", receipt)
*/

        // send transaction
        const receipt = await contract_payment.paymentByCoin(_machine,_refNekoId).sendTransaction({
            from: this.account,
        }).confirmed()

         console.log("purchase receipt", receipt)


/** 
        // pay with Coin 
        await contract_payment.paymentByCoin(_machine,_refNekoId).call({
            from: this.account,
            value: 10^18,
        }).then((res) => {
            //            value: this.payment.tokens[0].tokenQuantity
            //this.generateNewNFT(res.events.PAYMENT.returnValues._nftID, res.transactionHash );
            //console.log('pay with coin',res);
            //console.log('New NFT ID : ',res.events.PAYMENT.returnValues._nftID);
            console.log('after payByCoin :', res)
            
        })
        */
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
    async getNeko(){

        await contract_neko.balanceOf(contract_collectibles_addr).then((res) => {
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
    async getPaymentToken(){
        await contract_payment.totalTokens().then((res) => {
            this.payment.count = parseInt(res);
            console.log('Acceptable CRC20 token :', this.payment.count);

            //CFX Coin
            this.payment.tokens.push({
                tokenAddress: '0x0000000000000000000000000000000000001002',
                tokenSymbol: 'CFX',
                //tokenQuantity: (1*10**17).toString(),
                tokenQuantity : 10**18,
                tokenQtyDisplay : parseFloat(1).toFixed(10),
                tokenStatus: true
            });
                
        for(let i=0; i< this.payment.count ;i++){ 
            
             contract_payment.ERC20Tokens(i).then((res) => {
                if(res[3]===true){
                    this.payment.tokens.push({
                        tokenAddress: res[0],
                        tokenSymbol: res[1],
                        tokenQuantity: res[2][0],
                        tokenQtyDisplay : Number(res[2][0]).toLocaleString(),
                        tokenStatus: res[3],
                    });
                    console.log ('ERC20 Token :', res[0], ', ', res[1], ', ', res[2][0], ', ',res[3])
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