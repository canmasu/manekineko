<template>
    <div> 
        <h1> Clubhouse </h1>
        <el-card>
            <div>Members:</div>
            <el-table :data="members" stripe style="width: 100%">
                    <el-table-column prop="address" label="Wallet Address" > </el-table-column>
                    <el-table-column prop="balance" label="Minted" > </el-table-column>   
            </el-table>

        </el-card>
    </div>
</template>

<script>

import getWeb3 from '../web3/web3';

import abi_clubhouse from '../web3/abi_clubhouse';
const contract_clubhouse = '0x9Da0Db278C51d425E3d31Fe92DAAE3B91D016FB9';

import abi_collectible from '../web3/abi_collectible';
const contract_collectible = '0x8e2F7e97f07bF6454a62FAECb4402A62B7C57e22';

export default {
    data(){
        return{
            account:null,
            contract :{
                clubhouse:null
            },
            members :[]
        
        }
    },
    mounted() {
        if (typeof web3 !== 'undefined') {
            console.log('Metamask is installed!');
            getWeb3().then((res) => {
                this.web3 = res;
                //connect Contracts clubhouse, 
                this.contract.clubhouse = new this.web3.eth.Contract(abi_clubhouse, contract_clubhouse);

                //connect Contracts Collectibles, 
                this.contract.collectibles = new this.web3.eth.Contract(abi_collectible, contract_collectible);

                //get current signed wallet address
                this.web3.eth.getAccounts().then((accounts) => {
                    [this.account] = accounts;

                this.getMembers();

                }).catch((err) => {
                    console.log(err, 'err!!');
                });
            });
            } else {
                //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');

            }
    },
    methods :{
        getMembers (){        

            this.contract.clubhouse.methods.getMembers().call({
                from: this.account,
            }).then((res) => {

            for(let i=0; i< res[0].length ; i++){
                if( res[0][i]!= '0x0000000000000000000000000000000000000000'){

                    this.contract.collectibles.methods.balanceOf(res[0][i]).call({
                        from: this.account,
                    }).then((bal) => {

                        this.members.push({
                            address: res[0][i],
                            balance: bal
                        });

                    })
                }
            }
            }).catch((err) => {
                console.log(err, 'err');
            });

        }

    }
}

</script>
