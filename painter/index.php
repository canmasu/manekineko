<?php
// required headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


/**
 * 
 * 
 * 1 Create Metadata .json /metadata  https://metadata.neko.exchange/ID
 * 2 Create Maneki-Meow .GIF /image   https://collectible.neko.exchange/ID
 * 
 * 
 * Origin   : 000
 * Painter  : 0001
 * Series 1 : 0001 
 */

$_POST = json_decode(file_get_contents("php://input"),true);

// required 3 input. DNA , Wish and NFT_Id
$energies = $_POST['DNA'];
$wish     = $_POST['wish'];
$nftID    = $_POST['nftID'];

$NFT -> ArtPiece    = (int)substr($energies, 1,1);      // 1D = ARTPIECE
$NFT -> isPainting  = (int)substr($energies, 9,4);      // 1D = isPAINTING
$NFT -> Painting    = (int)substr($energies, 7,1);      // 1D = PAINTING
$NFT -> Bgcolour    = (int)substr($energies, 59,1);     // 1D = PAINTING
$NFT -> Wealth      = (int)substr($energies, 1,5);      // 5D = E1 WEALTH
$NFT -> Opportunity = (int)substr($energies, 6,5);      // 5D = E2 OPPORTUNITY
$NFT -> Relationship= (int)substr($energies, 11,5);     // 5D = E3 RELATIONSHIP
$NFT -> Wisdom      = (int)substr($energies, 16,5);     // 5D = E4 WISDOM
$NFT -> Health      = (int)substr($energies, 21,5);     // 5D = E5 HEALTH
$NFT -> ManekiPower = (int)substr($energies, 26,6);     // 6D = MANEKIPOWER
$NFT -> Generation  = (int)substr($energies, 32,6);     // 6D = GENARATION
$NFT -> Origin      = (int)substr($energies, 38,3);     // 3D = ORIGIN
$NFT -> Painter     = (int)substr($energies, 41,4);     // 4D = PAINTER
$NFT -> Series      = (int)substr($energies, 45,4);     // 4D = SERIES
$NFT -> Birthday    = (int)substr($energies, 49,11);    // 11D = BIRTHDAY
$NFT -> Code        = 200;
$NFT -> DNA         = $energies;
$NFT -> wish        = $wish;

//create metadata http://metadata.neko.exchange/ID
$data =
Array (
    attributes =>
    Array (
        "0" => Array (
            "trait_type" => "世代",
            "value" => $NFT ->gen_Display,
        ),
        "1" => Array (
            "trait_type" => "アートピース",
            "value" => $NFT ->ArtPiece + 1,
        ),
        "2" => Array (
            "trait_type" => "系列",
            "value" => $NFT ->Series,
        ),
        "3" => Array (
            "trait_type" => "幸運な",
            "value" => number_format((int)$NFT ->ManekiPower),
        ),
        "4" => Array (
            "trait_type" => "豊か",
            "value" => number_format((int)$NFT ->Wealth),
        ),
        "5" => Array (
            "trait_type" => "機会",
            "value" => number_format((int)$NFT ->Opportunity),
        ),
        "6" => Array (
            "trait_type" => "関係",
            "value" => number_format((int)$NFT ->Relationship),
        ),
        "7" => Array (
            "trait_type" => "知恵",
            "value" => number_format((int)$NFT ->Wisdom),
        ),
        "8" => Array (
            "trait_type" => "元気",
            "value" => number_format((int)$NFT ->Health),
        ),
        "9" => Array (
            "display_type" => "date",
            "trait_type" => "お誕生日",
            "value" => $NFT ->Birthday,
        )
    ),
        "description" => "Great in bringing you luck especially in wealth, relationship and wisdom",
        "external_url" => "https://nft.neko.exchange/",
        "image" => 'https://nft.neko.exchange/'.$nftID.'.svg',
        "name" => '招き猫 #'.$nftID,
        "art_dna" => $energies,
    );

    // encode array to json
    $json = json_encode($data);
    $filename = 'metadata/storage/'.$nftID.'.json';

    //write json to file
    if (file_put_contents($filename, $json))
        echo "JSON file created successfully...";
    else
        echo "Oops! Error creating json file...";

    echo json_encode($NFT);
    

?>