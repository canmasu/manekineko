// SPDX-License-Identifier: MIT

// input number lucky NFT
// Changed the Maneki Pool relatively to the Royalty distribution

pragma solidity ^0.8.0;

import "@openzeppelin/contracts@4.1.0/token/ERC721/ERC721.sol";
import "@openzeppelin/contracts@4.1.0/utils/math/SafeMath.sol";
import "@openzeppelin/contracts@4.1.0/utils/Strings.sol";
import "@openzeppelin/contracts@4.1.0/token/ERC20/IERC20.sol";


contract NekoCollectibles is ERC721 {
    using SafeMath for uint256;
    /**
     * Struct Clevel for authorize person to execute some restricted functions
     * Role : 1 as CEO, 2 as Management Team OR Premitted Contract 
     * Status : 1 as Active , 0 as inactive
     *
     * Struct Neko for NFT Token
     * Power : Maneki power for invite lucky coins
     * refCount : every neko eligible to refer up to 5 times
     *
     * Stuct referral
     * Credit : use to redeem free neko
     *
     */

    struct CLevel {
        address CLevelAddress;
        uint256 Role;
        bool Status;
    }

    struct Neko {
        uint256 power;
        uint256 DNA;
        uint256 refCount;
        uint256 gammaNekoID;
    }

    struct referral {
        uint256 credit;
    }

    /**
     * List of existing CLevel
     * List of exisiting Neko
     * 
     */

    CLevel[] public CLevels;
    Neko[] public Nekos;
    //string public baseURI = 'http://metadata.neko.exchange/';
    
    
    using Strings for uint256;
        
    // Optional mapping for token URIs
    mapping (uint256 => string) private _tokenURIs;
    
    
    // Base URI
    string private _baseURIextended;
    
    
    /**
     * numberOfLuckyNeko the total no. of lucky collectible
     * royaltyAmount the total fund use to split for total collectibles
     * Lucky Nekos      3
     * royalty 10%      10**16
     * Incentive 20%    2*10**16
     * bonus 10%        10**16
     */
    
    
    IERC20  manekiTokenAddress;
    uint256 numberOfLuckyNeko;
    uint256 royaltyAmount;
    uint256 incentiveAmount;
    uint256 bonusAmount;
    
    // Initial Collectibles Offering
    bool    ICO = true;


    /**
     * Initializing an ERC-721 Token named 'Nekos' with a symbol 'NEKO'
     *
     */

    constructor() ERC721("Neko Collectibles", "NCX") public {
        CLevel memory newCLevel = CLevel({
            CLevelAddress : msg.sender,
            Role : 1,
            Status : true
        });
        CLevels.push(newCLevel);
    }


    modifier onlyCEO {
        address authorized;

        for(uint i=0; i<CLevels.length; i++){
            if(CLevels[i].CLevelAddress== msg.sender && CLevels[i].Role==1 && CLevels[i].Status == true){
                authorized = CLevels[i].CLevelAddress;
            }
        }
        require(msg.sender == authorized);
        _;
    }

    modifier onlyCLevel {
        address authorized;

        for(uint i=0; i<CLevels.length; i++){
            if(CLevels[i].CLevelAddress== msg.sender){
                authorized = CLevels[i].CLevelAddress;
            }
        }
        require(msg.sender == authorized);
        _;
    }

    mapping (address => referral) public referrals;



    /**
     * BIRTH - a new Neko is created
     * WITHDRAWAL - a withdraw is made
     * LUCKY_COINS - a lucky coin is invited
     * REWARD - a credit recieved from the referral made
     * REDEMPTION - a neko is redeem
     * INCENTIVE - incentive sent to premium collector
     * ROLES - a new user is add or updated
     */
     
    event BIRTH (address owner, uint256 NekoId, uint256 power, uint256 DNA);
    event WITHDRAWAL (address indexed payee, uint256 amount, uint256 balance);
    event LUCKY_COINS (address indexed luckyWallet, uint256 indexed luckyNeko, uint256 amount, uint256 timestamp);
    event REWARD (address indexed payee, uint256 refNekoId, uint256 newNeko, uint256 credit, uint256 timestamp);
    event REDEMPTION (address indexed payee, uint256 freeNekoID, uint256 credit, uint256 timestamp);
    event INCENTIVE (address indexed payee, uint256 refNekoId, uint256 amount, uint256 timestamp);
    event BONUS (address indexed payee, uint256 gammaNekoID, uint256 amount, uint256 timestamp);
    event ROLES (address user, uint256 role, bool status);


    function addNewCEO (address _newAddress) external onlyCEO{
        CLevel memory newCLevel = CLevel({
            CLevelAddress : _newAddress,
            Role : 1,
            Status : true
        });
        CLevels.push(newCLevel);
        emit ROLES (_newAddress,1,true);
    }

    function addNewCLevel (address _newAddress) external onlyCEO{
        CLevel memory newCLevel = CLevel({
            CLevelAddress : _newAddress,
            Role : 2,
            Status : true
        });
        CLevels.push(newCLevel);
        emit ROLES (_newAddress,2,true);
    }

    function deactiveCLevel (address _deactiveAddress) external onlyCEO{
        for(uint i=0; i<CLevels.length; i++){
            if(CLevels[i].CLevelAddress== _deactiveAddress){
                CLevels[i].Status = false;
                emit ROLES (_deactiveAddress,CLevels[i].Role,false);
            }
        }
    }
    /**
     * mintRootNeko  - onlyCLevel able to mint
     * 1st Generation as alpha and 2nd Generation as beta
     * default refCount = 5
     * 3rd Generation as gamma, 4th Generation as Delta and 5th Generation as Epsilon
     * default refCount = 0
     * 0.0 ETH
     *
     * mintLuckyNeko - everyone able to mint
     * start from 6th Generation
     * 0.1 ETH
     *
     * mintFreeNeko - only use credit to mint
     * start from 6th Generation
     * 0.1 ETH
     *
     * kickstart Neko #0 will be generated by onwer
     *
     * mintRootNeko
     * Alpha , Bata NOT able to be referral
     * Gamma and above able to have 5 referral
     *
     */

    function mintRootNeko(uint256 _machine, uint256 _generation) external onlyCLevel() {

        require (_generation<4);
            
        uint256 DNA;
        uint256 manekiPower;
        (manekiPower,DNA)  = nekoDNA(_machine, _generation);
            
            
        if(_generation<3){
            createNeko(msg.sender, manekiPower, DNA, 5, 0);
        } else {
            createNeko(msg.sender, manekiPower, DNA, 0, 0);
        }
    }


    
    /**
     * For call to Mint with ERC20 Token 
     * 
     * _refNekoId = 0 mean is direct from Dapp
     * _refNekoId > 0 mean is by referral
     *
     * verify refCount of the referral neko
     * Qualified : (refCount not more then 5 times refering)
     * New Neko Generation = refNeko's generation + 1
     * verify referral is a premiumCollectorIncentive
     *
     * Default generation 6, if no referral
     *
     */
     
    function mintCollectible (address _buyer, uint256 _machine, uint256 _refNekoId) external onlyCLevel(){
        
        if( ICO == false ) {
            require (_buyer == ownerOf(_refNekoId));
        }
        
        uint256 DNA;
        uint256 manekiPower;
        uint256 generation;
        uint256 _newNeko;
        uint256 _newGammaNekoID;

        Neko storage NEKO = Nekos[_refNekoId];


        if (_refNekoId>0 && NEKO.refCount<5){
            generation = NEKO.DNA.div(10**22).mod(1000000);

            if(generation==3 && generation >=3){
                _newGammaNekoID = _refNekoId;
            } else {
                _newGammaNekoID = NEKO.gammaNekoID;
            }

            generation += 1;
            NEKO.refCount += 1;
            Nekos[_refNekoId].refCount = NEKO.refCount;
            premiumCollectorIncentive(_refNekoId);
        } else {
            generation = 6;
            _newGammaNekoID = 0;
        }

        (manekiPower,DNA)  = nekoDNA(_machine, generation);
        createNeko(_buyer, manekiPower, DNA, 0, _newGammaNekoID);
        luckyCoin();

        if(_refNekoId > 0){
            address refOwner = ownerOf(_refNekoId);
            _newNeko = referrals[refOwner].credit += 1;
            emit REWARD (refOwner, _refNekoId, _newNeko, 1, block.timestamp);
        }

        // Bonus for GammaNeko
        if (_newGammaNekoID != 0){
            gammaCollectorBonus(_newGammaNekoID);
        }   
    }


    /**
     * 3 credits required to redeem 1 neko
     */

    function mintFreeNeko(uint256 _machine) external {
        require (referrals[msg.sender].credit >= 3);
            
        uint256 newNekoID;
        uint256 DNA;
        uint256 manekiPower;
        (manekiPower,DNA)  = nekoDNA(_machine, 6);
        newNekoID = createNeko(msg.sender, manekiPower, DNA, 0, 0);
        referrals[msg.sender].credit -= 3;
        
        emit REDEMPTION (msg.sender, newNekoID, 3, block.timestamp);

    }



    function createNeko(address _owner, uint256 _power, uint256 _DNA, uint256 _refCount, uint256 _gammaNekoID) internal returns (uint256){

        require(_owner != address(0));

        Neko memory newNeko = Neko({
            power       : _power,
            DNA         : _DNA,
            refCount    : _refCount,
            gammaNekoID : _gammaNekoID
        });

        Nekos.push(newNeko);
        uint256 newNekoId = Nekos.length-1;
        super._mint(_owner, newNekoId);

        emit BIRTH (_owner,newNekoId, newNeko.power, newNeko.DNA);
        return newNekoId;
    }

    function nekoDNA(uint256 _machine, uint256 _generation) internal view returns ( uint256, uint256){

       uint256 DNA;
       uint256 manekiPower;
       uint256[] memory generateDNA = new uint256[](5);

        /**
        *  1D = ESCAPE
        *  5D = WEALTH
        *  5D = OPPORTUNITY
        *  5D = RELATIONSHIP
        *  5D = WISDOM
        *  5D = HEALTH
        *  --> 26D
        *  6D = MANEKIPOWER
        *  --> 32D
        *
        *  6D = GENERATION
        *  --> 38D
        *
        *  3D = ORIGIN
        *  4D = PAINTER : Source Code that use to generate art work
        *  4D = SERIES  : New Designs added by batch
        *  11D = BIRTHDAD
        *  -->  22D
        */

       uint256 energies = uint256(keccak256(abi.encodePacked(block.timestamp, block.difficulty.add(_machine)))).mod(10**26);
 
       if( energies <=  10**25){
           energies += 10**25;
        }

        generateDNA[0] = energies.div(10**20).mod(100000);
        generateDNA[1] = energies.div(10**15).mod(100000);
        generateDNA[2] = energies.div(10**10).mod(100000);
        generateDNA[3] = energies.div(10**5).mod(100000);
        generateDNA[4] = energies.mod(10**5);

        manekiPower = generateDNA[0] + generateDNA[1] + generateDNA[2] + generateDNA[3] + generateDNA[4];

        DNA = energies;
        
        DNA = DNA.mul(10**6).add(manekiPower);
        DNA = DNA.mul(10**6).add(_generation);
        DNA = DNA.mul(10**22).add(_machine);

        return (manekiPower,DNA);
   }

    /**
     * Lucky coins splited to 10 portions accordingly to Maneki Power
     * Higher Maneki Power will receive more lucky coins
     *
     */

    function luckyCoin() internal returns(bool){
        address payable luckyWallet;
        address payable ownerWallet;
        
 
        uint256[] memory _nekoId = new uint256[](10);
        uint256[] memory _nekoPower = new uint256[](10);
        uint256 _sumPower = 0;

        uint256 _amount;
    

        uint256 random = uint256(keccak256(abi.encodePacked(block.timestamp, block.difficulty)));
        uint256 factor = 1;

        for (uint i = 0; i < numberOfLuckyNeko; i++) {
            
          // shift by factor and trim the Random number
          // Modular the trimed random number with total Neko Supply
          factor = factor.mul(10);
          _nekoId[i] = random.div(factor).mod(Nekos.length);

          Neko storage NEKO = Nekos[_nekoId[i]];
          _nekoPower[i] = NEKO.power;

          _sumPower = _sumPower.add(NEKO.power);

        }

        for (uint j = 0; j < numberOfLuckyNeko; j++) {

          ownerWallet = payable(ownerOf(_nekoId[j]));
          luckyWallet = ownerWallet;

          _nekoPower[j] = _nekoPower[j].mul(royaltyAmount);

          _amount = _nekoPower[j].div(_sumPower);
          //_amount = _amount.mul(royaltyAmount);
          
          // Tranfers Maneki Coins to the lucky neko
          manekiPayout (address(this), luckyWallet, _amount);
          
        
          emit LUCKY_COINS (luckyWallet, _nekoId[j], _amount, block.timestamp);
        }

        return  true;
    }

    /**
     * Owned 10 neko to be promoted as Premium Collector
     * Premium collector eligible to get 0.018 ETH incentive for each referral
     *
     */

    function premiumCollectorIncentive(uint256 _refNekoId) internal {
        if(balanceOf(ownerOf(_refNekoId))>=10){
            address payable _payee;
            uint256 _amount = incentiveAmount;
            _payee = payable(ownerOf(_refNekoId));
            
            // Tranfers ERC20 Token to Premium Collector 
            manekiPayout (address(this), _payee, _amount);
            emit INCENTIVE (_payee, _refNekoId, _amount, block.timestamp);
 
        }
    }
    
    
    /**
     * if the referred from a Gamma Neko
     * Gamma collector eligible to get 0.01 ETH Bonus for each referral
     *
     */
    function gammaCollectorBonus(uint256 _GammaNekoID) internal{
            address payable _payee;
            uint256 _amount = bonusAmount;
            _payee = payable(ownerOf(_GammaNekoID));
            
            // Tranfers ERC20 Token to Premium Collector 
            manekiPayout (address(this), _payee, _amount);

            emit BONUS (_payee, _GammaNekoID, _amount, block.timestamp);

    }

    function manekiPayout(address _contractAdds, address payable _payee, uint256 _amount) internal{
        
            manekiTokenAddress.approve(_contractAdds,_amount);
            manekiTokenAddress.allowance(_contractAdds,_payee);
            manekiTokenAddress.transferFrom(_contractAdds, _payee, _amount);
    }

    function rewardCredit() external view returns (uint){
            return (referrals[msg.sender].credit);
    }

    /**
     * etrieve a specific Neko's details.
     * NekoId ID of the Neko who's details will be retrieved
     *
     */

    function getNekoDetails(uint256 NekoId) public view returns (uint256, uint256, uint256, uint256) {
        Neko storage NEKO = Nekos[NekoId];
        return (NekoId, NEKO.power, NEKO.DNA, NEKO.refCount);
    }

    /**
     * get a list of owned Nekos' IDs
     *
     */
    function ownedNekos() external view returns(uint256[] memory) {
        uint256 NekoCount = balanceOf(msg.sender);
        if (NekoCount == 0) {
            return new uint256[](0);
        } else {
            uint256[] memory result = new uint256[](NekoCount);
            uint256 totalNekos = Nekos.length;
            uint256 resultIndex = 0;
            uint256 NekoId = 0;
            while (NekoId < totalNekos) {
                if (ownerOf(NekoId) == msg.sender) {
                    result[resultIndex] = NekoId;
                    resultIndex = resultIndex.add(1);
                }
                NekoId = NekoId.add(1);
            }
            return result;
        }
    }

    /**
    * send / withdraw _amount to _payee onlyCLevel
    *
    */

    function withdrawal(address payable _payee, uint256 _amount) external onlyCLevel {
        require(_payee != address(0) && _payee != address(this));
        require(_amount > 0 && _amount <= address(this).balance);
        _payee.transfer(_amount);
        emit WITHDRAWAL (_payee, _amount, address(this).balance);
    }
    
    function totalSupply() external view returns (uint){
        return Nekos.length;
    }

    
    function setBaseURI(string memory baseURI_) external onlyCLevel() {
        _baseURIextended = baseURI_;
    }
        
    function _setTokenURI(uint256 tokenId, string memory _tokenURI) internal virtual {
        require(_exists(tokenId), "ERC721Metadata: URI set of nonexistent token"); 
        _tokenURIs[tokenId] = _tokenURI;
    }
        
    function _baseURI() internal view virtual override returns (string memory) {
        return _baseURIextended;
    }    
    
    
    /**
     * set ManekiToken 
     * 1. Maneki Token address
     * 2. Maneki Token amount
     * 3. number of lucky Neko
     */
     
     function setManekiToken (IERC20 _manekiTokenAddress, uint256 _numberOfLuckyNeko, uint256 _royaltyAmount, uint256 _incentiveAmount, uint256 _bonusAmount)  external onlyCLevel(){
         manekiTokenAddress = _manekiTokenAddress;
         numberOfLuckyNeko  = _numberOfLuckyNeko;
         royaltyAmount      = _royaltyAmount;
         incentiveAmount    = _incentiveAmount;
         bonusAmount        = _bonusAmount;
     }
     
     function setICO (bool _status) external onlyCLevel() returns (bool) {
         return ICO = _status;
     }
     
}
