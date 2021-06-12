const Web3 = require("web3");
const ethEnabled = async () => {
  if (window.ethereum) {
    await window.ethereum.send('eth_requestAccounts');
    window.web3 = new Web3(window.ethereum);
    return true;
  }
  alert ('Please allow access for the DApp to work');
  return false;
}