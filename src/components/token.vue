<template>
    <div>

        <router-link :to="'/token/' + $route.params.id"> 
        <div>Neko collectibles Token : {{ $route.params.id }}</div>
        </router-link>

        <div> {{NFT.name}}</div>
        <div> {{NFT.description}}</div>
        <div> {{NFT.external_url}} </div>
        <div> {{NFT.art_dna}} </div>

        <el-image :src="NFT.image" class="nft-image">
            <div slot="placeholder" class="image-slot">
                加载中<span class="dot">...</span>
            </div>
            <div slot="error" class="image-slot">
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
export default {
    data(){
        return{
            NFT: {
                name:null,
                description:null,
                external_url:null,
                art_dna:null,
                image:null,
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

    },
    methods :{
        getMetadata(id){
            const axios = require('axios');

            axios.get('https://api.harvestcamasu.com/token/'+id)
            .then((res) => {
                console.log ('metadata :',res.data);

                //import data form metadata
                this.NFT.name = res.data.name;
                this.NFT.description = res.data.description;
                this.NFT.external_url = res.data.external_url;
                this.NFT.art_dna = res.data.art_dna;
                this.NFT.image = res.data.image;
                this.NFT.attributes = res.data.attributes;





            }).catch((err) => {
                console.log(err, 'err');
            });
           

            
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
