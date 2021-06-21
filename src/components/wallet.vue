<template>
    <div>
        <div> Pass in input: address(wallet) => below details, mainly is TESTIMONAL purpose </div>
        <div> Summary </div>
        <div> BNB Balance , Neko Balance, other accepted coin balance </div>
        <div> Total Maneki $Neko  <br><br><br></div>
        <div> records </div>
        <div> NFT Transcations (the trading record) </div>
        <div> Maneki $Neko (Coins invited record</div>
        <div> My Walllet address : {{account}}</div>




    <el-table  :data="NFTList.filter(data => !search || data.tokenID.includes(search))" stripe style="width: 100%">
        <el-table-column prop="date" label="Date" width="180"> </el-table-column>
        <el-table-column prop="tokenID" label="招き猫 #" width="180"> </el-table-column>
        <el-table-column prop="tokenDNA" label="DNA"> </el-table-column>

        <el-table-column align="right">
            <template  slot="header">
                <el-input v-model="search" size="mini" placeholder="Token ID"/>
            </template>

            <template slot-scope="scope">
                <router-link :to="'/token/' +scope.row.tokenID">
                    <el-button size="mini"> View </el-button>
                </router-link>
                    <el-button size="mini" type="primary"> Sell </el-button>
            </template>

        </el-table-column>   
             
    </el-table>


    </div>
</template>

<script>

import getWeb3 from '../web3/web3';

import abi_collectible from '../web3/abi_nekocollectible';
const contract_collectible = '0x9CCD560e93C2be416edE43C4E97941b7b443b9CE';

export default {
    data(){
        return{
            contractInstance:null,
            account:null,
            isCollapse:false,
            balance:0,
            tokenSupply:0,
            name:null,

            // NFT 
            NFT : {
                owned:0,
                supply:0,
                balance:0
            },
            // date, tokenID, tokenDNA
            NFTList :[],
            search: '',
            contract :{
                collectibles:null
            }

        }
    },
    mounted() {
        if (typeof web3 !== 'undefined') {
    
            console.log('Metamask is installed!');

            getWeb3().then((res) => {
                this.web3 = res;

                //connect Contracts Collectibles, 
                this.contract.collectibles = new this.web3.eth.Contract(abi_collectible, contract_collectible);
                
                //get current signed wallet address
                this.web3.eth.getAccounts().then((accounts) => {
                    [this.account] = accounts;

                this.getNFTOwned();

                }).catch((err) => {
                    console.log(err, 'err!!');
                });
            });
            } else {
                //alert('Wallet not connected! Kindly use Coinbase Wallet or Google Chrome with Metamask Plugin');

            }
    },
    methods :{
        getNFTOwned (){        
            this.contract.collectibles.methods.ownedNekos().call({
                from: this.account,
            }).then((res) => {

            for(let i=0; i< res.length ; i++){
                this.NFTList.push({
                    date: '1st July 2021',
                    tokenID: res[i],
                    tokenDNA: '100 '+ i
                });
            }
            console.log('List of NFT owned :', res);

            }).catch((err) => {
                console.log(err, 'err');
            });

        },
        handleView(index, row) {
            console.log('the index :', index);
            console.log('the row :', row);
        }
    }
}
</script>