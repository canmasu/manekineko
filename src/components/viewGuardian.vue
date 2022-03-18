 <!-- Public Access Page : Guardian ID  -->

<template>
    <div class="main-container">
        <div class="sticky-note">
            <div class="sticky-note-text">
            Full experience available for
            <img src="../../src/assets/images/logo-win.png" />
            <img src="../../src/assets/images/logo-mac.png" />
            <br>
            <span style="color:#666;">
            Make a wish &amp; Marketplace only supported on PC / MacOS
            </span>
            </div>
        </div>
        
        <img class="maneki-logo" src="../../src/assets/images/logo-maneki_logo.png" />

        <div class="sub-container-gaurdian-details">

        <el-card class="tab-artpiece">
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
        <el-card class="tab-name">
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

        <el-card  class="tab-piggy" >
            <div v-if="NFT.description" >
                <div class="el-card-wish">
                    <div style="background:#00ba2c; height:80px; padding:10px; color:#3f3f3f;   border-radius: 10px 10px 0 0;">{{NFT.description}}</div>
                </div>
            </div>
            <div >
                <div class="el-card-neko"> 
                    <div style="background:#7ac943; height:40px; padding:10px; color:#3f3f3f; border-radius:0 0 10px 10px;">{{ NFT.piggyBank }} NEKO</div>
                </div>
            </div>
        </el-card>

        <div style="clear:both;"></div>

 
        <!-- Maneki Power -->
        <el-card  class="el-card-meta">
            <el-table class="el-table-meta" :data="NFT.attributes" stripe>
                <el-table-column prop="trait_type" label="MANEKI POWER" width="180"> </el-table-column>
                <el-table-column prop="value" label="value #" > </el-table-column>      
            </el-table>
        </el-card>

        <!-- Chart -->
        <el-card  class="el-card-chart">
            <radar-chart :chart-data="chartData" :options="options" style="background:white; padding:5px 20px;"></radar-chart>
        </el-card>

        <!-- Certificate -->
        <el-card class="el-card-cert">
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
    </div>
</template>

<script>
import RadarChart from '../charts/RadarChart';
import abi_collectible from '../web3/abi_collectible';


const { Conflux} = require('js-conflux-sdk');

// In browser: const Conflux = window.TreeGraph.Conflux;

const conflux = new Conflux({
    url: "https://test.confluxrpc.com",
    networkId: 1
    });


conflux.provider = window.conflux


//connect Collectibles Contract 
const contract_collectibles =  conflux.Contract({abi:abi_collectible, address:'cfxtest:acgrtg0ncb3jmj8621f8fa78bhbxrg38kazk4n25uw'});



export default {
    name: 'App',

    metaInfo() {
        return {
        title: 'Maneki-Meow #' + this.$route.params.id,
        titleTemplate: '%s | ' + this.NFT.description,
        htmlAttrs: { lang: 'en-US'},
        meta: [
            // Twitter Card
            {name: 'twitter:card', content: 'The only NFT able to do mining without owned a machine'},
            {name: 'twitter:title', content: 'Maneki-Meow #' + this.$route.params.id},
            {name: 'twitter:description', content:  this.NFT.description },
            // image must be an absolute path
            {name: 'twitter:image', content: this.logo},
            // Facebook OpenGraph
            {property: 'og:title', content: 'Maneki-Meow #' + this.$route.params.id },
            {property: 'og:site_name', content: 'Maneki-Meow'},
            {property: 'og:type', content: 'website'},
            {property: 'og:image', content:  this.NFT.image},
            {property: 'og:description', content:  this.NFT.description}
        ]
        }
    },

    components: {
        RadarChart
    },
    data(){
        return{
            contractInstance:null,
            account:null,
            contract :{
                collectibles:null,
                payment:null,
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
                }]
            },
            energies : {
                wealth      : 0,
                opportunity : 0,
                relationship: 0,
                wisdom      : 0,
                health      : 0
            },
            // Radarchart Setting and data
            chartData : null,
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
        }
    },
    mounted() {
        this.getMetadata(this.$route.params.id);
        this.getNFTDetails(this.$route.params.id);
        console.log('in Guardian page')
    },
    methods :{
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
        async getMetadata(id){
            const axios = require('axios');
            await axios.get('https://metadata.neko.exchange/token/'+id)
            .then((res) => {
                console.log ('metadata :',res.data);
                //import data form metadata
                this.NFT.transactionHash = res.data.transactionHash;
                this.NFT.txURL  = 'https://testnet.confluxscan.io/transaction/'+ res.data.transactionHash;
                this.NFT.name = res.data.name;
                this.NFT.id = res.data.id;
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
            this.generateNewNFT(this.$route.params.id,);
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
    width:500px;
    background:rgba(0, 0, 0, .4); 
}
.el-card-chart {
    width:440px;
}
.el-card-cert {
    width:440px;
}

.el-table-meta {
    background-color: none;
}
.linkVerify {
    padding: 10px;
    margin:0 auto;
    text-align: right;
}
.tab-artpiece{
    width: 440px;
}
.tab-piggy {
    width: 500px;
    height:200px; 
    background : #ffe600;
}
.tab-name {
    width: 500px; 
    height:65px; 
    background:none; 
    margin-bottom:5px;
}
.sticky-note {
    display: none;
}



@media only screen and (max-width: 768px) {
  /* For mobile phones: */
    .sub-container-gaurdian-details{
        padding-top: 50px;
        width:100%;
        margin: 0 auto;
        padding-bottom: 30px;
    }
    .main-container{
        max-width: 100%;
        border: 0px solid red;
    }
    .maneki-logo {
        width:100%;
        margin:0 auto;
    }

    .tab-artpiece{
        width: 100%;
        height: 100%;
    }

    .nft-image {
        padding:0px;
        margin:0px;
        width:100%;
        height: 100%;
    }
    .el-card {
        float:left;
        margin-left: 0;
        margin-bottom: 30px;
        background:rgba(0, 0, 0, .4); 
        border: none;
    }

    .el-card-meta {
        width:100%;
        background:rgba(0, 0, 0, .4); 
    }
    .el-card-chart {
        width:100%;
    }
    .el-card-cert {
        width:100%;
    }

    .tab-name {
        width: 100%;
        height: 100px;
        background:none; 
        margin-bottom:5px;
    }
    .tab-piggy {
        width: 100%;
        background : #ffe600;
    }
    .sticky-note {
        display:block;
        width:100%;
        background:#262626;
        margin: 0;
        margin-bottom: 30px;
        border-radius:10px;
    }
    .sticky-note-text{
        padding: 20px;
    }
    .sticky-note img {
        height: 20px;
        padding-left:3px;
    }
}
</style>
