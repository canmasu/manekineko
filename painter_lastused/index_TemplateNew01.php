<?php
// required headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
$_POST = json_decode(file_get_contents("php://input"),true);

/**
 * 1 Create .json   /metadata   https://metadata.neko.exchange/ID
 * 2 Create .SVG                https://nft.neko.exchange/421.svg
 * 3 Create .PNG 
 * 4 Create .GIF
 * 
 * LAYERS 
 * Token ID
 * Generation
 * Maneki Power
 * Element Logo +  招き猫の祝福
 * weather
 * Lucky item
 * nose
 * eyes
 * Face + Ears
 * City
 * pattern
 * Sky
 * Land
 * Base
 * 
 * Wave hand
 * PNG Raise hand
 */
// receive DATA with JSON Format

// retrive POST Values 
// Transaction Hash , nftIF, Wish, Name, Guardian, gammaID(super guardian), Power
$transactionHash    = $_POST['transactionHash'];
$NFT -> id          = number_format($_POST['id']);

if (isset($_POST['wish']) && !empty($_POST['wish'])) {
    $wish     = $_POST['wish'];
} else {
    //$wish = '来て！ 運があなたをフォローします運があなたをフォローします運があなたをフォローします';
    $wish = 'Congratulation for the business grand open. Stay wealthy, prosperity and healty Stay wealthy, prosperity and healty';
}
if (isset($_POST['name']) && !empty($_POST['name'])) {
    $name     = $_POST['name'];
} else {
    $name = '招き猫';
}
$guardian = $_POST['guardian'];
$energies = $_POST['DNA'];
$superguardian  = $_POST['gammaID'];
$power    = $_POST['power'];


// JSON Data
$NFT -> luckyItem       = ((int)substr($energies, 3,4) + (int)substr($energies, 59,2)) % 10;
$NFT -> faceBody        = (int)substr($energies, 7,2) % 10;
$NFT -> eyes            = ((int)substr($energies, 9,2) + (int)substr($energies, 59,2)) % 10;
$NFT -> nose            = ((int)substr($energies, 11,2)+ (int)substr($energies, 59,2)) % 10;
$NFT -> bell            = (int)substr($energies, 12,2) % 10;
$NFT -> city            = (int)substr($energies, 15,3) % 10;
$NFT -> pattern         = (int)substr($energies, 18,2) % 10; 
$NFT -> patternColour   = ((int)substr($energies, 18,2)+ (int)substr($energies, 59,2)) % 10; 
$NFT -> sky             = ((int)substr($energies, 20,2)+ (int)substr($energies, 59,2)) % 50;
$NFT -> weather         = (int)substr($energies, 22,2) % 10;
$NFT -> luckyCaption    = (int)substr($energies, 24,2);
$NFT -> base            = ((int)substr($energies, 29,2)+ (int)substr($energies, 59,2)) % 50;
$NFT -> land            = ((int)substr($energies, 27,2)) % 50;

$NFT -> wealth          = (int)substr($energies, 1,5);
$NFT -> wisdom          = (int)substr($energies, 6,5);
$NFT -> relationship    = (int)substr($energies, 11,5);
$NFT -> health          = (int)substr($energies, 16,5);
$NFT -> opportunity     = (int)substr($energies, 21,5);
$NFT -> manekiPower     = number_format((int)substr($energies, 26,6));
$NFT -> generation      = (int)substr($energies, 32,6);
$NFT -> origin          = (int)substr($energies, 38,3);
$NFT -> painter         = (int)substr($energies, 41,4);
$NFT -> series          = (int)substr($energies, 45,4);
$NFT -> birthdate       = gmdate("Y-M-d  H:i:s",(int)substr($energies, 49,11)).' Tokyo, Japan (GMT+9)';
$NFT -> basePower       = (int)substr($energies, 60,6);


