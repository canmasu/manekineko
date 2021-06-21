// SPDX-License-Identifier: MIT

// Trading Currency True. ETH AND False. NEKO
// Zero Transaction Fee with $NEKO
// Transaction Fee for ETh deal, with the retrun of $NEKO


// multi-payment

pragma solidity ^0.8.0;

import "@openzeppelin/contracts@4.1.0/token/ERC721/IERC721.sol";
import "@openzeppelin/contracts@4.1.0/token/ERC20/IERC20.sol";

contract Exchange {

    constructor (IERC721 _NFTAddress, IERC20 _NekoAddress) public {
        NFTAddress  = _NFTAddress;
        NEKOAddress = _NekoAddress;
    }

    struct Deal { 
        address Seller;
        address Buyer;
        uint256 TokenID;
        uint256 Price;
        bool    Currency;
        bool    Status;
    }
    Deal[] public Deals;
    IERC721 NFTAddress;
    IERC20  NEKOAddress;

    
    
    event BUY        (address indexed _seller, address indexed _buyer, uint256 indexed _tokenID, uint256 price);
    event OFFER      (address indexed _seller, uint256 _tokenID, uint256 price);
    event WITHDRAW   (address indexed _seller, uint256 _tokenID);
    event UPDATEPRICE(address indexed _seller, uint256 _tokenID, uint256 price);

    function offer (uint256 _tokenID, uint256 _Price, bool _Currency) external returns (bool){
        Deal memory newDeal = Deal({
            Seller   : msg.sender,
            Buyer    : msg.sender,
            TokenID  : _tokenID,
            Price    : _Price,
            Currency : _Currency,
            Status   : true
        });
        Deals.push(newDeal);
    
        _escrow(_tokenID);
        emit OFFER (msg.sender,_tokenID,_Price);
        return true;
    }

    function _escrow (uint256 _tokenID) internal {
        address from = msg.sender;
        NFTAddress.transferFrom(from,address(this),_tokenID);
    }
    
    function _unescrow (uint256 _tokenID) internal {
        address to = msg.sender;
        NFTAddress.approve(to, _tokenID);
        NFTAddress.transferFrom(address(this),to,_tokenID);
    }
    
    function withdraw (uint256 _tokenID) external {
        
        for (uint i=0 ; i<Deals.length ; i++){  
            if (Deals[i].TokenID == _tokenID && Deals[i].Status == true){ 
                require (msg.sender == Deals[i].Seller);
                _unescrow;
                Deals[i].Status = false;
                emit WITHDRAW (msg.sender,_tokenID);
            }
        }
    }
    
    function updatePrice (uint256 _tokenID, uint256 _price ) external {
        
        for (uint i=0 ; i<Deals.length ; i++){  
            if (Deals[i].TokenID == _tokenID && Deals[i].Status == true){ 
                require (msg.sender == Deals[i].Seller);
                Deals[i].Price = _price;
                emit UPDATEPRICE (Deals[i].Seller,_tokenID,_price);
            }
        }
    }
    
    function buy (uint256 _tokenID) payable external returns (uint256){
        
        for (uint i=0 ; i<Deals.length ; i++){  
            if (Deals[i].TokenID == _tokenID && Deals[i].Status == true){ 

                address buyer = msg.sender;

                if (Deals[i].Currency == true ){
                    // trade with ETH
                    payable(Deals[i].Seller).transfer(Deals[i].Price);
                } else {
                    // TRADE with $NEKO
                    // PRE Approve and Allowance , than only execute transferFrom
                    NEKOAddress.transferFrom(buyer, Deals[i].Seller , Deals[i].Price);
                }

                // Transfer NFT to Buyer Wallet
                NFTAddress.approve(buyer, _tokenID);
                NFTAddress.transferFrom(address(this),buyer,_tokenID);
                Deals[i].Status = false;
                
                emit BUY (Deals[i].Seller,buyer,_tokenID,Deals[i].Price);
            }
        }
        return _tokenID;
    } 
    
    function totalDeals () external view returns (uint256) {
        return Deals.length;
    }
    


}