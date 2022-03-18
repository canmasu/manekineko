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
$NFT -> name            = $name;

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
  <path d="M47.6,1246.22c0-3.48-.13-6-.39-8.8H57.92a73.58,73.58,0,0,0-.39,8.74v4.92H66v-6.17a64.11,64.11,0,0,0-.39-7.56H76.12a66.51,66.51,0,0,0-.33,7.43v6.3h8.35v-6.37a59.07,59.07,0,0,0-.33-7.36H94.39a60.29,60.29,0,0,0-.4,7.43v6.3h.59a61.13,61.13,0,0,0,7.69-.39v9.85a64.08,64.08,0,0,0-7.69-.39H94v13.6c0,1.77.07,4.07.26,6.56-1.9-.13-3.54-.19-5.71-.19H71.91c-2.16,0-3.94.06-6.11.19.14-1.7.2-3.67.2-5.91v-14.25H57.53v26.4H90a82.81,82.81,0,0,0,9.53-.39v13.18c-3.22-.33-42-.63-42-.63H47.14c.33-3.35.46-6.77.46-10.45v-28.11a66.38,66.38,0,0,0-7.42.39v-9.85a64.51,64.51,0,0,0,7.42.39Zm28.19,13.93v10.9h8.35v-10.9Z"/>
  <path d="M113.7,1275.58c0-3.54,0-4.92.13-7a40.72,40.72,0,0,1-4.93,5.91,44.23,44.23,0,0,0-4.14-11.43,55,55,0,0,0,11.57-18.4,35.46,35.46,0,0,0,2.43-8.34l10.25,3.35a118,118,0,0,1-5.26,13.14v37.57a70,70,0,0,0,.4,8.15H113.3a68.43,68.43,0,0,0,.4-8.15Zm33.84-12.15a51.68,51.68,0,0,0,3.81,13.93,29.77,29.77,0,0,0,4.46,7.16,6,6,0,0,0,1.91,1.57c.85,0,1.64-4.13,2.1-11.23a34.94,34.94,0,0,0,8.68,5.65c-.86,7.49-1.91,11.43-3.82,14.52a6.85,6.85,0,0,1-6,3.55c-3.35,0-6.64-2.11-10.38-6.64-5.59-6.83-9.2-16-10.91-28l-5.19.33a55.58,55.58,0,0,0-6.63.65l-.07-10c1.91.07,2.1.07,2.82.07,1.06,0,3.42-.07,4.54-.13l3.55-.2c-.27-3.94-.27-3.94-.46-11.5a35.07,35.07,0,0,0-.4-5.58l11-.2a31.47,31.47,0,0,0-.33,5.59c0,3.74.07,6.3.4,11l9.19-.52a77.24,77.24,0,0,0-8.73-10.64l8-5.39a82.72,82.72,0,0,1,9.59,10.64l-7.22,5.26,1.77-.07a61,61,0,0,0,7.75-.85l.07,10.24a23.16,23.16,0,0,0-2.83-.13,36.24,36.24,0,0,0-4.2.2Z"/>
  <text transform="translate(200 1290)" style="font-size: 90px;font-family: DoulosSIL, Doulos SIL">'.$NFT -> generation.'</text>
</g>';

//inner source - text input 5 elements
$meow .= file_get_contents('template/5element/'.$NFT -> element.'.txt');

//inner source - text input Maneki power
$meow .='
<g id="manekiPower">
  <text transform="translate(205.81 1456.78)" style="font-size: 121.63294982910156px;fill: #070707;font-family: DoulosSIL, Doulos SIL">'.$NFT -> manekiPower.'</text>
  <ellipse cx="82.99" cy="1415.19" rx="49.5" ry="49.5"/>
  <polygon points="82.92 1378.7 99.5 1378.7 81.54 1414.62 99.5 1414.62 68.55 1454.33 83.19 1421.63 67.58 1422.06 82.92 1378.7" style="fill: #fff"/>
  <text text-anchor="middle" transform="translate(1005 447.47)" style="font-size: 37.49140930175781px;font-family:DoulosSIL, Doulos SIL">'.$NFT -> elementVal.'</text>
  <text  text-anchor="end" transform="translate(1450 1415.81)" style="font-size: 50px; font-family: DoulosSIL, Doulos SIL">'.$NFT -> name.'</text>
</g>';

//inner source - text input tokenID
$meow .= '<g id="tokenID">
<text text-anchor="end" transform="translate(1450 1298.55)" style="font-size: 49px;font-family: DoulosSIL, Doulos SIL">'.$NFT -> id.'</text>
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