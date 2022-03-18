const CollectiblePaymentABI =[
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "_collectiblesContract",
        "type": "address"
      }
    ],
    "stateMutability": "nonpayable",
    "type": "constructor"
  },
  {
    "anonymous": false,
    "inputs": [
      {
        "indexed": true,
        "internalType": "contract IERC20",
        "name": "_tokenAddress",
        "type": "address"
      },
      {
        "indexed": true,
        "internalType": "string",
        "name": "_tokenSymbol",
        "type": "string"
      },
      {
        "indexed": false,
        "internalType": "uint256",
        "name": "_tokenQuantity",
        "type": "uint256"
      }
    ],
    "name": "ADDPAYMENTTOKEN",
    "type": "event"
  },
  {
    "anonymous": false,
    "inputs": [
      {
        "indexed": true,
        "internalType": "address",
        "name": "payee",
        "type": "address"
      },
      {
        "indexed": true,
        "internalType": "uint256",
        "name": "_nftID",
        "type": "uint256"
      },
      {
        "indexed": true,
        "internalType": "contract IERC20",
        "name": "token",
        "type": "address"
      },
      {
        "indexed": false,
        "internalType": "uint256",
        "name": "amount",
        "type": "uint256"
      }
    ],
    "name": "PAYMENT",
    "type": "event"
  },
  {
    "anonymous": false,
    "inputs": [
      {
        "indexed": false,
        "internalType": "address",
        "name": "user",
        "type": "address"
      },
      {
        "indexed": false,
        "internalType": "uint256",
        "name": "role",
        "type": "uint256"
      },
      {
        "indexed": false,
        "internalType": "bool",
        "name": "status",
        "type": "bool"
      }
    ],
    "name": "ROLES",
    "type": "event"
  },
  {
    "anonymous": false,
    "inputs": [
      {
        "indexed": true,
        "internalType": "contract IERC20",
        "name": "_tokenAddress",
        "type": "address"
      },
      {
        "indexed": false,
        "internalType": "uint256",
        "name": "_tokenAmount",
        "type": "uint256"
      }
    ],
    "name": "UPDATETOKENRATE",
    "type": "event"
  },
  {
    "anonymous": false,
    "inputs": [
      {
        "indexed": true,
        "internalType": "address",
        "name": "_walletAddress",
        "type": "address"
      },
      {
        "indexed": false,
        "internalType": "uint256",
        "name": "_amount",
        "type": "uint256"
      },
      {
        "indexed": false,
        "internalType": "uint256",
        "name": "_balance",
        "type": "uint256"
      }
    ],
    "name": "WITHDRAWALCOIN",
    "type": "event"
  },
  {
    "anonymous": false,
    "inputs": [
      {
        "indexed": true,
        "internalType": "contract IERC20",
        "name": "_tokenAddress",
        "type": "address"
      },
      {
        "indexed": true,
        "internalType": "address",
        "name": "_walletAddress",
        "type": "address"
      },
      {
        "indexed": false,
        "internalType": "uint256",
        "name": "_amount",
        "type": "uint256"
      },
      {
        "indexed": false,
        "internalType": "uint256",
        "name": "_balance",
        "type": "uint256"
      }
    ],
    "name": "WITHDRAWALTOKEN",
    "type": "event"
  },
  {
    "inputs": [
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "name": "CLevels",
    "outputs": [
      {
        "internalType": "address",
        "name": "CLevelAddress",
        "type": "address"
      },
      {
        "internalType": "uint256",
        "name": "Role",
        "type": "uint256"
      },
      {
        "internalType": "bool",
        "name": "Status",
        "type": "bool"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "name": "ERC20Tokens",
    "outputs": [
      {
        "internalType": "contract IERC20",
        "name": "tokenAddress",
        "type": "address"
      },
      {
        "internalType": "string",
        "name": "tokenSymbol",
        "type": "string"
      },
      {
        "internalType": "uint256",
        "name": "tokenQuantity",
        "type": "uint256"
      },
      {
        "internalType": "bool",
        "name": "tokenStatus",
        "type": "bool"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "_newAddress",
        "type": "address"
      }
    ],
    "name": "addNewCEO",
    "outputs": [],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "_newAddress",
        "type": "address"
      }
    ],
    "name": "addNewCLevel",
    "outputs": [],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "_deactiveAddress",
        "type": "address"
      }
    ],
    "name": "deactiveCLevel",
    "outputs": [],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "_contractAddress",
        "type": "address"
      }
    ],
    "name": "setCollectibleContract",
    "outputs": [],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "inputs": [],
    "name": "totalTokens",
    "outputs": [
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "contract IERC20",
        "name": "_tokenAddress",
        "type": "address"
      },
      {
        "internalType": "string",
        "name": "_tokenSymbol",
        "type": "string"
      },
      {
        "internalType": "uint256",
        "name": "_tokenQuantity",
        "type": "uint256"
      }
    ],
    "name": "addERC20Token",
    "outputs": [
      {
        "internalType": "bool",
        "name": "",
        "type": "bool"
      }
    ],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "contract IERC20",
        "name": "_tokenAddress",
        "type": "address"
      },
      {
        "internalType": "uint256",
        "name": "_tokenQuantity",
        "type": "uint256"
      }
    ],
    "name": "setTokenQuantity",
    "outputs": [
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "contract IERC20",
        "name": "_tokenAddress",
        "type": "address"
      },
      {
        "internalType": "bool",
        "name": "_tokenStatus",
        "type": "bool"
      }
    ],
    "name": "setTokenStatus",
    "outputs": [
      {
        "internalType": "bool",
        "name": "",
        "type": "bool"
      }
    ],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "contract IERC20",
        "name": "_tokenAddress",
        "type": "address"
      },
      {
        "internalType": "uint256",
        "name": "_machine",
        "type": "uint256"
      },
      {
        "internalType": "uint256",
        "name": "_refNekoId",
        "type": "uint256"
      }
    ],
    "name": "paymentByToken",
    "outputs": [],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "uint256",
        "name": "_machine",
        "type": "uint256"
      },
      {
        "internalType": "uint256",
        "name": "_refNekoId",
        "type": "uint256"
      }
    ],
    "name": "paymentByCoin",
    "outputs": [],
    "stateMutability": "payable",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "contract IERC20",
        "name": "_tokenAddress",
        "type": "address"
      },
      {
        "internalType": "address",
        "name": "_recipient",
        "type": "address"
      },
      {
        "internalType": "uint256",
        "name": "_amount",
        "type": "uint256"
      }
    ],
    "name": "withdrawalToken",
    "outputs": [
      {
        "internalType": "address",
        "name": "",
        "type": "address"
      },
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "address payable",
        "name": "_recipient",
        "type": "address"
      },
      {
        "internalType": "uint256",
        "name": "_amount",
        "type": "uint256"
      }
    ],
    "name": "withdrawalCoin",
    "outputs": [],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "contract IERC20",
        "name": "_tokenAddress",
        "type": "address"
      }
    ],
    "name": "checkERC20Balance",
    "outputs": [
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [],
    "name": "currentPrice",
    "outputs": [
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  }
]
export default CollectiblePaymentABI;