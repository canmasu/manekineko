<template>
    <div>

        <router-link :to="'/token/' + $route.params.id"> 
        <div>Neko collectibles Token : {{ $route.params.id }}</div>
        </router-link>

        <div> {{NFT.name}}</div>
        <div> {{NFT.description}}</div>
        <div> {{NFT.external_url}} </div>
        <div> {{NFT.art_dna}} </div>
        <div> {{NFT.wish}} </div>

        <el-image :src="NFT.image" class="nft-image">
            <div slot="placeholder" class="image-slot">
                loading<span class="dot">...</span>
            </div>
            <div slot="error" class="image-slot" @click="imageLoadError()">
                <i class="el-icon-picture-outline"></i>
            </div>
        </el-image>




        <el-table  :data="NFT.attributes" stripe style="width: 50%">
            <el-table-column prop="trait_type" label="trait_type" width="250"> </el-table-column>
            <el-table-column prop="value" label="value #" width="250"> </el-table-column>      
        </el-table>



    </div>
</template>

<script>

import getWeb3 from '../web3/web3';

import abi_collectible from '../web3/abi_collectible';
const contract_collectible = '0x8e2F7e97f07bF6454a62FAECb4402A62B7C57e22';


export default {
    data(){
        return{
            contractInstance:null,
            account:null,
            contract :{
                collectibles:null
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
                name:null,
                description:null,
                external_url:null,
                art_dna:null,
                image:null,
                wish:null,
                attributes: [{
                    trait_type:null,
                    value:null,
                    display_type:null
                }]
            }
        }
    },
    mounted() {
        this.getMetadata(this.$route.params.id);

        if (typeof web3 !== 'undefined') {
    
            console.log('Metamask is installed!');

            getWeb3().then((res) => {
                this.web3 = res;

                //connect Contracts Collectibles
                this.contract.collectibles = new this.web3.eth.Contract(abi_collectible, contract_collectible);
   
                //get current signed wallet address
                this.web3.eth.getAccounts().then((accounts) => {
                    [this.account] = accounts;

                }).catch((err) => {
                    console.log(err, 'err!!');
                });
            });
        } else {
            //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');

        }

    },
    methods :{
        getMetadata(id){
            const axios = require('axios');
            axios.get('https://metadata.neko.exchange/token/'+id)
            .then((res) => {
                console.log ('metadata :',res.data);
                //import data form metadata
                this.NFT.name = res.data.name;
                this.NFT.description = res.data.description;
                this.NFT.external_url = res.data.external_url;
                this.NFT.art_dna = res.data.art_dna;
                this.NFT.image = res.data.image;
                this.NFT.wish = res.data.wish;
                this.NFT.attributes = res.data.attributes;
            }).catch((err) => {
                console.log(err, 'err');
            });    
        },
        imageLoadError () {
            console.log('Image failed to load');
            this.generateNewNFT(this.$route.params.id);
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
                this.newNFT.wish    = '';

                console.log('gen new NFT',this.newNFT);
                this.generateFile ();
                
            })
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
.nft-image {
    width:500px;
    height: 500px;
}
</style>
