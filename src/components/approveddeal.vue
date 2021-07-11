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
        <el-table-column prop="url" label="url">
            <template slot-scope="scope">
                <img :src="scope.row.url"/>
            </template>
        </el-table-column>
        <el-table-column prop="Seller" label="Seller" > </el-table-column>
        <el-table-column prop="Exchange" label="Exchange" > </el-table-column>
        <el-table-column prop="TokenID" label="招き猫 #"> </el-table-column>   
        
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
      activeIndex: '1',
      web3: null,
      account: null,
      contract :[],
      totalDeals:0,
      deals : [],
      search :0,
      formLabelWidth: '120px'
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

        this.contract.collectibles.getPastEvents('Approval', {
            filter: { owner: this.account },
            fromBlock: 0,
            toBlock: 'latest'
        }, function(error, events){ 

            console.log('Approval : ', events);

            for (let i=0; i < events.length ; i++){
                this.deals.push({
                    Exchange: events[i].returnValues.approved,
                    Seller  : events[i].returnValues.owner,
                    TokenID : events[i].returnValues.tokenId
                });
                
            }
           
           
            console.log('Deals : ', this.deals);

        });


    },
  }
  
}
</script>

<style lang="less" scoped>

</style>