// SPDX-License-Identifier: MIT
// BSC 0.V3.01
// input number lucky NFT
// Changed the Maneki Pool relatively to the Royalty distribution

pragma solidity ^0.8.0;

contract Clubhouse {
    /**
     * Clubhouse
     */

    struct Participent {
        address referrerAddr;
    }
    
    mapping (address => Participent) public participents;
    address[] public participentAdds;
    
    
   function setParticipent (address _participentAddr,address _referrerAddr)  public {
        require (participents[_participentAddr].referrerAddr == address(0x0));
        Participent storage participent = participents[_participentAddr];
        participent.referrerAddr = _referrerAddr;
        participentAdds.push(_participentAddr);
    
    }
    
    function getParticipents () public view returns (address [] memory){
        return (participentAdds);
    }
    
    
    function countParticipents () public view returns (uint256){
        return participentAdds.length ;
    }
    
    function getParticipentsByReferrer (address _referrerAddr) public view returns (uint,address[] memory){
        uint count = 0;
        address[] memory _participentAddr = new address[](participentAdds.length);
        for(uint i=0; i<participentAdds.length; i++){
         if(participents[participentAdds[i]].referrerAddr==_referrerAddr){
             _participentAddr[count] = participentAdds[i];
             count++;
         }
        }
        return (count, _participentAddr);
    } 
    function getReferrer (address _participentAddr) public view returns (address) {
        return participents[_participentAddr].referrerAddr;
    }
}