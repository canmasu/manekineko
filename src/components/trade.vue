<template>
    <div class="sales_container">

      <div> Decenterlized NFT Trading 
      NFTs Valuation 
      Select NFT > Payment Method > Buy
      Select NFT > Set a price > Sell
      </div>


    <el-table  :data="deals.filter(data => !search || data.TokenID.includes(search))" stripe style="width: 100%">
        <el-table-column prop="DealID" label="Deal ID" > </el-table-column>
        <el-table-column prop="Seller" label="Seller" > </el-table-column>
        <el-table-column prop="TokenID" label="招き猫 #"> </el-table-column>             
        <el-table-column prop="Price" label="Price" > </el-table-column>
        <el-table-column prop="Currency" label="Currency"> </el-table-column>
        <el-table-column prop="Status" label="Status"> </el-table-column>   
        
        <el-table-column align="right">
            <template  slot="header">
                <el-input v-model="search" size="mini" placeholder="Token ID"/>
            </template>

            <template slot-scope="scope">
                <router-link :to="'/token/' +scope.row.TokenID">
                    <el-button size="mini"> View </el-button>
                </router-link>
                    <el-button size="mini" type="danger" > Buy </el-button>
            </template>

        </el-table-column>         
    </el-table>

  
    </div>
</template>

<script>

import getWeb3 from '../web3/web3';

import abi_exchange from '../web3/abi_exchange';
const contract_exchange = '0xA70b9A701173A59AAD272eC9b118853C150DfE8F';

export default {
  data() {
    return {
      web3: null,
      account: null,
      contract :{
        exchange:null
      },
      dialog :{
          buyWithBNB : false
      },
      totalDeals:0,
      deals : [],
      search :0
    };
  },
  mounted() {
    if (typeof web3 !== 'undefined') {

      console.log('Metamask is installed!');

      getWeb3().then((res) => {
          this.web3 = res;

          //connect Contracts Exchange
          this.contract.exchange = new this.web3.eth.Contract(abi_exchange, contract_exchange);

          //get current signed wallet address
          this.web3.eth.getAccounts().then((accounts) => {
              [this.account] = accounts;

          // some function
          this.getDeals();

          }).catch((err) => {
              console.log(err, 'err!!');
          });
      });
      } else {
          //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');

      }

  },
  methods:{

  getTotalDeals(){
      this.contract.exchange.methods.totalDeals().call().then((res) => {
          this.totalDeals = res;
          console.log('totalDeals :', this.totalDeals);
      }).catch((err) => {
          console.log(err, 'err');
      });
  },

  getDeals(){
      this.contract.exchange.methods.totalDeals().call().then((res) => {
        this.totalDeals = res;
        console.log('totalDeals :', this.totalDeals);

        // retrive all active deal 
        for (let i=0; i< this.totalDeals; i++){
          this.contract.exchange.methods.Deals(i).call().then((res) => {
            var _currency = '';
            var _status = '';

            if(res[4]==true){
              _currency = 'BNB';
            } else {
              _currency = 'NEKO';
            }
            if (res[5]==true){
              _status = 'active';
              this.deals.push({
                  DealID: i,
                  Seller: res[0],
                  Buyer: res[1],
                  TokenID: '招き猫 #' + res[2],
                  Price: res[3],
                  Currency : _currency,
                  Status : _status
              });
            }
        
          }).catch((err) => {
              console.log(err, 'err');
          });
        }


        console.log('Deals :', this.deals);


      }).catch((err) => {
          console.log(err, 'err');
      });
  },



  }
}
</script>

<style lang="less" scoped>

</style>