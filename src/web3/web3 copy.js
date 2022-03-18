import Web3 from "web3";

const getWeb3 = () =>
new Promise((resolve, reject) => {
  // Wait for loading completion to avoid race conditions with web3 injection timing.
  // Modern dapp browsers...
  const { Conflux,Drip } = require('js-conflux-sdk');

  if (window.conflux) {
    //const web3 = new Web3(window.ethereum);
    const web3 = new Conflux({
      url: "https://test.confluxrpc.com",
      networkId: 1,
    });

    try {
      // Request account access if needed
        window.conflux.enable();
      // Acccounts now exposed
      console.log('Conflux :', web3 );
      
      resolve(web3);
    } catch (error) {
      reject(error);
    }
  }

});

export default getWeb3;