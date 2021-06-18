<template>
    <div class="mint_container">
        <!-- Form -->

        <h1> Total Neko Collectibles Token (NCT): {{NFTSupply}} </h1>
        <div> Collectible Pool balance : {{poolBalance}} </div>
        <div> Collector Wallet address : {{account}}</div>
        <div> Collector (NCT) : {{NFTBalance}}</div>
        <div> LIST of NCT : {{NFTOwned}}</div>

        <ul>
            <li v-for="(item,index) in paymentTokens" v-bind:key="index">
                {{index}} -  {{item.tokenSymbol}} {{(item.tokenQuantity)/10**18}} {{item.tokenAddress}}
            </li>
        </ul>


        <el-button type="text" @click="dialogFormVisible = true">Make a wish!</el-button>

        <el-dialog title="Make a wish!" :visible.sync="dialogFormVisible">

        <el-form :model="form">
            <el-form-item label="Guardian" :label-width="formLabelWidth">
            <el-select v-model="form.guardian" placeholder="Select Guardian Neko">
                <el-option v-for="item in NFTOwned" :key="'neko-'+item" :label="'招き猫 #'+item" :value="item"></el-option>
            </el-select>
            </el-form-item>

            <el-form-item label="Payment" :label-width="formLabelWidth">
            <el-select v-model="form.payment" placeholder="Select token">
                <el-option v-for="(item,index) in paymentTokens" :key="index" :label="'$' +item.tokenSymbol+ '  '+ (item.tokenQuantity)/10**18" :value="index+'-'+item.tokenQuantity"></el-option>
            </el-select>
            </el-form-item>

        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="dialogFormVisible = false">Cancel</el-button>
            <el-button type="primary" @click="buyNFT()">Confirm</el-button>
        </div>
        </el-dialog>

        <el-button type="primary" @click="endableBNB()"> Enable BNB</el-button>
    </div>
</template>

<script>

const Web3 = require('web3');
const web3 = new Web3('https://data-seed-prebsc-1-s1.binance.org:8545');

// Contract : Neko Collectibles - NCX
import NekoCollectibleABI from '../web3/abi_nekocollectible';
const contractAddressCollectibles = '0x9CCD560e93C2be416edE43C4E97941b7b443b9CE';
const contractCollectibles = new web3.eth.Contract(NekoCollectibleABI,contractAddressCollectibles);


// Contract : Collectibles Paymnet
import CollectiblePaymentABI from '../web3/abi_collectiblesPayment';
const PaymentGateway = '0x5c573535C3E0e9deB802D884F4D4CA766A85b355';
const contractPayment = new web3.eth.Contract(CollectiblePaymentABI,PaymentGateway);

// Contract : ERC20 - $NEKO
import NekoABI from '../web3/abi_neko';
const contractAddressNeko = '0xdF3CF86Faed8a1936F3dB48a374E981e3fFC3164';
const contractNeko = new web3.eth.Contract(NekoABI,contractAddressNeko);

  export default {
    data() {
      return {
        account :null,
        poolBalance:0,
        NFTOwned:0,
        NFTSupply:0,
        NFTBalance :0,
        paymentTokenCount:0,
        paymentTokens:[],
        dialogTableVisible: false,
        dialogFormVisible: false,
        form: {
          guardian: '',
          payment: '',
        },
        formLabelWidth: '120px'
      };
    },
    mounted() {
        // check metamask and request for installation
        if(typeof window.ethereum !== 'undefined'){
            console.log('Metamask is installed!');
            this.connectBlockchain();
        } else {
            console.log('Please Install Metamask');
        }
    },
    methods : {
    async endableBNB(){

        if (window.ethereum) {
        try {
            const accounts = await web3.ethereum.request({ method: 'eth_requestAccounts' });


            this.payByCoin('999000100010','12');
        
            console.log('new account :', accounts);
        } catch (error) {
            if (error.code === 4001) {
            // User rejected request
            }
        }
        }

        //web3.ethereum.request({ method: 'eth_requestAccounts' });
        //alert('yam : ', window.ethereum.isConnected());
    },
    async connectBlockchain (){
        //init the blockchain
        await window.ethereum.request({method: 'eth_requestAccounts'}).then((res) => {
            const accounts = res;
            this.account = Web3.utils.toChecksumAddress(accounts[0]);
            this.getNeko();
            this.getGeneralDetails();
            this.getNFTOwned();
            this.getNFTSupply();
        });
    },
    buyNFT (){
            this.payByCoin('999000100010','12');
    },
    payByCoin(_machine,_refNekoId){
        console.log('payment PaymentGateway: ' ,PaymentGateway)
        console.log('pay machine',_machine);
        console.log('pay ref id',_refNekoId);
        console.log('Wallet Acc',this.account);

        contractPayment.methods.paymentByCoin(_machine,_refNekoId).send({
            from: this.account,
            value: 100000000000000000
            }).then((res) => {
                console.log('Pay by coin :', res);
            })
    },
    getNeko(){
        contractNeko.methods.balanceOf(contractAddressCollectibles).call().then((res) => {
            this.poolBalance = res;
            console.log('async poolBalance :', res);
        }).catch((err) => {
            console.log(err, 'err');
        });
    },
    getGeneralDetails(){
        contractCollectibles.methods.balanceOf(this.account).call().then((res) => {
            this.NFTBalance = res;
            console.log('async You $neko Bal :', res);
        }).catch((err) => {
            console.log(err, 'err');
        });
    },
    getNFTOwned (){        
        contractCollectibles.methods.ownedNekos().call({
            from: this.account,
        }).then((res) => {
            this.NFTOwned = res;
            console.log('async Neko owned :', res);
        }).catch((err) => {
            console.log(err, 'err');
        });

    },
    getNFTSupply (){        
        contractCollectibles.methods.totalSupply().call({
            from: this.account,
        }).then((res) => {
            this.NFTSupply = res;
            console.log('async Neko owned :', res);
        }).catch((err) => {
            console.log(err, 'err');
        });

    },
    getPaymentToken(){
        
            contractPayment.methods.totalTokens().call().then((res) => {
                this.paymentTokenCount = res;
                console.log('async Token count :', this.paymentTokenCount);


                this.paymentTokens.push({
                            tokenAddress: '0x0000000000000000000000000000000000001002',
                            tokenSymbol: 'BNB',
                            tokenQuantity: (1*10**17).toString(),
                            tokenStatus: true
                        });
                        
                        
                for(let i=0; i< this.paymentTokenCount ;i++){ 

                    contractPayment.methods.ERC20Tokens(i).call().then((res) => {
                        this.paymentTokens.push({
                            tokenAddress: res[0],
                            tokenSymbol: res[1],
                            tokenQuantity: res[2],
                            tokenStatus: res[3],
                        });
                    }).catch((err) => {
                        console.log(err, 'err');
                    });
                }
                console.log ('Payment Tokens', this.paymentTokens);

            }).catch((err) => {
                console.log(err, 'err');
            });

    },
    payByToken(){
  
    },


    }


  };
</script>