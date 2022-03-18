<template>
    <el-container>
            <el-header>
                <el-menu  class="el-main-menu" mode="horizontal" router 
                style="text-align:center; text-color=#fff  active-text-color=#ffd04b">

                    <el-menu-item class="nav-home" v-on:click="goHome()">Home</el-menu-item>
                    <el-menu-item class="nav-wishes" index="/wishingWall">Wishes</el-menu-item>
                    <el-menu-item class="nav-marketplace" index="/marketplace">Marketplace</el-menu-item>
                    <el-menu-item class="nav-makeWish" index="/wish/guardian">Make a wish</el-menu-item>   

                </el-menu>
                <el-menu  class="el-main-menu nav-wallet" mode="horizontal" router 
                style=" float:right; text-align:center; text-color=#fff  active-text-color=#ffd04b">
                    <el-submenu index="/wallet">
                    <template slot="title">
                        <i class="iconfont icon-iconwallet"></i>
                        <span slot="title">wallet</span>
                    </template>
                        <el-menu-item-group>
                            <el-menu-item index="/wallet">Asset</el-menu-item>
                            <!-- <el-menu-item index="/earn">Earn</el-menu-item> -->
                            <el-menu-item index="/sentgift">Gift</el-menu-item>
                            <el-menu-item index="/clubhouse">Clubhouse</el-menu-item>
                            <!-- <el-menu-item index="/painter">Painter</el-menu-item> -->
                        </el-menu-item-group>
                    </el-submenu>
                </el-menu>
            </el-header>
            <el-container>
                <el-main>
                    <router-view/>
                </el-main>
            </el-container>
    </el-container>
</template>

<script>

export default {
    data(){
        return{
            isCollapse:true
        }
    },
    mounted(){

    },

    methods : {
        toggle(){
            this.isCollapse = !this.isCollapse;
        },
        goHome(){
            window.location.href = 'https://maneki.market';
        }
    }
}
</script>


<style lang="less" scoped>

.el-header {
    display: flex;
    justify-content: space-between;
    align-items: left;
    color: blanchedalmond;
    border-bottom:solid 1px #0086ff;
}
.el-main-menu{
    text-align: left;
    background:none;
}
.el-menu.el-menu--horizontal{
    border-bottom: none;
}
.el-aside {
}
.el-main {
    overflow:hide;
    height: 100%;
}
.el-container {
    height: 100%;
}
.iconfont {
    padding-right:15px;
}

@media only screen and (max-width: 768px) {
  /* For mobile phones: */
    .nav-marketplace {
        display: none;
    }
    .nav-makeWish {
        display: none;

    }
    .nav-wallet {
        display: none;
    }
}

</style>
