<template>
<el-row>
    <el-row>
        <el-col :span="24">
            <h2> Last transcated </h2>
        </el-col>
    </el-row>
    <el-row>
        <el-col :span="8">
            <el-card shadow="always">
                <div> 
                    <img src="https://harvestcamasu.com/cryptoArtist/neko/217.svg" class="image"> 
                    NEKO / NC  1,000,000 &#8776; $12 </div>
            </el-card>
        </el-col>
        <el-col :span="8">
            <el-card shadow="always">
                <div> 
                    <img src="https://harvestcamasu.com/cryptoArtist/neko/345.svg" class="image"> 
                    BNB / NC  7.2  &#8776; $2,810 </div>
            </el-card>
        </el-col>
        <el-col :span="8">
            <el-card shadow="always">
                <div>
                    <img src="https://harvestcamasu.com/cryptoArtist/neko/74.svg" class="image">  
                    CAKE / NC  32  &#8776; $14 </div>
            </el-card>
        </el-col>
    </el-row>


    <el-row>
        <el-col :span="24">
            <el-carousel :interval="5000" arrow="always">
                <el-carousel-item v-for="item in 4" :key="item">
                <h3>{{ item }}</h3>
                </el-carousel-item>
            </el-carousel>
        </el-col>
    </el-row>
    
    <el-row>
        <el-col :span="24">
            <h2> Sale </h2>
        </el-col>
    </el-row>

    <el-row>
    <el-col :span="6">
        <el-card :body-style="{ padding: '0px' }">
        <img src="https://harvestcamasu.com/cryptoArtist/neko/327.svg" class="image">
        <el-divider></el-divider>
        <div style="padding: 14px;">
            <span>招き猫 # 327 第 97 世代</span>
            <div class="bottom clearfix">
            <el-tag
                    v-for="item in items"
                    :key="item.label"
                    :type="item.type"
                    effect="plain">
                    {{ item.label }}
            </el-tag>

            <div class="price">$NEKO 9,898,283</div>
            <el-progress :percentage="18"></el-progress>
            <el-button type="text" class="button">Buy</el-button>
            </div>
        </div>
        </el-card>
    </el-col>

    <el-col :span="6">
        <el-card :body-style="{ padding: '0px' }">
        <img src="https://harvestcamasu.com/cryptoArtist/neko/681.svg" class="image">
        <el-divider></el-divider>
        <div style="padding: 14px;">
            <span>招き猫 # 681 第 97 世代</span>
            <div class="bottom clearfix">
            <div class="price">$CAKE 120.20 </div>
            <el-progress :percentage="38"></el-progress>
            <el-button type="text" class="button">Buy</el-button>
            </div>
        </div>
        </el-card>
    </el-col>

    <el-col :span="6">
        <el-card :body-style="{ padding: '0px' }">
        <img src="https://harvestcamasu.com/cryptoArtist/neko/677.svg" class="image">
        <el-divider></el-divider>
        <div style="padding: 14px;">
            <span>招き猫 # 677 第 97 世代</span>
            <div class="bottom clearfix">
            <div class="price">$BNB 1.2</div>
            <el-progress :percentage="68"></el-progress>
            <el-button type="text" class="button">Buy</el-button>
            </div>
        </div>
        </el-card>
    </el-col>

    <el-col :span="6">
        <el-card :body-style="{ padding: '0px' }">
        <img src="https://harvestcamasu.com/cryptoArtist/neko/637.svg" class="image">
        <el-divider></el-divider>
        <div style="padding: 14px;">
            <span>招き猫 # 637 第 97 世代</span>
            <div class="bottom clearfix">
            <div class="price">$NEKO 10,000,000</div>
            <el-progress :percentage="86"></el-progress>
            <el-button type="text" class="button">Buy</el-button>
            </div>
        </div>
        </el-card>
    </el-col>
    </el-row>
    <el-row>
    <el-col :span="6">
        <el-card :body-style="{ padding: '0px' }">
        <img src="https://harvestcamasu.com/cryptoArtist/neko/27.svg" class="image">
        <el-divider></el-divider>
        <div style="padding: 14px;">
            <span>招き猫 # 27 第 97 世代</span>
            <div class="bottom clearfix">
            <div class="price">$NEKO 9,898,283</div>
            <el-progress :percentage="55"></el-progress>
            <el-button type="text" class="button">Buy</el-button>
            </div>
        </div>
        </el-card>
    </el-col>

    <el-col :span="6">
        <el-card :body-style="{ padding: '0px' }">
        <img src="https://harvestcamasu.com/cryptoArtist/neko/81.svg" class="image">
        <el-divider></el-divider>
        <div style="padding: 14px;">
            <span>招き猫 # 81 第 97 世代</span>
            <div class="bottom clearfix">
            <div class="price">$CAKE 120.20 </div>
            <el-progress :percentage="45"></el-progress>
            <el-button type="text" class="button">Buy</el-button>
            </div>
        </div>
        </el-card>
    </el-col>

    <el-col :span="6">
        <el-card :body-style="{ padding: '0px' }">
        <img src="https://harvestcamasu.com/cryptoArtist/neko/617.svg" class="image">
        <el-divider></el-divider>
        <div style="padding: 14px;">
            <span>招き猫 # 617 第 97 世代</span>
            <div class="bottom clearfix">
            <div class="price">$BNB 1.2</div>
            <el-progress :percentage="43"></el-progress>
            <el-button type="text" class="button">Buy</el-button>
            </div>
        </div>
        </el-card>
    </el-col>

    <el-col :span="6">
        <el-card :body-style="{ padding: '0px' }">
        <img src="https://harvestcamasu.com/cryptoArtist/neko/537.svg" class="image">
        <el-divider></el-divider>
        <div style="padding: 14px;">
            <span>招き猫 # 537 第 97 世代</span>
            <div class="bottom clearfix">
            <div class="price">$NEKO 10,000,000</div>
            <el-progress :percentage="57"></el-progress>
            <el-button type="text" class="button">Buy</el-button>
            </div>
        </div>
        </el-card>
    </el-col>
    </el-row>
