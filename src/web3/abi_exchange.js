const contractAbiExchange = [
	{
		"inputs": [
			{
				"internalType": "contract IERC721",
				"name": "_NFTAddress",
				"type": "address"
			},
			{
				"internalType": "contract IERC20",
				"name": "_NekoAddress",
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
				"internalType": "address",
				"name": "_seller",
				"type": "address"
			},
			{
				"indexed": true,
				"internalType": "address",
				"name": "_buyer",
				"type": "address"
			},
			{
				"indexed": true,
				"internalType": "uint256",
				"name": "_tokenID",
				"type": "uint256"
			},
			{
				"indexed": false,
				"internalType": "uint256",
				"name": "price",
				"type": "uint256"
			}
		],
		"name": "BUY",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"internalType": "address",
				"name": "_seller",
				"type": "address"
			},
			{
				"indexed": false,
				"internalType": "uint256",
				"name": "_tokenID",
				"type": "uint256"
			},
			{
				"indexed": false,
				"internalType": "uint256",
				"name": "price",
				"type": "uint256"
			}
		],
		"name": "OFFER",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"internalType": "address",
				"name": "_seller",
				"type": "address"
			},
			{
				"indexed": false,
				"internalType": "uint256",
				"name": "_tokenID",
				"type": "uint256"
			},
			{
				"indexed": false,
				"internalType": "uint256",
				"name": "price",
				"type": "uint256"
			}
		],
		"name": "UPDATEPRICE",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"internalType": "address",
				"name": "_seller",
				"type": "address"
			},
			{
				"indexed": false,
				"internalType": "uint256",
				"name": "_tokenID",
				"type": "uint256"
			}
		],
		"name": "WITHDRAW",
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
		"name": "Deals",
		"outputs": [
			{
				"internalType": "address",
				"name": "Seller",
				"type": "address"
			},
			{
				"internalType": "address",
				"name": "Buyer",
				"type": "address"
			},
			{
				"internalType": "uint256",
				"name": "TokenID",
				"type": "uint256"
			},
			{
				"internalType": "uint256",
				"name": "Price",
				"type": "uint256"
			},
			{
				"internalType": "bool",
				"name": "Currency",
				"type": "bool"
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
				"name": "_tokenID",
				"type": "uint256"
			}
		],
		"name": "buy",
		"outputs": [
			{
				"internalType": "uint256",
				"name": "",
				"type": "uint256"
			}
		],
		"stateMutability": "payable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_tokenID",
				"type": "uint256"
			},
			{
				"internalType": "uint256",
				"name": "_Price",
				"type": "uint256"
			},
			{
				"internalType": "bool",
				"name": "_Currency",
				"type": "bool"
			}
		],
		"name": "offer",
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
		"inputs": [],
		"name": "totalDeals",
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
				"internalType": "uint256",
				"name": "_tokenID",
				"type": "uint256"
			},
			{
				"internalType": "uint256",
				"name": "_price",
				"type": "uint256"
			}
		],
		"name": "updatePrice",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_tokenID",
				"type": "uint256"
			}
		],
		"name": "withdraw",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	}
]
export default contractAbiExchange;