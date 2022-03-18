<template>
    
    <div class="mint_container">

         <!-- Wallet connectd -->
        <div v-if="display.isConnect" class="wallet-connection"  >
            <div class="token-wrap">
                <div class="cfx-balance">{{this.accountCFXBalance}} CFX</div>
                <div class="neko-balance">{{this.accountNekoBalance}} NEKO</div>
                <div class="meow-balance">{{this.accountMeowBalance}}</div>
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

        <img src="../../src/assets/images/logo-maneki_logo.png" />


        <!-- Gashapon -->

        <el-card>
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
                <img class="btn_twister" src="../../src/assets/images/twister.png" v-on:click="gotoWish" />
            </el-form-item>
      

        </el-form>

        <!--
        <span style="color:white;">
        <h1> Make a wish! </h1>

        <h1> Total Neko Collectibles Token (NCT): {{NFT.supply}} </h1>
        <div> Collectible Pool balance : {{poolBalance}} </div>
        <div> Collector Wallet address : {{account}}</div>
        <div> Collector $NEKO : {{this.accountNekoBalance}}</div>
        <div> Neko Collectibles Token (NCT) : {{NFT.balance}}</div>
        <div> LIST of NCT : {{NFT.owned}}</div>

        </span>
        -->

        </el-card>


        <el-dialog :visible.sync="dialog.mint">

            <div class="dialog-input-wrap">
                <img src="../../src/assets/images/sending.gif" style="width:500px; height:350px; margin:0 auto;"/>
                <div class ="dialog-footer-msg"> Minting ... </div>
            </div>
        </el-dialog>


        <el-dialog :visible.sync="dialog.wish" class="dailog_wish">
            <!-- Form - Part 2, Wish  -->
            <el-form :model="form" :rules="wishRules" ref="wishForm">
                <div class="dialog-input-wrap">
                    
                <div class="dialog-content">
                    <div style="color:white; font-size:1.8em; padding-top:100px; padding-left:80px;padding-bottom:20px; "> 
                        <span style="color:yellow; font-size:0.7em;">選択した言語で願い事をする</span> <br> Make a wish in your language <br>of choice </div>
                <el-form-item prop="wish" style="width:350px; margin: 0 auto; height:200px">
                    <el-input type="textarea" :rows="3" v-model="form.wish" placeholder="I'wish ... " ></el-input>
                </el-form-item>
                </div>

                <el-form-item  class="dialog-footer">
                    <el-button type="gashapon" @click="gotoName('wishForm')">    Ok    </el-button>
                </el-form-item>
                </div>
            </el-form>
        </el-dialog>



        <!-- Form - Part 3, name  -->
        
        <el-dialog :visible.sync="dialog.name" class="dailog_name">
            <el-form :model="form"  :rules="nameRules" ref="nameForm">
                <div class="dialog-input-wrap">

                <div class="dialog-content">
                <div style="color:white; font-size:1.8em; padding-top:100px; padding-left:80px;padding-bottom:20px; ">
                    <span style="color:yellow; font-size:0.7em;">ニャー ...</span><br>
                    Meooow ....</div>
                <el-form-item  prop="name"  style="width:350px; margin: 0 auto; height:200px">
                    <el-input v-model="form.name" placeholder="what's your meow name?" ></el-input>
                </el-form-item>
                </div>

                <el-form-item class="dialog-footer" >
                    <el-button type="gashapon" @click="gotoPayment('nameForm')">    Ok    </el-button>
                </el-form-item>
                </div>

            </el-form>
        </el-dialog>

        <el-dialog :visible.sync="dialog.gamma">
            <div class="dialog-input-wrap">
                <div class="dialog-content">
                <div style="color:white; font-size:1.8em; text-align:center; padding-top:100px; padding-bottom:20px; ">
                    <span style="color:yellow; font-size:0.7em;">願い事を送るための守護者としてニャーが必要</span><br>
                    Required a Meow as <br>the guardian for wish sending</div>
                </div>
                <div style="margin:0 auto; height:30px; width:330px;">
                <router-link :to="'/wallet'"> 
                    <el-button  type="text" style=" width:100px; border:1px solid red;"> I Have! </el-button> 
                </router-link>
                <router-link :to="'/marketplace'"> 
                    <el-button icon="el-icon-cart"  style="margin-left:30px; width:200px; "> Buy from marketplace</el-button> 
                </router-link>
                </div>
            </div>
        </el-dialog>


        <!-- Form - Part 4, Payment  -->
        <el-dialog :visible.sync="dialog.payment" class="dailog_payment">
            <el-form :model="form"  :rules="paymentRules" ref="paymentForm">
                    
                <div class="dialog-input-wrap">

                    <div class="dialog-content">
                    <div style="color:white; font-size:1.8em; text-align:center; padding-top:100px; padding-bottom:20px; ">
                        <span style="color:yellow; font-size:0.7em;">であなたの願いをミント</span><br>
                        Mint your wish with</div>
                        

                    <el-form-item   prop="payment"   style="width:350px; margin: 0 auto;" >
                        <el-select v-model="form.payment" placeholder="Select token" style="width:350px;" >
                            <el-option v-for="(item,index) in payment.tokens" :key="index" :label="item.tokenSymbol+ '  '+ (item.tokenQuantity)" :value="index"></el-option>
                        </el-select>
                    </el-form-item>
                    </div>


                    <el-form-item class="dialog-footer">
                        <el-button type="gashapon" @click="buyNFT('paymentForm')"> Ok </el-button>
                    </el-form-item>
                </div>


            </el-form>
        </el-dialog>


        <!-- Form - Part 4, painting  -->
        <el-dialog :visible.sync="dialog.painting">
            
            <div class="dialog-input-wrap" style="color:white;">
                <img src="../../src/assets/images/painting.gif" style="width:500px; height:350px; margin:0 auto;"/>

                <div class ="dialog-footer-msg">
                    Painting NFT : 
                    <br>
                    {{ newNFT.name }} (招き猫#{{ newNFT.id }}) : {{ newNFT.wish }} Maneki Power {{ newNFT.power.toLocaleString() }}
                </div>
            </div>


        </el-dialog>

        <!-- Form - Part 5, painting  -->
        <el-dialog :visible.sync="dialog.success">
            
            <div class="dialog-input-wrap" style="color:white; text-align:center; padding-bottom:50px;">

                <div class="dialog-content">
                <div style="margin: 0 auto; color:white; font-size:1.8em; text-align:center; padding-top:100px; padding-bottom:20px; width:350px; ">
                    
                    <span style="color:yellow; font-size:0.7em;">Maneki-Meowは、ユニークなNFTアート作品を通じてあなたの祝福と願いを届けます</span><br>
                    Maneki-Meow delivers your <br> blessing and wishes <br> via unique NFT art pieces</div>
                </div>

                <router-link :to="'/token/' + newNFT.id+ '/0x0'"> 
                    <el-button type="gashapon"  > Peep now!</el-button>
                </router-link>
            </div>


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
                <el-button type="primary" @click="payByERC20Token (form.guardian)">Confirm</el-button>
            </div>
        </el-dialog>



        <!-- Download wallet -->
        <el-dialog  :visible.sync="downloadWallet">

            <div class="dialog-input-wrap">


                <div class="dialog-content" style="height:250px;">
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


// Contract : Collectibles 
import abi_collectible from '../web3/abi_collectible';

// Contract : Collectibles Paymnet
import abi_payment from '../web3/abi_payment';

// Contract : ERC20 - $NEKO
import abi_neko from '../web3/abi_neko';



const { Conflux,Drip} = require('js-conflux-sdk');
const conflux = new Conflux({
    url: "https://test.confluxrpc.com",
    networkId: 1,
});



import ConfluxPortalOnboarding from 'conflux-portal-onboarding'


console.log('conflux version :', conflux.version);


conflux.provider = window.conflux

//connect Collectibles Contract 
const contract_collectibles =  conflux.Contract({abi:abi_collectible, address:'cfxtest:acgrtg0ncb3jmj8621f8fa78bhbxrg38kazk4n25uw'});

//connect payment Contract
const contract_payment = conflux.Contract({abi:abi_payment, address:'cfxtest:acg3y22s1msdvjgcaneruu9mja8yd12uyedj9t3r07'});
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
        accountCFXBalance:0,
        accountMeowBalance:0,
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
            painting : false,
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
                    { min: 0, max: 110, message: 'Message within 5 to 110 charaters', trigger: 'blur' }
                ],
        },    
        nameRules: {
                name: [
                    { required: true, message: 'Give a name', trigger: 'blur' },
                    { min: 0, max: 20, message: 'Message within 3 to 20 charaters', trigger: 'blur' }
                ],
        },  
        paymentRules : {
                payment: [
                    { required: true, message: 'select a payment token', trigger: 'change' }
                ]
        },          
        formLabelWidth: '120',
        paymentSelected : {},
        downloadWallet: false,
    };
  },
  created() {
        this.form.payment = 0;
  },
  mounted() {
      
    if (typeof window.conflux !== "undefined") {
        console.log('Conflux portal is installed!');
        this.updateData();

    } else {
        //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');
        // pop download
        this.downloadWallet = true;
    }
  },
  methods:{

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


            // Total NFT Owned 
            await contract_collectibles.balanceOf(this.account).call({
                from: this.account,
            }).then((res) => {
                this.accountMeowBalance = parseInt(res);
            }).catch((err) => {
                console.log(err, 'err');
            });

    },

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
    async quickMint(){
        console.log("Mint contract_payment :", contract_payment);


    if (typeof window.conflux !== "undefined"){
            console.log('Conflux Portal is installed!');

        try {
            await window.conflux.enable().then((currentWalletAddr) => {

            console.log('Connect Accounts :', currentWalletAddr);

            //this.$message.success('Start Minting');

            conflux.getGasPrice().then((receipt) => {
                let gasPrice = receipt[0];
                console.log ('raw.getGasPrice()', gasPrice);
            })
            conflux.getNextNonce(currentWalletAddr).then((receipt) => {
                let nextNonce = receipt[0];
                console.log ('raw.getNextNonce()', nextNonce);
            })
            conflux.getEpochNumber().then((receipt) => {
                let EpochNumber = receipt;
                console.log ('raw.getEpochNumber()', EpochNumber);
            })


            contract_payment.paymentByCoin('9000001000112345678901',this.newNFT.guardian).sendTransaction({
                from: currentWalletAddr,
                gas: '0x30D40', //200,000
                storageLimit: '0x1ED', //493
                gasPrice: '0xBB8', //3000
                value: Drip.fromCFX(0.00001),
                //nonce:  currentNonce,
                //gasPrice:  await conflux.getGasPrice(),
                //epochHeight:  conflux.getEpochNumber()
            }).executed().then((receipt) => {
                
                console.log("event NFT ID:", parseInt(receipt.logs[0].topics[3], 16))

                console.log("purchase gas fee", receipt.gasFee)

                //this.$message.success("Successfully Minted NFT!");
                this.updateData();

            }).catch((err) => {
                console.log(err, 'err!!');
                //this.$message.error("Minting Failed");
            });



            });


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

    async updateData(){
        await window.conflux.request({method: "cfx_requestAccounts"});

        if (typeof window.conflux !== "undefined"){
            console.log('Conflux Portal is installed!');

        try {

            const cfx = window["conflux"]
            const accounts = await cfx.request({method: "cfx_requestAccounts"})
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
            this.initPaymentMethod();

            this.checkWalletBalance();

            //this.getPaymentToken();
            //this.initCFXPaymentToken();

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
    gotoWish () {
        if (this.form.guardian!='guardian'){ 
            this.display.isGuardian = true;
            this.dialog.wish = true;
        } else {
            this.dialog.gamma = true;
        }
    },
    gotoName (form) {
        this.$refs[form].validate((valid) => {
            if (valid) {
                this.dialog.wish = false;
                this.dialog.name = true;
            }
        })
    },
    gotoPayment (form) {
        this.$refs[form].validate((valid) => {
            if (valid) {
                this.dialog.name = false;
                this.dialog.payment = true;
            }
        })
    },
    getGuardian (){
        this.newNFT.guardian = this.$route.params.guardian;
        this.newNFT.guardianIMG = 'https://nft.neko.exchange/gif/' + this.$route.params.guardian + '.gif'
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
                    this.dialog.payment = false;
                    
                    console.log('Enter BuyNFT :')
                    // Pay by native coin
                    this.payByCoins( this.generateMachineInput(), refID );

                    console.log('exit BuyNFT :')
                } else {
                    // store the token selection into payment
                    this.approvePayment();
                    //this.dialogApprove = true;
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


        //display painting tab
        this.dialog.mint = false;
        this.dialog.painting = true;

        await contract_collectibles.Nekos(_id).call({
        from: this.account
        }).then((res) => {

            /**
             * power       : _power,
             * DNA         : _DNA,
             * refCount    : _refCount,
             * gammaNekoID : _gammaNekoID
             */

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

            
        })
    },
    async generateFile (){
            // generate Metadata and GIF
            //const {data:res2} = 
            await this.$http.post('/painter/', this.newNFT).then((res) => {
                console.log('api data : ', res);

            //display success tab
            this.dialog.painting = false;
            this.dialog.success = true;
                
            });

    },
    async payByCoins (_machine, _refNekoId){


        // display minting tab
        this.dialog.mint = true;

        console.log('enter payByCoins',_machine, ', ',_refNekoId, ', ', this.account);
        console.log('Price :',this.payment.tokens[0].tokenSymbol , this.payment.tokens[0].tokenQuantity);
    

        await window.conflux.request({method: "cfx_requestAccounts"}).then((currentWalletAddr) => {

        console.log('Connect Accounts :', currentWalletAddr);
        //this.$message.success('Start Minting');

/*** 
        // prepare the tx meta info
        const currentEpoch = await conflux.cfx.getEpochNumber();
        const nonce = await conflux.cfx.getNextNonce(account.address);
        const value = Drip.fromCFX(1.1);
        const chainId = 1;
        const gasPrice = '0x1388';
        let txInfo = {
            from: account.address,
            to: targetAddress,
            value,
            nonce,
            gasPrice,
            chainId,
            epochHeight: currentEpoch,
        };
        // estimate gas and storageCollateralized
        let estimate = await conflux.cfx.estimateGasAndCollateral(txInfo);
        txInfo.gas = estimate.gasLimit;
        txInfo.storageLimit = estimate.storageCollateralized;
        const hash = await conflux.cfx.sendTransaction(txInfo);
        return hash;
*/
            


            //const status =  conflux.cfx.getStatus();

            // send transaction
            contract_payment.paymentByCoin(_machine,_refNekoId).sendTransaction({
                from: currentWalletAddr,
                gas: '0xF4240', //1,000,000
                storageLimit: '0x7D0', //2000
                gasPrice: '0x64', //100
                value: this.payment.tokens[0].tokenQuantity*10**18,
            }).executed().then((receipt) => {
                
                
            
                // EVENT PAYMENT,  payee > address,  _nftid > uint256,  token > address, amount >uint256
          
                let _txHash = receipt.transactionHash;
                let _newNFTid = parseInt(receipt.logs[0].topics[3], 16);


                console.log("transactionHash:",_txHash )
                console.log('New NFT ID : ',_newNFTid);


                this.dialogMint = false;
                this.generateNewNFT(_newNFTid, _txHash );


                console.log("purchase receipt", receipt)
                //this.$message.success("Successfully Minted NFT!");
                this.updateData();


            }).catch((err) => {
                console.log(err, 'err!!');
                //this.$message.error("Minting Failed");
                // display minting tab
                this.dialog.mint = false;
            });
        })


    },
    async approvePayment (){
        // Approve : amount of ERC20 to transfer

        //console.log('Price :',this.payment.tokens[_paymentTokenIndex].tokenSymbol , this.payment.tokens[_paymentTokenIndex].tokenQuantity*10**18);
        // ok 21, 22, __ , 24, 25

        console.log('sign by wallet : ', this.account);

        await contract_neko.approve(contract_payment.address , '100000000000000000000000').sendTransaction({
        from: this.account,
        }).then((res) => {

            console.log('approve ',res);
            
            //this.dialogApprove = false;
            this.dialogMint = true;
            //this.allowancePayment ();
        })
    },
    async payByERC20Token (_refNekoId){
        // pay with other ERC20 tokens
        let _machine = this.generateMachineInput();
        
        console.log("enter payByToken: machinecode : ",  _machine);

        await window.conflux.enable().then((currentWalletAddr) => {

            console.log('Connect Accounts :', currentWalletAddr);

            //contract_neko
            contract_payment.paymentByToken(contract_neko.address, _machine, _refNekoId).sendTransaction({
                from: currentWalletAddr,
                gas: '0xF4240', //1,000,000
                storageLimit: '0x7D0', //2000
                gasPrice: '0x64', //100
            }).executed().then((receipt) => {
                console.log (receipt);

                this.dialogMint = false;

                let _txHash = receipt.transactionHash;
                let _newNFTid = parseInt(receipt.logs[2].topics[3], 16);
                this.generateNewNFT(_newNFTid, _txHash );

                console.log("transactionHash:",_txHash )
                console.log('New NFT ID : ',_newNFTid);

            }).catch((err) => {
                console.log(err, 'err!!');
                ///this.$message.error("Minting Failed");
                // display minting tab
                this.dialog.mint = false;
            });
        });
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
            this.accountNekoBalance = parseInt(res.toString()/1e18);
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
    initPaymentMethod(){
            //CFX Coin
            this.payment.tokens.push({
                tokenAddress: '0x0000000000000000000000000000000000001002',
                tokenSymbol: 'CFX',
                tokenQuantity : 0.00001,
                tokenQtyDisplay : 0.00001,
                tokenStatus: true
            });

            // Neko Token
            this.payment.tokens.push({
                tokenAddress: contract_payment.address,
                tokenSymbol: 'NEKO',
                tokenQuantity: 10**6,
                tokenQtyDisplay : 10**6,
                tokenStatus: true,
            });


            //this.form.payment = this.payment.tokens[0];
    },
    initCFXPaymentToken(){

            //CFX Coin
            this.payment.tokens.push({
                tokenAddress: '0x0000000000000000000000000000000000001002',
                tokenSymbol: 'CFX',
                tokenQuantity : 0.00001,
                tokenQtyDisplay : 0.00001,
                tokenStatus: true
            });
                    console.log ('CFX Token :', this.payment.tokens[0].tokenAddress, ', ', this.payment.tokens[0].tokenSymbol, ', ', this.payment.tokens[0].tokenQuantity, ', ',this.payment.tokens[0].tokenStatus)
    },
    async getPaymentToken(){

        await contract_payment.totalTokens().then((res) => {
            this.payment.count = parseInt(res);
            console.log('Acceptable CRC20 token :', this.payment.count);


        //set in other tokens 
        for(let i=0; i< this.payment.count ;i++){ 
            
             contract_payment.ERC20Tokens(i).then((res) => {
                if(res[3]===true){
                    this.payment.tokens.push({
                        tokenAddress: res[0],
                        tokenSymbol: res[1],
                        tokenQuantity: 10**6,
                        tokenQtyDisplay : 10**6,
                        tokenStatus: res[3],
                    });
                    console.log ('ERC20 Token :', res[0], ', ', res[1], ', ', 10**6, ', ',res[3])
                    console.log ('all payment tokens :', this.payment.tokens)
                }
            }).catch((err) => {
                console.log(err, 'err');
            });
        }

        //set in CFX
        this.initCFXPaymentToken();


        }).catch((err) => {
            console.log(err, 'err');
        });
            // this had pick the right token , CFX
            this.form.payment = this.payment.tokens[0]

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
    height:300px;
}
.dialog-footer-msg{
    color:white;
}
.btn_twister{

}
.dailog_payment {
    text-align: center;
}
.dialog-footer {
    text-align: center;
}
.dialog-footer a{
    text-decoration: none;
    color:#ff3600;;
    padding:5px;
}
.el-dialog {
    padding-top:60px;
    padding-bottom:40px;
    text-align: center;
}
.wallet-connection{
    width: 460px;
    text-align: right;
    color: #c6c6c6;
    float:right;
    margin-right:30px;
}
.token-wrap{
    width: 460px;
    height: 30px;
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