</el-row>
</template>

<script>

import NekoABI from '../web3/abi_neko';
const contractAddress = '0xdF3CF86Faed8a1936F3dB48a374E981e3fFC3164';

const Web3 = require('web3');
const web3 = new Web3('https://data-seed-prebsc-1-s1.binance.org:8545');
const contract = new web3.eth.Contract(NekoABI,contractAddress);

export default {
  data() {
    return {
        account :null,
        contractInstance:null,
        balance :0,
        currentDate: new Date(),
        items: [
            { type: '', label: 'Si Putih' },
            { type: '', label: 'Money money come' }
        ]
    };
  },
  mounted() {
    // check metamask and request for installation
    if(typeof window.ethereum !== 'undefined'){
        console.log('Metamask is installed!');
        this.getAccount();
        this.connectBlockchain();
    } else {
        console.log('Please Install Metamask');
    }
  },
  methods : {
    async getAccount(){
        await window.ethereum.request({method: 'eth_requestAccounts'}).then((res) => {
            const accounts = res;
            this.account = accounts[0];
            console.log('inner asyn ',this.account);
        }).catch((err) => {
            console.log(err, 'err');
        });

        await window.ethereum.request({
            method: 'eth_getBalance',
            params: [this.account,"latest"],
        }).then((res) => {
            this.balance = parseInt(res)/10**18;
            console.log('inner balance ',this.balance);
        }).catch((err) => {
            console.log(err, 'err');
        });
    }, 
    async connectBlockchain (){
        //test a Return value

        await window.ethereum.request({method: 'eth_requestAccounts'}).then((res) => {
            const accounts = res;
            this.account = accounts[0];

            console.log('wallet addrs :', this.account);
            contract.methods.balanceOf(this.account).call().then((res) => {
                console.log('async You $neko Bal :', res);
            }).catch((err) => {
                console.log(err, 'err');
            });

        }).catch((err) => {
            console.log(err, 'err');
        });



    }

  }
}
</script>

<style>
  .el-carousel__item h3 {
    color: #475669;
    font-size: 18px;
    opacity: 0.75;
    line-height: 300px;
    margin: 0;
  }
  
  .el-carousel__item:nth-child(2n) {
    background-color: #99a9bf;
  }
  
  .el-carousel__item:nth-child(2n+1) {
    background-color: #d3dce6;
  }
  .el-row {
    margin-bottom: 20px;
    &:last-child {
      margin-bottom: 0;
    }
  }
  .el-col {
    border-radius: 4px;
  }
  .grid-content {
    border-radius: 4px;
    min-height: 36px;
  }
  .row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
  }
  .el-card {
      padding: 3px;
      margin:3px;
  }
  .price {
    margin-top: 13px;
  }

  .bottom {
    margin-top: 13px;
    line-height: 12px;
  }

  .button {
    padding: 0;
    float: right;
  }

  .image {
    width: 100%;
    display: block;
  }

  .clearfix:before,
  .clearfix:after {
      display: table;
      content: "";
  }
  
  .clearfix:after {
      clear: both
  }
  .el-tag {
      padding: 3px;
      margin:3px;
  }
</style>