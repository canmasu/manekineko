<template>
    <div class="sales_container">

      <div> Decenterlized NFT Trading 
      NFTs Valuation 
      Select NFT > Payment Method > Buy
      Select NFT > Set a price > Sell
      </div>
        <div class="sales_box">
        <el-form :model="loginForm" class="sales_form">
            <el-form-item prop="price" class="login_item">
                <el-input prefix-icon="iconfont icon-user" placeholder="price"></el-input>
            </el-form-item>
            <el-form-item>
                    <el-select v-model="value" placeholder="Select coin">
                        <el-option
                        v-for="item in options"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value">
                        </el-option>
                    </el-select>
            </el-form-item>
            <el-form-item>
                <el-button size="mini" type="primary" @click="sell()">Sell</el-button>
            </el-form-item>
        </el-form>
        </div>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        options: [{
          value: 'opt1',
          label: '$NEKO'
        }, {
          value: 'opt2',
          label: '$BNB'
        }, {
          value: 'opt3',
          label: '$CAKE'
        }, {
          value: 'opt4',
          label: '$BAKE'
        }],
        value: ''
      }
    },
    method :{
        sell(){

        }

    }
  }
</script>

<style lang="less" scoped>
.sales_container { 
    height:100%;
}
.sales_box {
    height:200px;
    width:300px;
    padding:5px;
    background-color: #FFF;
    border-radius: 10px;
    position :absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%,-50%);
    .login_form{
        padding: 10px;
    }
}
.sales_form /deep/ .el-input__inner {
    height: 30px;
}

</style>