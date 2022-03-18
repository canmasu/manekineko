<template>
    <el-container>
    <el-header>


        <el-menu  class="el-main-menu" mode="horizontal" router style="text-align:center;" >
            <el-menu-item index="1">Home</el-menu-item>
            <el-menu-item index="/wishingWall">Maneki-Meow</el-menu-item>
            <el-menu-item index="3">Marketplace</el-menu-item>
            <el-menu-item index="/wish/guardian">Make a wish</el-menu-item>
        </el-menu>


        <el-menu  class="el-main-menu" mode="horizontal" router style="text-align:center;" >
        <el-submenu index="/wallet">
            <template slot="title">
                <i class="iconfont icon-iconwallet"></i>
                <span slot="title">wallet</span>
            </template>
                <el-menu-item-group>
                    <el-menu-item index="/wallet">Asset</el-menu-item>
                    <el-menu-item index="/earn">Earn</el-menu-item>
                    <el-menu-item index="/clubhouse">Clubhouse</el-menu-item>
                </el-menu-item-group>
        </el-submenu>
        </el-menu>


        <div class="toggle-btn" @click="toggle"> X </div>

    </el-header>
    <el-container>
        <el-main>
            <router-view/>
        </el-main>
        <el-aside :width="isCollapse===true ?'0':'200px'">
            <el-menu :collapse="isCollapse" :collapse-transition="false" router>

                <!--
                <el-menu-item index="/welcome">
                    <i class="iconfont icon-home"></i>
                    <span slot="title">Home</span>
                </el-menu-item>

                <el-menu-item index="/marketplace">
                    <i class="iconfont icon-exchange"></i>
                    <span slot="title">Marketplace</span>
                </el-menu-item>
                -->



                <el-menu-item index="/wish/guardian">
                    <i class="iconfont icon-dollar"></i>
                    <span slot="title">Wish</span>
                </el-menu-item>

                <el-submenu index="/wallet">
                    <template slot="title">
                        <i class="iconfont icon-iconwallet"></i>
                        <span slot="title">wallet</span>
                    </template>
                        <el-menu-item-group>
                            <el-menu-item index="/wallet">Asset</el-menu-item>
                            <el-menu-item index="/earn">Earn</el-menu-item>
                            <el-menu-item index="/clubhouse">Clubhouse</el-menu-item>
                        </el-menu-item-group>
                </el-submenu>


            </el-menu>
        </el-aside>


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

    methods : {
        toggle(){
            this.isCollapse = !this.isCollapse;
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
    overflow:scroll;
}
.el-container {
    height: 100%;
}
.iconfont {
    padding-right:15px;
}
.toggle-btn {
    text-align: center;
    line-height: 24px;
    cursor: pointer;
}
</style>
