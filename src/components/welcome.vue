<template>



    <div class="mint_container">
        <div class="sticky-note">
            <div class="sticky-note-text">
            Full experience available for
            <img src="../../src/assets/images/logo-win.png" />
            <img src="../../src/assets/images/logo-mac.png" />
            <br>
            <span style="color:#666;">
            Make a wish &amp; Marketplace only supported on PC / MacOS
            </span>
            </div>
        </div>
        <img class="maneki-logo" src="../../src/assets/images/logo-maneki_logo.png"/>


        <div  v-if="!isDesktop" style=" border:0px solid red; color:white; text-align: center; width:100%; margin:0 auto; margin-top:45px;">

            <div style="border:0px solid green; text-align:center; padding-top:50px;">
            <div style="padding:20px 0; ">Explore latest minted wishes </div><br>
            <router-link :to="'/wishingWall'">
                <el-button> Take a tour </el-button>
            </router-link>
            </div>
        </div>


        <div  v-if="isDesktop" style=" color:#262626; text-align: center; width:820px; margin:0 auto; margin-top:50px;">
            <div style=" background:#ffe000; padding:30px; border:2px solid red; width:300px; height:380px;float:left; margin-right:90px; border-radius:20px;">
                <div style="font-size:1.3em;padding:5px; font-weight:300;"> Collect using your wallet </div>
                <img src="../../src/assets/images/connect-cp.png" />
                <div style="font-weight:300; padding:5px; padding-bottom:10px;"> Connect your wallet to fully enjoy Maneki-Meow! </div>
                <el-button style="background:#ff4200;border:1px solid #ff4200; color:white;"  v-on:click="connectWallet()"> Continue with wallet </el-button>
            </div>

            <router-link :to="'/wishingWall'">
            <div style=" background:#f7f7f7; padding:30px; border:1px solid white; width:300px; height:380px;float:left; border-radius:20px;">
                <div style="font-size:1.3em;padding:5px; font-weight:300;"> Explore as guest</div>
                <img src="../../src/assets/images/connect-ncp.png" />
                <div style="font-weight:300; padding:5px; padding-bottom:10px;"> Your information will be locally stored and your experience limited</div>
            </div>
            </router-link>


        </div>
    </div>
</template>

<script>

const { Conflux} = require('js-conflux-sdk');
const conflux = new Conflux({
    url: "https://test.confluxrpc.com",
    networkId: 1,
});


import ConfluxPortalOnboarding from 'conflux-portal-onboarding'

conflux.provider = window.conflux;

const forwarderOrigin = 'https://portal.confluxnetwork.org/'
const onboarding = new ConfluxPortalOnboarding({ forwarderOrigin })


export default {
  data() {
    return {
        isDesktop:true

    };
  },
  mounted() {
        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
            // true for mobile device
            console.log("Mode : mobile");
            this.isDesktop = false;

        }else{
            // false for not mobile device
            console.log("Mode : Desktop MAC");
        }
  },
  methods : {
      connectWallet(){

            if (!ConfluxPortalOnboarding.isConfluxPortalInstalled()) {
                onboarding.startOnboarding()
                console.log ('Click here to install ConfluxPortal!')
                window.location.href = 'https://chrome.google.com/webstore/detail/confluxportal/bjiiiblnpkonoiegdlifcciokocjbhkd?hl=en-GB';
                
            } else if (this.accounts && this.accounts.length > 0) {
                console.log('Connected :', this.accounts)
            } else {
                console.log('Connect')
                window.conflux.enable()
                this.$router.push({path:'/wishingWall'})
            }
          
      }
  }
}
</script>

<style>
  .image {
    width: 100%;
    display: block;
  }
    .sticky-note {
        display: none;
    }


@media only screen and (max-width: 768px) {
  /* For mobile phones: */
    .maneki-logo {
        width:300px;
        margin:0 auto;
        
    }
    .sticky-note {
        display:block;
        width:100%;
        background:#262626;
        margin: 0;
        margin-bottom: 30px;
        border-radius:10px;
    }
    .sticky-note-text{
        padding: 20px;
    }
    .sticky-note img {
        height: 20px;
        padding-left:3px;
    }
}


</style>