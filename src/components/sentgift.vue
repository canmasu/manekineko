<template>
  <div class="sales_container">

    <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" router>
      <el-menu-item index="/receivedgift">Received</el-menu-item>
      <el-menu-item index="/sentgift">Sent</el-menu-item>
    </el-menu>


  <el-card>
    <div> Gift </div>
  
  <el-table  :data="gifts.filter(data => !search || data.TokenID.includes(search))" stripe style="width: 100%">
    <el-table-column prop="tokenId" label="招き猫 #"> </el-table-column>   
    <el-table-column prop="receiver" label="Receiver" > </el-table-column>

      <el-table-column align="right">
        <template  slot="header">
            <el-input v-model="search" size="mini" placeholder="Token ID"/>
        </template>

        <template slot-scope="scope">
          <el-button size="mini"> 
            <router-link :to="'/token/' +scope.row.tokenId +'/0x0'"> View </router-link>
          </el-button>


          <el-button size="mini"> 
            <router-link :to="'/token/' +scope.row.tokenId + '/' + account"> share </router-link>
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


import abi_collectible from '../web3/abi_collectible';

const { Conflux} = require('js-conflux-sdk');
const conflux = new Conflux({
    url: "https://test.confluxrpc.com",
    networkId: 1
    });

conflux.provider = window.conflux

//connect Collectibles Contract 
const contract_collectibles =  conflux.Contract({abi:abi_collectible, address:'cfxtest:acgrtg0ncb3jmj8621f8fa78bhbxrg38kazk4n25uw'});


export default {
  data() {
    return {
      gifts : [],
      activeIndex: '1',
      web3: null,
      account: null,
      contract :[],
      totalDeals:0,
      search :0,
      formLabelWidth: '120px',
      data : [],
      isAlert : false,
      display :{
          isConnect : false,
      },
    };
  },

  mounted() {
    this.updateData();
    //this.getApprovedDeal();
    this.getLogsOfTransfer();
  },
  methods:{
  async updateData(){

      // initialize wallet connection 
      // Store init data

      await window.conflux.request({method: "cfx_requestAccounts"});

      if (typeof window.conflux !== "undefined"){
          console.log('Conflux Portal is installed!');

      try {

          const cfx = window["conflux"]
          const accounts = await cfx.request({method: "cfx_requestAccounts"});
          this.account = accounts[0]
          this.shortenAccount = accounts[0].substring(0, 6) + '...' + accounts[0].substring(44, 49)
          this.display.isConnect = true;

          console.log('Connect Accounts :', this.account);


      } catch (error) {
          if (error.code === 4001) {
              // EIP 1193 userRejectedRequest error
              console.log("Please connect to ConfluxPortal.")
          } else {
              console.error(error)
          }
      }
      }


  },

  async getLogsOfTransfer() {

  let latestEpoch  = 0;

  this.isAlert = true;
 /**
  * get epochNumber
  */ 
  await conflux.getEpochNumber().then((receipt) => {
    console.log('epochNumber :', receipt)
    latestEpoch = receipt - 1;
  })


  /*
  list contract logs of Transfer event
  FIlter : GIFT
  */
    const { address, topics } = contract_collectibles.GIFT(this.account, null, null); // any `_send`, any `_receiver`, any `_nekoID`

    for (let i=58828432; i<latestEpoch ;i+=999){

      let valTo = i + 999;

      if(valTo>latestEpoch){
        valTo = latestEpoch;
      }

      //console.log('val to :', valTo)

      const logs = await conflux.getLogs({ address, topics, fromEpoch: i, toEpoch: valTo });
      if(logs.length != 0){

        //console.log('get logs :',logs);
        let decoded = contract_collectibles.abi.decodeLog(logs[0]);
        console.log('decoded :',decoded);

            this.gifts.push({
              sender    : decoded.array[0],
              receiver  : decoded.array[1],
              tokenId   : decoded.array[2]
            })
      }
    }

    this.isAlert = false;

  },

  async getApprovedDeal(){
    this.isAlert = true;

/** 
let logs = await conflux.cfx.getLogs({
  fromEpoch: 58850462,
  toEpoch: 58851461,
  address: 'cfxtest:type.user:aapn5b8dw3bsg471sazzyzwms9u5ngjw6yzszb3m37',
  limit: 5000
});
*/

let logs = await conflux.cfx.getLogs({
  fromEpoch: 58850462,
  toEpoch: "latest_state",
  address: 'cfxtest:type.contract:acbz06p71gzn1e00c3j06gwgnebvgdw1cj50pg1teh',
  limit: 5000
});

console.log('event return :', logs);




let decoded = contract_collectibles.abi.decodeLog(logs[2]);
console.log('decoded :',decoded);


    await contract_collectibles.GIFT.getLogs('GIFT',{
      filter: { sender: this.account },
      fromEpoch: 0,
      toEpoch: "latest_state"
      }).then((events) => {

        for (let i=0; i < events.length ; i++){
          console.log('i  :', i);
            this.deals.push({
              sender    : events[i].returnValues.sender,
              receiver  : events[i].returnValues.receiver,
              tokenId   : events[i].returnValues.NekoId
            })
          
        }

        console.log('GIFT : ', events);
        console.log('events length :', events.length);
        console.log('Gifts : ', this.deals);
        this.isAlert = false;
      })
    },
  }
  
}
</script>

<style lang="less" scoped>

</style>