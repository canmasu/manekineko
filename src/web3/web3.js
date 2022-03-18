// get this from 
const {Conflux} = require('js-conflux-sdk');


const getWeb3 = () =>
  new Promise((resolve, reject) => {
    // Wait for loading completion to avoid race conditions with web3 injection timing.
      // Modern dapp browsers...
      if (window.conflux) {
        console.log('window conflux :', window.conflux)
        const provider = new Conflux(window.conflux);

        const web3 = new Conflux(provider);
        try {
          // Request account access if needed
           const cfx = window.conflux.enable();
          // Acccounts now exposed
          console.log('Conflux Network 1:', cfx );
          
          resolve(web3);
          console.log('Conflux web3 :', web3 );
        } catch (error) {
          reject(error);
        }
      }
      // Fallback to localhost; use dev console port by default...
      else {

        const provider = new Conflux({
          url: "https://test.confluxrpc.com",
          networkId: 1,
      });
        const web3 = new Conflux(provider);
        console.log("No web3 instance injected, using Local web3.");
        resolve(web3);
      }

  });

export default getWeb3;