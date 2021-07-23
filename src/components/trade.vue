<template>
    <div class="sales_container">

      <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" router>
        <el-menu-item index="/opendeal">Open deal</el-menu-item>
        <el-menu-item index="/approveddeal">Approved deal</el-menu-item>
        <el-menu-item index="/tradehistory">Trade History</el-menu-item>
      </el-menu>



    <el-row>
      <el-col :span="8" v-for="(deal, index) in deals" :key="deal.DealID" :offset="index > 0 ? 0 : 0">
        <el-card :body-style="{ padding: '0px' }">

          <div>Neko Name : {{deal.name}}</div>
          <div>Wish : {{deal.description}}</div>

          <img :src="deal.url" class="image">
          <div style="padding: 14px;">
            <!-- 
              Deal ID

              Status
              Seller
              Price
              Currency

              TokenID
              Name
              Wish
              Image
              Valuation 
              Last Price

              Share, Buy
            -->

            <div>Deal id: {{deal.DealID}}</div>
            <div>Seller : {{deal.Seller}}</div>
            <div>Token id : {{deal.TokenID}}</div>
            <div>Price : {{deal.Currency}} {{deal.Price}}</div>
            <div>Valuation : {{deal.valuation}}</div>
            <div>Gamma : {{deal.gamma}}</div>



            <div class="bottom clearfix">

              <el-button size="mini"> 
                <router-link :to="'/token/' + deal.TokenID +'/0x0'"> View </router-link>
              </el-button>

              <el-button size="mini"> 
                <router-link :to="'/token/' + deal.TokenID + '/' + account"> share </router-link>
              </el-button>

              <el-button size="mini" type="danger" 
                @click="wantToBuy(
                deal.DealID, 
                deal.TokenID,
                deal.Price,
                deal.Currency)"> BUY 
              </el-button>

            </div>
          </div>
        </el-card>
      </el-col>
    </el-row>

    <el-card>
    <!--
    <el-table  :data="deals.filter(data => !search || data.TokenID.includes(search))" stripe style="width: 100%">
        <el-table-column prop="url" label="url">
            <template slot-scope="scope">
                <img :src="scope.row.url"/>
            </template>
        </el-table-column>
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
                    <el-button size="mini"> 
                      <router-link :to="'/token/' +scope.row.TokenID +'/0x0'"> View </router-link>
                    </el-button>


                    <el-button size="mini"> 
                      <router-link :to="'/token/' +scope.row.TokenID + '/' + account"> share </router-link>
                    </el-button>

                
                    <el-button size="mini" type="danger" 
                        @click="wantToBuy(
                          scope.row.DealID, 
                          scope.row.TokenID,
                          scope.row.Price,
                          scope.row.Currency)"> BUY 
                    </el-button>
            </template>
      

        </el-table-column>         
    </el-table>
    -->

    </el-card>

    <el-dialog title="Buy Neko Collectible" :visible.sync="dialog.buyNFT">
        <el-form :model="buyForm">
            <el-form-item label="NFT" :label-width="formLabelWidth">
              招き猫 # {{buyForm.TokenID}}
            </el-form-item>
            <el-form-item label="Price" :label-width="formLabelWidth">
              ${{buyForm.Currency}} {{buyForm.Price}}
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="dialog.buyNFT = false">Cancel</el-button>
            <el-button type="primary" @click="buyNFT()"> Confirm </el-button>
        </div>
    </el-dialog>


    <el-dialog title="Approve $Neko to use" :visible.sync="dialog.approveSpend">
        <div>${{buyForm.Currency}} {{buyForm.Price}} be use for buying </div>
        <div slot="footer" class="dialog-footer">
            <el-button @click="dialog.approveSpend = false">Cancel</el-button>
            <el-button type="primary" @click="approve()"> Confirm </el-button>
        </div>
    </el-dialog>
  
    <el-dialog title="Payment confirm" :visible.sync="dialog.paymentConfirm">
        <div> 招き猫 # {{buyForm.TokenID}} </div>
        <div>${{buyForm.Currency}} {{buyForm.Price}}</div>
        <div slot="footer" class="dialog-footer">
            <el-button @click="dialog.approveSpend = false">Cancel</el-button>
            <el-button type="primary" @click="paymentConfirm()"> Confirm </el-button>
        </div>
    </el-dialog>
    </div>
