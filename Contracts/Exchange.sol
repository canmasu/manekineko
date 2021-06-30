// SPDX-License-Identifier: MIT
// BSC 0.V3.01
// Trading Currency True. ETH AND False. NEKO
// Zero Transaction Fee with $NEKO
// Transaction Fee for ETh deal, with the retrun of $NEKO


// multi-payment

pragma solidity ^0.8.0;

import "@openzeppelin/contracts@4.1.0/token/ERC721/IERC721.sol";
import "@openzeppelin/contracts@4.1.0/token/ERC20/IERC20.sol";
import "./Collectibles.sol";

contract Exchange {

    constructor (IERC721 _NFTAddress, IERC20 _NekoAddress, address _collectiblesContract) public {
        NFTAddress           = _NFTAddress;
        NEKOAddress          = _NekoAddress;
        collectiblesContract = _collectiblesContract;
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

    
    address collectiblesContract;
    
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
    
    function withdraw (uint256 _dealID, uint256 _tokenID) external {
        require (Deals[_dealID].TokenID == _tokenID && Deals[_dealID].Status == true && msg.sender == Deals[_dealID].Seller);
        _unescrow;
        Deals[_dealID].Status = false;
        emit WITHDRAW (msg.sender,_tokenID);
    }
    
    function updatePrice (uint256 _dealID, uint256 _tokenID, uint256 _price ) external {
        
        require(Deals[_dealID].TokenID == _tokenID && Deals[_dealID].Status == true && msg.sender == Deals[_dealID].Seller);
        Deals[_dealID].Price = _price;
        emit UPDATEPRICE (Deals[_dealID].Seller,_tokenID,_price);
    }
    
    
    // if without _referrerAddr = 0x0, mean no referrer 
    function buy_byETH (uint256 _dealID, uint256 _tokenID, address _referrerAddr) payable external returns (uint256){
        // buy NFT pay by ETH
        require(Deals[_dealID].TokenID == _tokenID &&Deals[_dealID].Status == true && Deals[_dealID].Currency== true);
        
        // Pay with ETH
        payable(Deals[_dealID].Seller).transfer(Deals[_dealID].Price);
            
        // Transfer NFT to Buyer Wallet
        transferNFT(msg.sender,_tokenID);
                
        // update Deals status and buyer to inactive
        Deals[_dealID].Buyer = msg.sender;
        Deals[_dealID].Status = false;
        
        // signup clubhouse
        if(_referrerAddr!=address(0x0)) {
            signupClubhouse (msg.sender , _referrerAddr);
        }
                
        emit BUY (Deals[_dealID].Seller,msg.sender,_tokenID,Deals[_dealID].Price);

        return _tokenID;
    } 
    
    
    function buy_byERC20 (uint256 _dealID, uint256 _tokenID, address _referrerAddr) external returns (uint256){
        // buy NFT pay by ERC20 token
        require(Deals[_dealID].TokenID == _tokenID &&Deals[_dealID].Status == true && Deals[_dealID].Currency== false);

        // Pay with $NEKO
        // PRE Approve and Allowance , than only execute transferFrom
        NEKOAddress.transferFrom(msg.sender, Deals[_dealID].Seller,  Deals[_dealID].Price);

        // Transfer NFT to Buyer Wallet
        transferNFT(msg.sender,_tokenID);
                
        // update Deals status and buyer to inactive
        Deals[_dealID].Buyer = msg.sender;
        Deals[_dealID].Status = false;
        
        // signup clubhouse
        if(_referrerAddr!=address(0x0)) {
            signupClubhouse (msg.sender , _referrerAddr);
        }
                
        emit BUY (Deals[_dealID].Seller, msg.sender, _tokenID,Deals[_dealID].Price);
        
        return _tokenID;
    } 
    function signupClubhouse (address _participentAddr,address _referrerAddr) internal {
        NekoCollectibles Contract = NekoCollectibles(collectiblesContract);

        if (Contract.getReferrer(receiverAddrs) == address(0x0)) {
            Contract.setParticipent(_participentAddr,_referrerAddr);
        }
    }
    
    function transferNFT (address _buyer, uint256 _tokenID) internal {
        // Transfer NFT to Buyer Wallet
        NFTAddress.approve(_buyer, _tokenID);
        NFTAddress.transferFrom(address(this),_buyer,_tokenID);
    }
    
    
    function totalDeals () external view returns (uint256) {
        return Deals.length;
    }
    
}