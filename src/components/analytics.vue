<template>
    <div>
        <div>graph show value of transacted</div>
        <div> transaction record Age, value , who to who </div>
        <el-button @click="buyDirect(this.account,'999000100010','12')">Buy the dip</el-button>
    </div>
</template>

<script>

import getWeb3 from '../web3/web3';
import contractAbi from '../web3/abi_collectible';
const contractAddressCollectibles = '0x9CCD560e93C2be416edE43C4E97941b7b443b9CE';

export default {
  name: 'App',
  data() {
    return {
      web3: null,
      account: null,
      contractInstance: null,
    };
  },
  mounted() {
      if (typeof web3 !== 'undefined') {
  
        console.log('Metamask is installed!');

        getWeb3().then((res) => {
            this.web3 = res;
            this.contractInstance = new this.web3.eth.Contract(contractAbi, contractAddressCollectibles);
            
            window.ethereum.request({method: 'eth_requestAccounts'}).then((accounts) => {
                [this.account] = accounts;
                console.log('analytic connected!', [this.account]);
                // some function
                this.buyDirect(this.account,'999000100010','12');

                
                }).catch((err) => {
                    console.log(err, 'err!!');
                });
        });
        } else {
            //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');

        }
  },
  methods:{

        async buyDirect(_buyer,_machine,_refNekoId){
            await this.contractInstance.methods.mintCollectible(_buyer,_machine,_refNekoId).send({
                from: this.account,
                });
                
        },

  }
}
</script>