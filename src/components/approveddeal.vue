<template>
    <div class="sales_container">

      <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" router>
        <el-menu-item index="/opendeal">Open deal</el-menu-item>
        <el-menu-item index="approveddeal">Approved deal</el-menu-item>
        <el-menu-item index="/tradehistory">Trade History</el-menu-item>
      </el-menu>


    <el-card>
      <div> Decenterlized NFT Trading 
      NFTs Valuation 
      Select NFT > Payment Method > Buy
      Select NFT > Set a price > Sell
      </div>
    
    <el-table  :data="deals.filter(data => !search || data.TokenID.includes(search))" stripe style="width: 100%">
     
        <el-table-column prop="tokenId" label="招き猫 #"> </el-table-column>   
        <el-table-column prop="owner" label="Seller" > </el-table-column>
        <el-table-column prop="approved" label="Exchange" > </el-table-column>
     

       
        <el-table-column align="right">
            <template  slot="header">
                <el-input v-model="search" size="mini" placeholder="Token ID"/>
            </template>

            <template slot-scope="scope">
                    <el-button size="mini"> 
                      <router-link :to="'/token/' +scope.row.TokenID +'/0x0'"> View </router-link>
                    </el-button>


                    <el-button size="mini"> 
                      <router-link :to="'/token/' +scope.row.TokenID + '/' + account"> share </router-link>
                    </el-button>

                
                    <el-button size="mini" type="danger"> BUY </el-button>
            </template>
      

        </el-table-column>         
    </el-table>

    <el-alert title="Loading ..." type="warning" show-icon v-if="isAlert"></el-alert>
    </el-card>
    </div>
</template>

<script>

import getWeb3 from '../web3/web3';


import abi_collectible from '../web3/abi_collectible';
const contract_collectible = '0x8e2F7e97f07bF6454a62FAECb4402A62B7C57e22';

export default {
  data() {
    return {
      deals : [],
      activeIndex: '1',
      web3: null,
      account: null,
      contract :[],
      totalDeals:0,
      search :0,
      formLabelWidth: '120px',
      data : [],
      isAlert : false,
    };
  },

  mounted() {
    if (typeof web3 !== 'undefined') {

      console.log('Metamask is installed!');

      getWeb3().then((res) => {
          this.web3 = res;

          
          //connect Contracts Collectibles
          this.contract.collectibles = new this.web3.eth.Contract(abi_collectible, contract_collectible);

          //get current signed wallet address
          this.web3.eth.getAccounts().then((accounts) => {
            [this.account] = accounts;
            this.getApprovedDeal();

          }).catch((err) => {
              console.log(err, 'err!!');
          });
      });
      } else {
          //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');

      }

  },
  methods:{

    getApprovedDeal(){
    this.isAlert = true;

    this.contract.collectibles.getPastEvents('Approval', {
      filter: { owner: this.account },
      fromBlock: 0,
      toBlock: 'latest'
      }).then((events) => {

        for (let i=0; i < events.length ; i++){
          console.log('i  :', i);
          if(events[i].returnValues.approved !='0x0000000000000000000000000000000000000000'){
            this.deals.push({
              approved : events[i].returnValues.approved,
              owner : events[i].returnValues.owner,
              tokenId : events[i].returnValues.tokenId
            })
          }
          
        }

        console.log('Approval : ', events);
        console.log('events length :', events.length);
        console.log('Deals : ', this.deals);
        this.isAlert = false;
      })
    },
  }
  
}
</script>

<style lang="less" scoped>

</style>