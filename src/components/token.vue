<template>
    <div>

        <router-link :to="'/token/' + $route.params.id"> 
        <div>Neko collectibles Token : {{ $route.params.id }}</div>
        </router-link>
    </div>
</template>

<script>
export default {
    data(){
        return{
            NFT: []
        }
    },
    mounted() {
        this.getMetadata('13');

    },
    methods :{
        getMetadata(id){
            //return neko details in json
            //let link = 'https://harvestcamasu.com/maneki/src/metadata/'+ id + '.json';
            
            /** 
            this.$http.get(link).then(response => {
                this.list = response.data;
            }, function(error){
                console.log(error.statusText);
            });
            */
            const axios = require('axios');
            //axios.get('https://api.coindesk.com/v1/bpi/currentprice.json')

            //https://harvestcamasu.com/maneki/API/token/123

            axios.get('https://api.harvestcamasu.com/token/'+id)
            .then((res) => {
                console.log ('metadata :',res.data);

            }).catch((err) => {
                console.log(err, 'err');
            });
           

            
  },
    }
}

</script>
