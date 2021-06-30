<template>
    <div> 
        <h1> Clubhouse </h1>
        <el-card>
            <div> club mate :</div>
            {{this.clubmate}}
        </el-card>
    </div>
</template>

<script>

import getWeb3 from '../web3/web3';

import abi_clubhouse from '../web3/abi_clubhouse';
const contract_clubhouse = '0x0dB762C6F50fe480B269EBC48bb50F60f7460c58';

export default {
    data(){
        return{
            account:null,
            contract :{
                clubhouse:null
            },
            clubmate :[]
        
        }
    },
    mounted() {
        if (typeof web3 !== 'undefined') {
            console.log('Metamask is installed!');
            getWeb3().then((res) => {
                this.web3 = res;
                //connect Contracts clubhouse, 
                this.contract.clubhouse = new this.web3.eth.Contract(abi_clubhouse, contract_clubhouse);

                //get current signed wallet address
                this.web3.eth.getAccounts().then((accounts) => {
                    [this.account] = accounts;

                this.getClubmate();

                }).catch((err) => {
                    console.log(err, 'err!!');
                });
            });
            } else {
                //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');

            }
    },
    methods :{
        getClubmate (){        
            this.contract.clubhouse.methods.getParticipentsByReferrer(this.account).call({
                from: this.account,
            }).then((res) => {
            this.clubmate = res;
            console.log('List of club mate :', res);

            }).catch((err) => {
                console.log(err, 'err');
            });

        },

    }
}

</script>