// 5 Element
$FiveElement = array ("0" => $NFT -> wealth, "1" => $NFT -> oportunity, "2" => $NFT -> relationship, "3" => $NFT -> wisdom, "4" => $NFT -> health);
$NFT -> element = array_search(max($FiveElement),$FiveElement);
$NFT -> elementVal = ceil($FiveElement[$NFT -> element]/10000);

// Genaration labeling 
if ($NFT -> generation<6){
    if($NFT -> generation==1) { $NFT -> generation = "α"; }
    if($NFT -> generation==2) { $NFT -> generation = "β"; }
    if($NFT -> generation==3) { $NFT -> generation = "γ"; }
    if($NFT -> generation==4) { $NFT -> generation = "δ"; }
    if($NFT -> generation==5) { $NFT -> generation = "ε"; }
  }

// Palette Set 1 and 2
$ColourSet1 = array('#050505','#ffffff','#00a651','#ed1c24','#ffc60a','#1989dd','#debf8c','#cccccc','#d573f9','#19ff00');
$ColourSet2 = array(
    '#333333','#471411','#664949','#d33e20','#ed1c24','#ff500b','#f37021','#fc7272','#ffe1e1','#fff5f5',
    '#515151','#444411','#686849','#debf8c','#fff200','#ffc60a','#ffe600','#f9e873','#f7f3ed','#fffae3',
    '#808080','#112a3f','#4a6a6d','#1989dd','#2e3192','#51a4da','#39bced','#73aaf9','#a4eeff','#e3fbff',
    '#cccccc','#114212','#526b4a','#30e017','#00a651','#a7ac39','#b3d3c2','#73f9d3','#caf8bd','#f0ffeb',
    '#ffffff','#34113d','#624a66','#d122a4','#92278f','#be1e6f','#c04078','#d573f9','#caa9ff','#ede3ff'
);

// select and concat the SVG source files
// Export > Export as > user artboard 
// > SVG OPTION (inline style, SVG, Preserve, Layer Name, Decimal 2, Reponsive)

//SVG header
$meow = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1500 1500">';

//SVG template
//external source

// Sky 
$meow .='  <g id="sky">
<rect width="1500" height="1499.86" style="fill: '.$ColourSet2[$NFT -> sky].'"/>
</g>';

//$meow .= file_get_contents('template/pattern/'.$NFT -> pattern.'.txt');

//include external PHP file with variable colour palette
include 'template/pattern/'.$NFT -> pattern.'.php';

// Base
$meow .='  <g id="base">
<rect y="1330.87" width="1500" height="168.98" style="fill: '.$ColourSet2[$NFT -> base].'"/>
</g>';

// Land
$meow .='  <g id="land">
<rect x="0.46" y="1028.55" width="1500.11" height="302.58" style="fill: '.$ColourSet2[$NFT -> land].'"/>
</g>';

$meow .= file_get_contents('template/city/'.$NFT -> city.'.txt');
$meow .= file_get_contents('template/faceBody/'.$NFT -> faceBody.'.txt');
$meow .= file_get_contents('template/bell/'.$NFT -> bell.'.txt');
$meow .= file_get_contents('template/nose/'.$NFT -> nose.'.txt');
$meow .= file_get_contents('template/eyes/'.$NFT -> eyes.'.txt');
$meow .= file_get_contents('template/luckyItem/'.$NFT -> luckyItem.'.txt');
$meow .= file_get_contents('template/luckyCaption/0.txt');
$meow .= file_get_contents('template/weather/'.$NFT -> weather.'.txt');

//inner source - text input



// Generation
$meow .='
<g id="generation">
  <text transform="translate(38.41 1285.15)" style="font-size: 65.69450378417969px;font-family: HiraKakuStd-W8-83pv-RKSJ-H, Hiragino Kaku Gothic Std">世代</text>
  <text transform="translate(200 1290)" style="font-size: 90px;font-family: MyriadPro-Regular, Myriad Pro">'.$NFT -> generation.'</text>
