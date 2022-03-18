 <!-- Public Access Page : Guardian ID  -->

<template>
    <div class="main-container">

        <!-- Wallet connection -->
        <div class="wallet-connection"  >
            <div class="token-wrap">
                <div class="cfx-balance">{{this.accountCFXBalance}} CFX</div>
                <div class="neko-balance">{{this.accountNekoBalance}} NEKO</div>
                <div class="meow-balance">{{this.accountMeowBalance}}</div>
            </div>
            <el-button @click="updateButton()" v-if="!display.isConnect"> connect wallet </el-button>
            <div  v-if="display.isConnect" style="padding-top:10px;"> Conflux Network : {{shortenAccount}} </div>
        </div>

        <img src="../../src/assets/images/logo-maneki_logo.png" />

        <div class="sub-container-gaurdian-details">
        <el-card  style="width: 440px">
            <el-image :src="NFT.image" class="nft-image">
                <div slot="placeholder" class="image-slot">
                    loading<span class="dot">...</span>
                </div>
                <div slot="error" class="image-slot" @click="imageLoadError()">
                    <i class="el-icon-picture-outline"></i>
                </div>
            </el-image>
        </el-card> 



        <!-- General details  -->
        <el-card  style="width: 500px; height:65px; background:none; margin-bottom:5px;">
            <div v-if="NFT.name" >
                <div class="el-card-name">{{NFT.name}} , 
                    <span v-if="NFT.refCount" style="color:#0375fc;">
                        {{5 - NFT.refCount}} blessing left
                    </span>
                    <br> 
                    <span style="color:#0375fc;">MEOW 猫 <span style="font-weight:bold">#{{NFT.id}}</span></span> 
                    <span v-if="NFT.gamma" style="color:#BBB;">
                        &#x2B05; Super Guardian MEOW 猫 #{{NFT.gamma}}
                    </span>
                     
                </div>
            </div>
        </el-card>

        <el-card  style="width: 500px; height:200px; background : #ffe600;">
            <div v-if="NFT.description" >
                <div class="el-card-wish">
                    <div style="background:#00ba2c; height:80px; padding:10px; color:#3f3f3f; 
    border-radius: 10px 10px 0 0;">{{NFT.description}}</div>
                </div>
            </div>
            <div >
                <div class="el-card-neko"> 
                    <div style="background:#7ac943; height:40px; padding:10px; color:#3f3f3f; border-radius:0 0 10px 10px;">{{ NFT.piggyBank }} NEKO</div>
                </div>
            </div>
        </el-card>



        <!-- Action Button -->
        <el-card  style="width: 500px; height:100px; ">
            <router-link :to="'/wish/' + NFT.id "> 
                <el-button > Guardian </el-button>
            </router-link>
            &nbsp;
            <el-button @click="sendGift(NFT.id)">Gift</el-button>
            <el-button @click="openApproveSellDialog(NFT.id)">Sell</el-button>
        </el-card>

  
        <div style="clear:both;"></div>


        <!-- Maneki Power -->
        <el-card  class="el-card-meta" style="width: 500px">
            <el-table class="el-table-meta" :data="NFT.attributes" stripe>
                <el-table-column prop="trait_type" label="MANEKI POWER" width="180"> </el-table-column>
                <el-table-column prop="value" label="value #" > </el-table-column>      
            </el-table>
        </el-card>

        <!-- Chart -->
        <el-card  class="el-card-chart" style="width: 440px">
            <radar-chart :chart-data="chartData" :options="options" style="background:white; padding:5px 20px;"></radar-chart>
        </el-card>

        <!-- Certificate -->
        <el-card style="width:440px">
            <el-table  :data="certificate" stripe>
                <el-table-column prop="trait_type" label="Birth Certification" width="170"> </el-table-column>
                <el-table-column prop="value" > </el-table-column>      
            </el-table>
            <div style="background-color:white">
            <el-link :href=NFT.txURL target="_blank" class="linkVerify" type="primary" style="background-color:white; text-align:center; margin:0 auto; ">Verify Certificate</el-link>
            </div>
        <div style="clear:both;"></div>
        </el-card>



        <div style="clear:both;"></div>
        </div>

        
  




        <!-- Approve to sell -->
        <el-dialog  :visible.sync="dialog.approveToSell" >  
            <div class="dialog-input-wrap">
            
                <el-form :model="sellApproveForm">
                    <el-form-item  >
                    <el-select v-model="sellApproveForm.tokenID" placeholder="Select NFT">
                        <el-option :key="'neko-'+sellApproveForm.tokenID" :label="'招き猫 #'+sellForm.tokenID" :value="sellForm.tokenID"></el-option>
                    </el-select>
                    </el-form-item>
                </el-form>

            <div slot="footer" class="dialog-footer">
                <el-button @click="dialog.approveToSell = false">Cancel</el-button>
                <el-button type="primary" @click="approveToSell()"> Approve </el-button>
            </div>
            </div>
        </el-dialog>
        


        <!-- Want to sell -->
        <el-dialog  :visible.sync="dialog.wantToSell" :close-on-click-modal="false" >
            <div class="dialog-input-wrap">
                <h2 style="color:white;"> Want to Sell</h2>

                <el-form :model="sellForm">
                    <el-form-item  >
                    <el-select v-model="sellForm.tokenID" placeholder="Select NFT">
                        <el-option :key="'neko-'+sellForm.tokenID" :label="'招き猫 #'+sellForm.tokenID" :value="sellForm.tokenID"></el-option>
                    </el-select>
                    </el-form-item>

                    <el-form-item >
                        <el-select v-model="sellForm.paymentToken" placeholder="Pay by">
                            <el-option label="CFX" value="cfx:aathhv7a493sc2r6p5g5se7ehjpxsrtcy6863hna4v"></el-option>
                            <el-option label="USDT" value="cfxtest:acepe88unk7fvs18436178up33hb4zkuf62a9dk1gv"></el-option>
                            <!-- <el-option label="NEKO" value="cfxtest:aca9u5eu3zvmjv6gdudvf21g9uj2cm60upya76k4e4"></el-option> -->
                        </el-select>
                    </el-form-item>

                    <el-form-item >
                        <el-input v-model="sellForm.endPrice" placeholder="Lowest price" value="100"></el-input>
                    </el-form-item>

                    <el-form-item >
                        <el-input v-model="sellForm.startPrice" placeholder="Highest price" value="100"></el-input>
                    </el-form-item>


                    <el-form-item >
                        <el-input v-model="sellForm.duration" placeholder="Duration (Hour)" value="24"></el-input>
                    </el-form-item>
                </el-form>

            <div slot="footer" class="dialog-footer">
                <el-button @click="dialog.wantToSell = false">Cancel</el-button>
                <el-button type="primary" @click="wantToSell()"> Approve </el-button>
            </div>
            </div>
        </el-dialog>


        <!-- Approving -->
        <el-dialog  :visible.sync="dialog.approving" :close-on-click-modal="false" >
            <div class="dialog-input-wrap" style="color:white;">
                <h2 > Approving ... </h2>
                <div> #{{NFT.id}} to sell in Marketplace
                </div>
            </div>
        </el-dialog>


        <!-- Publishing -->
        <el-dialog  :visible.sync="dialog.publishing" :close-on-click-modal="false" >
            <div class="dialog-input-wrap">
                <h2 style="color:white;"> Publishing {{ NFT.id }} to marketplace </h2>
            </div>
        </el-dialog>


        <!-- Published -->
        <el-dialog  :visible.sync="dialog.successPublish" :close-on-click-modal="false" >
            <div class="dialog-input-wrap" style="color:white; height:200px">
                <h2 > Published successfully </h2>
                <div> Transacted : <br>
                <el-link :href=NFT.TxLogURL target="_blank" class="linkVerify" type="primary">{{ NFT.TxLog }}</el-link>
                </div>
            </div>
            <div slot="footer" class="dialog-footer">
                <el-button type="primary" @click="$router.push('/marketplace')"> Close </el-button>
            </div>
        </el-dialog>


        <!-- Send as gift -->
        <el-dialog  :visible.sync="dialog.sendGift" >
            <div class="dialog-input-wrap">
                <h2 style="color:white;"> Send to a friend </h2>
            

            <el-form :model="giftForm">
                <el-form-item  >
                <el-select v-model="giftForm.tokenID" placeholder="Select NFT">
                    <el-option :key="'neko-'+giftForm.tokenID" :label="'招き猫 #'+giftForm.tokenID" :value="giftForm.tokenID"></el-option>
                </el-select>
                </el-form-item>

                <el-form-item >
                <el-input v-model="giftForm.receiverAddr" placeholder="Conflux wallet cfx:aapn..."></el-input>
                </el-form-item>

            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialog.sendGift = false">Cancel</el-button>
                <el-button type="primary" @click="approveSendGift()"> Approve </el-button>
            </div>
            </div>
        </el-dialog>

    </div>
