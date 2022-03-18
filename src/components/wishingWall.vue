<template>
    <div class="mint_container">
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

        <h1> Wishing wall <div> {{ tokenSupply }} 招き猫</div> </h1> 
        
        <div style="background:none; margin:50px auto;">
            
            <ul class="list">
                <li class="item"  v-for="(item) in NFTs" :key="item.id" >
                    <div v-if="item.name">
                        <div style="width:50; height:50px; float:left;padding:5px;"><img src="../../src/assets/images/icon-meow.png" /></div>
                        <div style="float:left; padding:10px;"> {{item.name}} <br>
                           <span style="font-size:0.8em;">MEOW 猫 <span style="font-weight:bold">#{{item.id}}</span></span> 
                        </div>



                        <div style="clear:both;"></div>
                    </div>
                    <router-link :to="'/viewGuardian/' + item.id">
                        <el-card :body-style="{ padding: '0', }">
                            <img :src="item.url" class="image">
                        </el-card>
                    </router-link>
                    <div v-if="item.description">
                        <div style="float:left; padding:10px;">{{item.description}}</div>
                    </div>

                </li>

                <div style="clear:both;"></div>
            </ul>

        </div>
    </div>
</template>

<script>

import abi_collectible from '../web3/abi_collectible';


    const { Conflux } = require('js-conflux-sdk');
    // In browser: const Conflux = window.TreeGraph.Conflux;

    const conflux = new Conflux({
    url: "https://test.confluxrpc.com",
    networkId: 1
    });
    

    //connect Collectibles Contract 
    const contract_collectibles =  conflux.Contract({abi:abi_collectible, address:'cfxtest:acgrtg0ncb3jmj8621f8fa78bhbxrg38kazk4n25uw'});

    
    
export default {
    data(){
        return{
            activeIndex: '1',
            contractInstance:null,
            account:null,
            isCollapse:false,
            tokenSupply:null,
            name:null,
            loading : true,
            valautionBNB : 0,
            valautionBUSD : 0,

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
            coinBalance : [],
            coinPrice :'',
            web3:null,

        }
    },
    mounted() {
            console.log('Public Retrive!');
            console.log('collectibles : ', contract_collectibles);
            this.getNFTSupply();
    },
    methods :{

        async getNFTSupply (){        
            await contract_collectibles.totalSupply().call().then((res) => {
                this.NFT.supply = parseInt(res[0]);
                console.log('async Neko owned :', res[0]);
                this.pumpNekos()
            }).catch((err) => {
                console.log(err, 'err');
            });

        },

        pumpNekos(){


            let totalNekos = parseInt(this.NFT.supply-1);
            this.tokenSupply = totalNekos;

            let limit = parseInt(this.NFT.supply) - 50;

            console.log('totalnekos :',totalNekos);
            console.log('limit :', limit);


            for (let i = totalNekos; i > limit; i -= 1) {
                // retrive NFT Details
                const axios = require('axios');
                axios.get('https://metadata.neko.exchange/token/'+ i)
                .then((metadata) => {

                    this.NFTs.push({
                        id: i,
                        power: metadata.data.attributes[3].value,
                        DNA: metadata.data.art_dna,
                        url: metadata.data.image,

                        name : metadata.data.name,
                        description : metadata.data.description,
                        image : metadata.data.image,
                        gamma : metadata.data.gamma,
                    });

                }).catch((err) => {
                    console.log(err, 'err');
                });

            }


        },

      
    }
}
</script>

<style scoped>
h1{
    width: 85%;
    font-size: 3em;
    color:rgba(255, 255, 0, 0.7);
    margin:0 auto;
    font-weight: 100;
    margin-bottom: 50px;
}
.list {
    top:50px;
    width: 91%;
    padding:0;
    margin:0 auto;
}

.item {
    border: 1px solid black;
    width: 280px;
    height : 400px;
    float:left;
    margin:15px 10px;
    padding: 5px 5px;
    padding-bottom: 30px;
    background:rgba(0, 0, 0, .6); 
    border-radius: 10px;
    list-style: none;
}
.sticky-note {
    display: none;
}

@media only screen and (max-width: 768px) {
  /* For mobile phones: */
    .mint_container{
        padding: 0;
        margin:0;
    }
    .maneki-logo {
        width:100%;
        margin:0 auto;
        
    }
    .el-main {
        padding:0;
        margin:0;
        border: 1px solid red;

    }
    .list {
        width: 98%;
        padding:0;
        margin:0;

    }
    .item {
        margin:0;
        margin-bottom:50px;
        width: 100%;
        height: 100%;

    }
    h1 {
        font-size:2em;
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