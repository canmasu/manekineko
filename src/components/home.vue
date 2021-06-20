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
                <el-menu-item index="welcome">
                    <i class="iconfont icon-home"></i>
                    <span slot="title">Home</span>
                </el-menu-item>

                <el-menu-item index="wallet">
                    <i class="iconfont icon-iconwallet"></i>
                    <span slot="title">Wallet</span>
                </el-menu-item>

                <el-menu-item index="trade">
                    <i class="iconfont icon-exchange"></i>
                    <span slot="title">Trade NFT</span>
                </el-menu-item>


                <el-menu-item index="launchpad">
                    <i class="iconfont icon-launch"></i>
                    <span slot="title">Launchpad</span>
                </el-menu-item>


                <el-menu-item index="maneki">
                    <i class="iconfont icon-dollar"></i>
                    <span slot="title">Maneki $NEKO</span>
                </el-menu-item>

                <el-menu-item index="manekineko">
                    <i class="iconfont icon-dollar"></i>
                    <span slot="title">Buy & Mint</span>
                </el-menu-item>

                <el-menu-item index="analytics">
                    <i class="iconfont icon-graph_rise"></i>
                    <span slot="title">Analytics</span>
                </el-menu-item>

                <el-submenu index="contact">
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
