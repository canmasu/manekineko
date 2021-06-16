// SPDX-License-Identifier: MIT

// input number lucky NFT
// Changed the Maneki Pool relatively to the Royalty distribution
// Zero Transaction Fee

// multi-payment

pragma solidity ^0.8.0;

import "@openzeppelin/contracts@4.1.0/token/ERC721/IERC721.sol";

contract Exchange {

    constructor (IERC721 _contractAddress) public {
        NFTAddress = _contractAddress;
    }

    struct Deal { 
        address Seller;
        uint256 TokenID;
        uint256 Price;
        bool    Status;
    }
    Deal[] public Deals;
    IERC721 NFTAddress;
    
    event TRANSACTED (address indexed _seller, address indexed _buyer, uint256 indexed _tokenID, uint256 price);
    event SALES      (address indexed _seller, uint256 _tokenID, uint256 price);
    event WITHDRAW   (address indexed _seller, uint256 _tokenID);
    event UPDATEPRICE(address indexed _seller, uint256 _tokenID, uint256 price);

    function _sale (uint256 _tokenID, uint256 _Price) external returns (bool){
        Deal memory newDeal = Deal({
            Seller : msg.sender,
            TokenID  : _tokenID,
            Price    : _Price,
            Status   : true
        });
        Deals.push(newDeal);
    
        _escrow(_tokenID);
        emit SALES (msg.sender,_tokenID,_Price);
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
    
    function _withdraw (uint256 _tokenID) external {
        
        for (uint i=0 ; i<Deals.length ; i++){  
            if (Deals[i].TokenID == _tokenID && Deals[i].Status == true){ 
                require (msg.sender == Deals[i].Seller);
                _unescrow;
                Deals[i].Status = false;
                emit WITHDRAW (msg.sender,_tokenID);
            }
        }
    }
    
    function _updatePrice (uint256 _tokenID, uint256 _price ) external {
        
        for (uint i=0 ; i<Deals.length ; i++){  
            if (Deals[i].TokenID == _tokenID && Deals[i].Status == true){ 
                require (msg.sender == Deals[i].Seller);
                Deals[i].Price = _price;
                emit UPDATEPRICE (Deals[i].Seller,_tokenID,_price);
            }
        }
    }
    
    function _buy (uint256 _tokenID) payable external returns (uint256){
        
        for (uint i=0 ; i<Deals.length ; i++){  
            if (Deals[i].TokenID == _tokenID && Deals[i].Status == true){ 
                require (msg.value ==  Deals[i].Price);
                address to = msg.sender;
                NFTAddress.approve(to, _tokenID);
                NFTAddress.transferFrom(address(this),to,_tokenID);
                Deals[i].Status = false;
                emit TRANSACTED (address(this),to,_tokenID,Deals[i].Price);
            }
        }
        return _tokenID;
    } 
    
    function _totalDeals () external view returns (uint256) {
        return Deals.length;
    }
    
    function _getDeal (uint256 _dealID) external view returns(address,uint256,uint256,bool){
        return (Deals[_dealID].Seller,Deals[_dealID].TokenID,Deals[_dealID].Price,Deals[_dealID].Status);
        
    }


}