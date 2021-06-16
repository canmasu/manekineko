<template>
    <div>
        <div> Pass in input: address(wallet) => below details, mainly is TESTIMONAL purpose </div>
        <div> Summary </div>
        <div> BNB Balance , Neko Balance, other accepted coin balance </div>
        <div> Total Maneki $Neko  <br><br><br></div>
        <div> records </div>
        <div> NFT Transcations (the trading record) </div>
        <div> Maneki $Neko (Coins invited record</div>
        <div> Token Name : {{name}}</div>
        <div> Balance : {{balance}}</div>
    </div>
</template>

<script>

import contractAbiExchange from '../contract/abi_exchange';
//import getWeb3 from '../contract/web3';
// Safemoon contract
const contractAddress = '0xdF3CF86Faed8a1936F3dB48a374E981e3fFC3164';

export default {
    data(){
        return{
            contractInstance:null,
            account:null,
            isCollapse:false,
            balance:0,
            name:null
        }
    },
    mounted(){
        const Web3 = require('web3');
        const web3 = new Web3(window.ethereum);
        //const loader = setupLoader({ provider: web3 }).web3;
        this.contractInstance = new web3.eth.Contract(contractAbiExchange,contractAddress);

        web3.eth.getAccounts().then((accounts) => {
            [this.account] = accounts;
            this.getBalance();
            this.getTokenName();
        }).catch((err) => {
            console.log(err, 'err!!');
        });

        //web3.eth.getBalance("0xebF1f5cfa240C1101f461ed1a33c1c00058fe9FD").then(console.log);
       
    },
    methods : {
        getBalance(){
            this.contractInstance.methods.totalSupply().call({
            }).then((res) => {
                this.balance = res;
            }).catch((err) => {
                console.log(err, 'err');
            });
        },
        getTokenName(){
            this.contractInstance.methods.name().call({
            }).then((res) => {
                this.name = res;
            }).catch((err) => {
                console.log(err, 'err');
            });
        }
    }
}
</script>