</g>';

//inner source - text input 5 elements
$meow .= file_get_contents('template/5element/'.$NFT -> element.'.txt');

//inner source - text input Maneki power
$meow .='
<g id="manekiPower">
  <text transform="translate(205.81 1456.78)" style="font-size: 121.63294982910156px;fill: #070707;font-family: MyriadPro-Regular, Myriad Pro">'.$NFT -> manekiPower.'</text>
  <ellipse cx="82.99" cy="1415.19" rx="49.5" ry="49.5"/>
  <polygon points="82.92 1378.7 99.5 1378.7 81.54 1414.62 99.5 1414.62 68.55 1454.33 83.19 1421.63 67.58 1422.06 82.92 1378.7" style="fill: #fff"/>
  <text text-anchor="middle" transform="translate(1332 1425.38)" style="font-size: 104.09490966796875px;font-family: MyriadPro-Regular, Myriad Pro">'.$NFT -> elementVal.'</text>
</g>';

//inner source - text input tokenID
$meow .= '<g id="tokenID">\
<text text-anchor="end" transform="translate(1400 130)" style="font-size: 100px;font-family: MyriadPro-Regular, Myriad Pro">'.$NFT -> id.'</text>
</g>';

//external source
$meow .= file_get_contents('template/logo/0.txt');

//SVG Footer
$meow .= '</svg>';

//write into SVG
$newFileName = 'images/'.$NFT -> id.'.svg';
$newNeko = fopen($newFileName, "w") or die("Unable to open file!");
fwrite($newNeko, $meow);
fclose($newNeko);

echo 'painting completed';

//Generate metadata https://metadata.neko.exchange/ID
$data =
Array (
    attributes =>
    Array (
        "0" => Array (
            "trait_type" => "世代",
            "value" => $NFT -> generation,
        ),
        "1" => Array (
            "trait_type" => "アートピース",
            "value" => $NFT -> city,
        ),
        "2" => Array (
            "trait_type" => "系列",
            "value" => $NFT ->series,
        ),
        "3" => Array (
            "trait_type" => "幸運な",
            "value" => number_format((int)$NFT -> manekiPower),
        ),
        "4" => Array (
            "trait_type" => "オリジナルの気",
            "value" => number_format((int)$NFT -> basePower),
        ),
        "5" => Array (
            "trait_type" => "豊か",
            "value" => number_format((int)$NFT -> wealth),
        ),
        "6" => Array (
            "trait_type" => "知恵",
            "value" => number_format((int)$NFT -> wisdom),
        ),
        "7" => Array (
            "trait_type" => "関係",
            "value" => number_format((int)$NFT -> relationship),
        ),
        "8" => Array (
            "trait_type" => "元気",
            "value" => number_format((int)$NFT -> health),
        ),
        "9" => Array (
            "trait_type" => "機会",
            "value" => number_format((int)$NFT -> opportunity),
        ),
        "10" => Array (
            "display_type" => "date",
            "trait_type" => "お誕生日",
            "value" => $NFT -> birthdate,
        )
    ),
    "description" => $wish,
    "external_url" => "https://nft.neko.exchange/",
    "image" => 'https://nft.neko.exchange/'.$NFT -> id .'.svg',
    "transactionHash" => $transactionHash,
    "name" => $name,
    "art_dna" => $energies,
    "guardian" => $guardian,
    "gamma" => $superguardian
);


if(file_exists('metadata/storage/'.$NFT -> id.'.json')==0){
    // encode array to json
    $json = json_encode($data);
    $filename = 'metadata/storage/'.$NFT -> id.'.json';

    //write json to file
    if (file_put_contents($filename, $json))
        echo "JSON file created successfully...";
    else
        echo "Oops! Error creating json file...";

    echo json_encode($data);
} else {
    echo "Oops! Metadata not wrote";
}



?>