</template>

<script>

import abi_collectible from '../web3/abi_collectible';
import abi_exchange from '../web3/abi_exchange';
// Contract : ERC20 - $NEKO
import abi_neko from '../web3/abi_neko';

import RadarChart from '../charts/RadarChart';

import ConfluxPortalOnboarding from 'conflux-portal-onboarding'
    
    const { Conflux} = require('js-conflux-sdk');

    // In browser: const Conflux = window.TreeGraph.Conflux;

    const conflux = new Conflux({
        url: "https://test.confluxrpc.com",
        networkId: 1
        });


    conflux.provider = window.conflux

    //connect Collectibles Contract 
    const contract_collectibles =  conflux.Contract({abi:abi_collectible, address:'cfxtest:acgrtg0ncb3jmj8621f8fa78bhbxrg38kazk4n25uw'});
    
    const contract_marketplace = conflux.Contract({abi:abi_exchange, address:'cfxtest:acg512fh0g276nar1n2te23js1808hzrcp2vdc2d2e'});

    //connect Neko Contract
    const contract_neko = conflux.Contract({abi:abi_neko, address:'cfxtest:aca9u5eu3zvmjv6gdudvf21g9uj2cm60upya76k4e4'});



    const forwarderOrigin = 'http://localhost:8080'
    const onboarding = new ConfluxPortalOnboarding({ forwarderOrigin })
    //onboarding.startOnboarding()


