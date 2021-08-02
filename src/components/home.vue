<template>
    <el-container>
    <el-header>

        <div class="toggle-btn" @click="toggle"> X </div>
        <h1>招き猫 Maneki-Neko</h1>
        <el-button> connect wallet </el-button>
    </el-header>
    <el-container>
        <el-aside :width="isCollapse===true ?'0':'200px'">
            <el-menu :collapse="isCollapse" :collapse-transition="false" router>

                <!--
                <el-menu-item index="/welcome">
                    <i class="iconfont icon-home"></i>
                    <span slot="title">Home</span>
                </el-menu-item>
                -->

                <el-menu-item index="/marketplace">
                    <i class="iconfont icon-exchange"></i>
                    <span slot="title">Marketplace</span>
                </el-menu-item>

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
                            <el-menu-item index="/earning">Earning</el-menu-item>
                            <el-menu-item index="/clubhouse">Clubhouse</el-menu-item>
                        </el-menu-item-group>
                </el-submenu>


<!--

                <el-submenu index="/contact">
                    <template slot="title">
                        <i class="iconfont icon-contact"></i>
                        <span slot="title">Contact</span>
                    </template>
                        <el-menu-item-group>
                            <el-menu-item index="1-1">Telegram</el-menu-item>
                            <el-menu-item index="1-2">Twitter</el-menu-item>
                            <el-menu-item index="1-3">Medium</el-menu-item>
                            <el-menu-item index="1-4">Github</el-menu-item>
                        </el-menu-item-group>
                </el-submenu>
-->

            </el-menu>
        </el-aside>


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
            isCollapse:false
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
    background-color:gainsboro;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.el-aside {
    background-color : #CCC;
}
.el-main {
    background-color: #F7F7F7;
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
