<template>
    <div class="mint_container">
        <img src="../../src/assets/images/logo-maneki_logo.png" />
        <div style="background:none; margin:50px auto;">

            <h1> Wishing wall </h1>
            <ul class="list">
                <li class="item"  v-for="(item) in NFTs" :key="item.id" >
                    <div v-if="item.name">
                        <div style="width:50; height:50px; float:left;padding:5px;"><img src="../../src/assets/images/icon-meow.png" /></div>
                        <div style="float:left; padding:10px;"> {{item.name}} <br> Guardian: {{item.id}}</div>
                        <div style="clear:both;"></div>
                    </div>
                    <router-link :to="'/viewGuardian/' + item.id">
                        <el-card :body-style="{ padding: '0', }">
                            <img :src="item.url" class="image">
                        </el-card>
                    </router-link>
                    <div v-if="item.description">
                        <div style="width:50; height:50px; float:left;padding:5px;"><img src="../../src/assets/images/icon-wish.png" /></div>
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
    const contract_collectibles =  conflux.Contract({abi:abi_collectible, address:'cfxtest:acbz06p71gzn1e00c3j06gwgnebvgdw1cj50pg1teh'});

    
    
export default {
    data(){
        return{
            activeIndex: '1',
            contractInstance:null,
            account:null,
            isCollapse:false,
            tokenSupply:0,
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

            let totalNekos = parseInt(this.NFT.supply);
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
    border: 1px solid white;
    width: 250px;
    float:left;
    margin:15px 20px;
    padding: 5px 10px;
    padding-bottom: 30px;
    background:rgba(128, 255, 209, .2); 
    border-radius: 10px;
    list-style: none;
}

</style>