export default {
    components: {
        RadarChart
    },
    data(){
        return{
            accountNekoBalance:0,
            accountCFXBalance:0,
            accountMeowBalance:0,
            contractInstance:null,
            account:null,
            contract :{
                collectibles:null,
                payment:null,
            },
            display :{
                isConnect : false,
            },
            newNFT : {
                id      : '',
                DNA     : '',
                gammaID : '',
                power   : '',
                refCount: '',
                wish    : ''
            },
            NFT: {
                id : null,
                transactionHash : null,
                txURL     : null,
                valuation : null,
                scarcity  : null,
                name:null,
                description:null,
                external_url:null,
                art_dna:null,
                image:null,
                wish:null,
                piggyBank : null,
                attributes: [{
                    trait_type:null,
                    value:null,
                    display_type:null
                }],
                TxLog : null,
                TxLogURL : null
            },
            energies : {
                wealth      : 0,
                opportunity : 0,
                relationship: 0,
                wisdom      : 0,
                health      : 0
            },
            // Radarchart Setting and data
            chartData : {
                labels: [ "Wealth",	"Opportunity","Relationship", "Wisdom",	"Health"],
                datasets: [
                {
                    label: 'Maneki Power',
                    borderWidth: 0.5,
                    fill: true,
                    backgroundColor: 'rgba(255, 99, 132,0.2)',
                    borderColor: 'rgb(255, 99, 132,0.2)',
                    pointBackgroundColor: 'rgb(255, 99, 132)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgb(255, 99, 132)',
                    data: [0, 0, 0, 0, 0]
                },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scale: {
                    ticks: {
                    min: 0,
                    max: 99999,
                    stepSize: 30000
                    }
                }
            },
            valuation : [],
            certificate : [],
            dialog :{
                approveToExchange : false,
                offerForm : false,
                sendGift : false,
                wantToSell : false,
                approveToSell : false,
                publishing : false,
                successPublish : false,
                approving : false
            },
            giftForm : {
                tokenID: '',
                receiverAddr:''
            },
            sellApproveForm : {
                tokenID: '',
            },
            sellForm : {
                sellerAddr :'',
                tokenID: '',
                paymentToken : '',
                startPrice : 100,
                endPrice : 10,
                duration :24,
            },
            formLabelWidth : '120px',
        }
    },
    mounted() {

        if (typeof window.conflux !== "undefined") {
            console.log('Conflux portal is installed!');
            this.updateData();
        } else {
            console.log('Please install Conflux portal, go to confluxnetwork.org');
        }


        //this.updateData();
        //this.getMetadata(this.$route.params.id);
        //this.getNFTDetails(this.$route.params.id);
        //this.checkWalletBalance();
        
    },
    methods :{

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

                this.getMetadata(this.$route.params.id);
                this.getNFTDetails(this.$route.params.id);
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
                    this.accountNekoBalance = (parseInt(res.toString()/1e18)).toLocaleString();
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
                await contract_collectibles.totalSupply().call({
                    from: this.account,
                }).then((res) => {
                    this.accountMeowBalance = parseInt(res);
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
                window.conflux.enable()
            }
        },
        fillData (a,b,c,d,e) {

            console.log('pre-filling :');

            this.chartData = {
                labels: [ "Wealth",	"Opportunity","Relationship", "Wisdom",	"Health"],
                datasets: [
                {
                    label: 'Maneki Power',
                    borderWidth: 0.5,
                    fill: true,
                    backgroundColor: 'rgba(255, 99, 132,0.2)',
                    borderColor: 'rgb(255, 99, 132,0.2)',
                    pointBackgroundColor: 'rgb(255, 99, 132)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgb(255, 99, 132)',
                    data: [a, b, c, d, e]
                },
                ]
            }
            console.log('filling oppotunity:',b);
        },
        getCertificate (){

            this.certificate.push ({
                trait_type: 'Token ID',
                value: this.$route.params.id,
            });

            this.certificate.push ({
                trait_type: 'Name',
                value: this.NFT.name,
            });

            this.certificate.push ({
                trait_type: 'Guardian',
                value: this.NFT.guardian,
            });
            this.certificate.push ({
                trait_type: 'Gamma',
                value: this.NFT.gamma,
            });
            this.certificate.push ({
                trait_type: 'DNA',
                value: this.NFT.art_dna,
            });
            this.certificate.push ({
                trait_type: 'Blockchain Certificate',
                value: this.NFT.transactionHash,
            });


        },
        getValuation (pow){
            //valuation return the decimal within 0 - 1
            //scarcity 0 - 100%
            var Power = pow/2;
            var Mean  = 250000;
            var Range = 250000;
            var BasePrice = 10.00;

            if (Power>250000){
                // power above 250k
                this.NFT.valuation = (((Power-Mean)/100000)**5)/97.65625;
            } else {
                // power below 250k
                this.NFT.valuation = (((Range-Power)/100000)**5)/97.65625;
            }
            
            // x10
            if (this.NFT.valuation * 100 < 18.8 ){
                this.NFT.scarcity = 18.8 + this.NFT.valuation * 10;
            } else {
                this.NFT.scarcity = this.NFT.valuation * 100;
            }
            this.NFT.valuation = parseFloat(BasePrice + this.NFT.valuation * 500).toFixed(4);

            // store data to valuation

            this.valuation.push ({
                trait_type: 'Valuation',
                value: this.NFT.valuation,
            })

            this.valuation.push ({
                trait_type: 'Luckiness',
                value: this.NFT.scarcity,
            })

            this.valuation.push ({
                trait_type: 'Limited',
                value: 'Yes',
            })

            this.valuation.push ({
                trait_type: 'Transcacted',
                value: 5,
            })

            this.valuation.push ({
                trait_type: 'Coins invited',
                value: 123132132,
            })

            this.valuation.push ({
                trait_type: 'Gamma',
                value: this.NFT.gamma,
            })

            this.valuation.push ({
                trait_type: 'Guardian',
                value: this.NFT.guardian,
            })

            console.log ('valuation :', this.NFT.valuation);
            console.log ('Power :', pow);


        },
        async getNFTDetails(_id){

            contract_collectibles.Nekos(_id).call().then((res) => {

                this.NFT.id           = _id;
                this.NFT.power        = res[0]; 
                this.NFT.DNA          = res[1];
                this.NFT.piggyBank    = parseInt(res[4].toString()/10**18).toLocaleString() ;


                if (res[2].toString()!=0){
                    this.NFT.refCount        = res[2].toString();
                } else {
                    this.NFT.refCount        = null;
                }


                if (res[3].toString()!=0){
                    this.NFT.gamma        = res[3].toString();
                } else {
                    this.NFT.gamma        = null;
                }

                console.log('NFT details : ',res[4].toString()/10**18);
                
            })    

        },
        async getMetadata(id){
            const axios = require('axios');
            await axios.get('https://metadata.neko.exchange/token/'+id)
            .then((res) => {
                console.log ('metadata :',res.data);
                //import data form metadata
                this.NFT.id = id;
                this.NFT.transactionHash = res.data.transactionHash;
                this.NFT.txURL  = 'https://testnet.confluxscan.io/transaction/'+ res.data.transactionHash;
                this.NFT.name = res.data.name;
                this.NFT.guardian = res.data.guardian;
                this.NFT.gamma = res.data.gamma;
                this.NFT.description = res.data.description;
                this.NFT.external_url = res.data.external_url;
                this.NFT.art_dna = res.data.art_dna;
                this.NFT.image = res.data.image;
                this.NFT.attributes = res.data.attributes;

                //fill in data for charting
                this.fillData(
                    parseInt(res.data.attributes[5].value.toString().replace(',', '')),
                    parseInt(res.data.attributes[6].value.toString().replace(',', '')),
                    parseInt(res.data.attributes[7].value.toString().replace(',', '')),
                    parseInt(res.data.attributes[8].value.toString().replace(',', '')),
                    parseInt(res.data.attributes[9].value.toString().replace(',', ''))
                    );

                //make valuation
                this.getValuation(res.data.attributes[3].value.toString().replace(',', ''));


                //birth certification 
                this.getCertificate();

                console.log('Guardian Metadata', this.NFT);

            }).catch((err) => {
                console.log(err, 'err');
            });    
        },
        imageLoadError () {
            console.log('Image failed to load');
            //this.generateNewNFT(this.$route.params.id,);
        },


        async generateNewNFT(_id){

            // get transactionHash
            await this.contract.payment.getPastEvents('PAYMENT', {
                filter: { payee: this.account },
                fromBlock: 0,
                toBlock: 'latest'
            }).then((events) => {  
            
            //received transactionHash
            for( let i =0 ; i < events.length ; i++){
                    if(events[i].returnValues._nftID==this.$route.params.id){
                        this.NFT.transactionHash = events[i].transactionHash;
                        console.log('transactionHash : ',events[i].transactionHash);
                    }
            }

            //start generate Image and Metadata


            this.contract.collectibles.methods.Nekos(_id).call({
            from: this.account
            }).then((res) => {
                this.newNFT.id      = _id;
                this.newNFT.DNA     = res.DNA;
                this.newNFT.gamma   = res.gammaNekoID;
                this.newNFT.power   = res.power; 
                this.newNFT.refCount= res.refCount;
                this.newNFT.wish    = '';

                console.log('gen new NFT',this.newNFT);
                this.generateFile ();
                
            })


            }).catch((err) => {
                console.log(err, 'err');
            });


        },
        async generateFile (){
                // generate Metadata and GIF
                const {data:res2} = await this.$http.post('/painter/', this.newNFT);
                console.log('api data 2 : ', res2);
                //this.$router.go();
        },
        sendGift(_tokenID){
            console.log('send gift activated')
            this.giftForm.tokenID = _tokenID;
            this.dialog.sendGift = true;
        },
        openApproveSellDialog(_tokenID){
            console.log('Approve to sell activated')
            this.sellApproveForm.tokenID =_tokenID;
            this.sellForm.tokenID = _tokenID;
            this.dialog.approveToSell = true;
        },
        async approveToSell(){
            console.log('Approve for selling')
            // sell approve able to transfer to marketplace, via COLLECTIBLE contract

            this.dialog.approveToSell = false;
            this.dialog.approving = true;

            await window.conflux.enable().then((currentWalletAddr) => {

                console.log('Connect Accounts :', currentWalletAddr);
                console.log('Marketplace addr : ', contract_marketplace.address);

            
                contract_collectibles.approve(contract_marketplace.address, this.sellApproveForm.tokenID).sendTransaction({
                        from: currentWalletAddr
                    }).executed().then((res) => {

                    console.log('Approved ',res);

                    this.dialog.approving = false;
                    this.dialog.wantToSell = true;
                    this.$message.success('approve marketplace to create your listing');


                }).catch((err) => {
                    console.log(err, 'err!!');
                    this.$message.error("Sending Failed");
                });
                
            });

        },
        openWantToSellDialog (_tokenID) {
            console.log('open want to sell dialog')
            this.dialog.wantToSell = true;
            this.sellForm.tokenID = _tokenID;
        },
        async wantToSell () {
            console.log('Want to sell activated')

            this.dialog.wantToSell = false;
            this.dialog.publishing = true;

            // sell approve able to transfer to marketplace, via COLLECTIBLE contract
            await window.conflux.enable().then((currentWalletAddr) => {

                console.log('Connect Accounts :', currentWalletAddr);
                console.log('collectible addr : ', contract_marketplace.address);
 
            
              
                // tokenId , _paymentToken, _startPrice, _endPrice, _duration
                contract_marketplace.createAuction(
                    this.sellForm.tokenID,
                    this.sellForm.paymentToken,
                    this.sellForm.startPrice*(10**18),
                    this.sellForm.endPrice*(10**18),
                    this.sellForm.duration*3600,
                    ).sendTransaction({
                        from: currentWalletAddr,
                        gas: '0x29810', //150000
                        gasPrice: '0x1388', //5000
                        storageLimit: '0x306', //774
                        }).executed().then((res) => {

                            console.log('Auction Created ',res);
                            this.NFT.TxLog = res['transactionHash'].substring(0, 15) + '...' + res['transactionHash'].substring(60, 66)
                            this.NFT.TxLogURL = 'https://testnet.confluxscan.io/transaction/'+ res['transactionHash']

                            console.log('tx hash :', this.NFT.TxLog
                            )
                            
                            this.$message.success('Listed created');

                            this.dialog.publishing = false;
                            this.dialog.successPublish = true;

                        })


            });

            // return : create Auction at MARKETPLACE contract
            
        },
        async approveSendGift (){   

            // No required to pre approve() only make transfer
            console.log('sender', this.account);
            console.log('reciever', this.giftForm.receiverAddr);

            await window.conflux.enable().then((currentWalletAddr) => {

                console.log('Connect Accounts :', currentWalletAddr);
                this.$message.success('Start Minting');
            
                contract_collectibles.sendAsGift(this.giftForm.receiverAddr, this.giftForm.tokenID).sendTransaction({
                    from: currentWalletAddr,
                    //from: this.account,
                    gas: '0x186A0', //100,000
                    gasPrice: '0x7D0', //2000
                    storageLimit: '0x7D0', //2000
                }).confirmed().then((res) => {
                    console.log('approve gift sending',res);
                    this.dialog.sendGift = false;
                }).catch((err) => {
                    console.log(err, 'err!!');
                    this.$message.error("Sending Failed");
                });
                
            });
 

        },
     
    }
}

</script>

<style scoped>
.main-container{
}
.sub-container-gaurdian-details, .sub-container-tx-details{
    padding-top: 50px;
    width:1020px;
    margin: 0 auto;
    padding-bottom: 30px;
}
.nft-image {
    padding:0px;
    margin:0px;
    width:400px;
    height: 400px;
}
.el-card {
    float:left;
    margin-left: 20px;
    margin-bottom: 30px;
    background:rgba(0, 0, 0, .4); 
    border: none;
}
.el-card-chart {
    float:left;
    margin-left: 20px;
    margin-bottom: 30px;
    background:rgba(0, 0, 0, .4); 
    border: none;
}
.el-card-wish{
    background-image: url("../../src/assets/images/icon-wish.png");
    background-repeat: no-repeat;
    background-position: top left; 
    padding-left:60px;
    color:white; 
    font-size:1.2em;
    min-height: 100px;
}
.el-card-name {
    background-image: url("../../src/assets/images/icon-meow.png");
    background-repeat: no-repeat;
    background-position: top left; 
    padding-left:60px;
    color:white; 
    font-size:1em;
    min-height: 100px;
}
.el-card-neko {
    background-image: url("../../src/assets/images/icon-neko.png");
    background-repeat: no-repeat;
    background-position: top left; 
    padding-left:60px;
    color:white; 
    min-height: 100px;
    font-size: 2em;
    
}

.el-card-meta {
    background:rgba(0, 0, 0, .4); 
}
.el-table-meta {
    background-color: none;
}
.linkVerify {
    padding: 10px;
    margin:0 auto;
    text-align: right;
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
.wallet-connection{
    width: 460px;
    text-align: right;
    color: #c6c6c6;
    float:right;
    margin-right:30px;
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
