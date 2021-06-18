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
        <div> Token Supply : {{tokenSupply}} <br></div>
        <div> My Walllet address : {{account}}</div>
        <div> Balance : {{balance}}</div>

    </div>
</template>

<script>
export default {
    data(){
        return{
            contractInstance:null,
            account:null,
            isCollapse:false,
            balance:0,
            tokenSupply:0,
            name:null
        }
    }
}
</script>