</template>

<script>

import getWeb3 from '../web3/web3';

// Contract : Exchange
import abi_exchange from '../web3/abi_exchange';
const contract_exchange = '0x9B206bDA6B505AAe9af79a61d268f4698660D8E2';

// Contract : ERC20 - $NEKO
import abi_neko from '../web3/abi_neko';
const contract_neko = '0xdF3CF86Faed8a1936F3dB48a374E981e3fFC3164';

export default {
  data() {
    return {
      activeIndex: '1',
      web3: null,
      account: null,
      contract :{
        exchange:null,
        neko:null
      },
      dialog :{
          buyNFT : false,
          approveSpend : false,
          paymentConfirm : false
      },
      buyForm: {
          DealID: '',
          TokenID: '',
          Price :'',
          Currency : ''
      },
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

          //connect Contracts Exchange
          this.contract.exchange = new this.web3.eth.Contract(abi_exchange, contract_exchange);
          this.contract.neko = new this.web3.eth.Contract(abi_neko, contract_neko);

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

wantToBuy (_DealID, _TokenID, _Price, _Currency) {
    this.buyForm.DealID = _DealID;
    this.buyForm.TokenID = _TokenID;
    this.buyForm.Price = _Price;
    this.buyForm.Currency = _Currency;
    this.dialog.buyNFT = true;
},
buyNFT (){

  if(this.buyForm.Currency=='BNB'){
    // connect to buy_byETH
    this.payWithETH();
  } else {
    // connect to buy_byERC20
    this.dialog.buyNFT = false;
    this.dialog.approveSpend = true;

  }

},
payWithETH () {
    this.contract.exchange.methods.buy_byETH(this.buyForm.DealID,this.buyForm.TokenID,'0x0000000000000000000000000000000000000000').send({
      from: this.account,
      value: this.buyForm.Price
    }).then((res) => {
        console.log('buy NFT success :' ,res);
        this.dialog.buyNFT = false;
    })
},
approve(){
    this.contract.neko.methods.approve(contract_exchange,this.buyForm.Price).send({
      from: this.account
    }).then((res) => {
        console.log('approved ! :' ,res);
        this.dialog.approveSpend = false;
        this.dialog.paymentConfirm = true;
    })

},
paymentConfirm() {
    console.log('in payment confirm');
    this.contract.exchange.methods.buy_byERC20(this.buyForm.DealID,this.buyForm.TokenID,'0x0000000000000000000000000000000000000000').send({
      from: this.account
    }).then((res) => {
        console.log('buy NFT success :' ,res);
        this.dialog.paymentConfirm = false;
        
    })
},
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
  

              const axios = require('axios');
              axios.get('https://metadata.neko.exchange/token/'+ res[2])
              .then((metadata) => {
                  console.log ('metadata :',metadata.data);
        
                  //make valuation
                  
                  let pow = metadata.data.attributes[3].value.toString().replace(',', '')

                  //valuation return the decimal within 0 - 1
                  //scarcity 0 - 100%
                  var Power = pow/2;
                  var Mean  = 250000;
                  var Range = 250000;
                  var BasePrice = 1.00;
                  var valuation = 0;
                  var scarcity = 0;

                  if (Power>250000){
                      // power above 250k
                      valuation = (((Power-Mean)/100000)**5)/97.65625;
                  } else {
                      // power below 250k
                      valuation = (((Range-Power)/100000)**5)/97.65625;
                  }
                  
                  // x10
                  if (valuation * 100 < 18.8 ){
                      scarcity = 18.8 + valuation * 10;
                  } else {
                      scarcity = valuation * 100;
                  }



                // write Deal
                _status = 'active';
                this.deals.push({
                    DealID: i,
                    Seller: res[0],
                    Buyer: res[1],
                    TokenID: res[2],
                    Price: res[3],
                    Currency : _currency,
                    Status : _status,
                    url: metadata.data.image,

                    name : metadata.data.name,
                    description : metadata.data.description,
                    image : metadata.data.image,
                    gamma : metadata.data.gamma,
                    valuation : 'BUSD ' + parseFloat(BasePrice + valuation * 500).toFixed(4),
                    scarcity : scarcity,
                    
                });
  

              }).catch((err) => {
                  console.log(err, 'err');
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