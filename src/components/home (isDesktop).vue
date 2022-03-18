<template>
    <el-container>
            <el-container  v-if="!isDesktop">
                <el-main>
                    <!-- Maneki Meow Logo -->
                    <img src="../../src/assets/images/logo-maneki_logo.png" />
                    <p style="text-align:center;"> Maneki-Meow available for </p>
                    <div style="text-align:center; height: 20px; width:250px; margin: 0 auto;">
                        <div style="float:left; padding:10px 20px;">
                            <img src="../../src/assets/images/logo-mac.png" />
                            <br>PC
                        </div>
                        <div style="float:left; padding:10px 20px;">
                            <img src="../../src/assets/images/logo-win.png" /> 
                            <br> macOS
                        </div>
                    </div>
                </el-main>
            </el-container>

            <el-header  v-if="isDesktop">
                <el-menu  class="el-main-menu" mode="horizontal" router 
                style="text-align:center; text-color=#fff  active-text-color=#ffd04b">

                    <el-menu-item v-on:click="goHome()">Home</el-menu-item>
                    <el-menu-item index="/wishingWall">Wishes</el-menu-item>
                    <el-menu-item index="/marketplace">Marketplace</el-menu-item>
                    <el-menu-item index="/wish/guardian">Make a wish</el-menu-item>   

                </el-menu>
                <el-menu  class="el-main-menu" mode="horizontal" router 
                style=" float:right; text-align:center; text-color=#fff  active-text-color=#ffd04b">
                    <el-submenu index="/wallet">
                    <template slot="title">
                        <i class="iconfont icon-iconwallet"></i>
                        <span slot="title">wallet</span>
                    </template>
                        <el-menu-item-group>
                            <el-menu-item index="/wallet">Asset</el-menu-item>
                            <el-menu-item index="/earn">Earn</el-menu-item>
                            <el-menu-item index="/sentgift">Gift</el-menu-item>
                            <el-menu-item index="/clubhouse">Clubhouse</el-menu-item>
                            <el-menu-item index="/painter/100">Painter</el-menu-item>
                        </el-menu-item-group>
                    </el-submenu>
                </el-menu>
            </el-header>
            <el-container  v-if="isDesktop">
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
            isCollapse:true,
            isDesktop:true
        }
    },
    mounted(){
        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
            // true for mobile device
            console.log("Mode : mobile");
            this.isDesktop = false;

        }else{
            // false for not mobile device
            console.log("Mode : Desktop");
        }

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
</style>
