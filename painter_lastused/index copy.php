<?php
// required headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

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
$_POST = json_decode(file_get_contents("php://input"),true);

// retrive POST Values 
// Transaction Hash , nftIF, Wish, Name, Guardian, gammaID(super guardian), Power
$transactionHash    = $_POST['transactionHash'];
$nftID              = $_POST['id'];

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
$gammaID  = $_POST['gammaID'];
$power    = $_POST['power'];


// JSON Data

$NFT -> luckyItem       = (int)substr($energies, 3,4) + (int)substr($energies, 59,2);
$NFT -> faceBody        = (int)substr($energies, 7,2);
$NFT -> eyes            = (int)substr($energies, 9,2) + (int)substr($energies, 59,2);
$NFT -> nose            = (int)substr($energies, 11,2)+ (int)substr($energies, 59,2);
$NFT -> bell            = (int)substr($energies, 12,2);
$NFT -> city            = (int)substr($energies, 15,3);
$NFT -> pattern         = (int)substr($energies, 18,2); 
$NFT -> sky             = (int)substr($energies, 20,2)+ (int)substr($energies, 59,2);
$NFT -> weather         = (int)substr($energies, 22,2);
$NFT -> luckyCaption    = (int)substr($energies, 24,2);
$NFT -> base            = (int)substr($energies, 29,2);
$NFT -> land            = (int)substr($energies, 55,2);

$NFT -> wealth          = (int)substr($energies, 1,5);
$NFT -> wisdom          = (int)substr($energies, 6,5);
$NFT -> relationship    = (int)substr($energies, 11,5);
$NFT -> health          = (int)substr($energies, 16,5);
$NFT -> opportunity     = (int)substr($energies, 21,5);
$NFT -> manekiPower     = (int)substr($energies, 26,6);
$NFT -> generation      = (int)substr($energies, 32,6);
$NFT -> origin          = (int)substr($energies, 38,3);
$NFT -> painter         = (int)substr($energies, 41,4);
$NFT -> series          = (int)substr($energies, 45,4);
$NFT -> birthdate       = gmdate("Y-M-d  H:i:s",(int)substr($energies, 49,11)).' Tokyo, Japan (GMT+9)';
$NFT -> basePower       = (int)substr($energies, 60,6);



// select and concat the SVG source files
// Export > Export as > user artboard 
// > SVG OPTION (inline style, SVG, Preserve, Layer Name, Decimal 2, Reponsive)

//SVG header
$meow = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1500 1500">';

//SVG template
//external source
$meow .= file_get_contents('template/sky/0.txt');
$meow .= file_get_contents('template/pattern/0.txt');
$meow .= file_get_contents('template/base/0.txt');
$meow .= file_get_contents('template/land/0.txt');
$meow .= file_get_contents('template/city/0.txt');
$meow .= file_get_contents('template/faceBody/0.txt');
$meow .= file_get_contents('template/bell/0.txt');
$meow .= file_get_contents('template/nose/0.txt');
$meow .= file_get_contents('template/eyes/0.txt');
$meow .= file_get_contents('template/luckyItem/0.txt');
$meow .= file_get_contents('template/luckyCaption/0.txt');
$meow .= file_get_contents('template/weather/0.txt');

//inner source - text input
$meow . '
<g id="generation">
  <text transform="translate(38.41 1285.15)" style="font-size: 65.69450378417969px;font-family: HiraKakuStd-W8-83pv-RKSJ-H, Hiragino Kaku Gothic Std">世代</text>
  <text transform="translate(219.15 1271.7)" style="font-size: 104.09490966796875px;font-family: MyriadPro-Regular, Myriad Pro">γ</text>
</g>';

//inner source - text input 5 elements
$meow .= 'file_get_contents('template/5element/0.txt')';

//inner source - text input Maneki power
$meow .= '
<g id="manekiPower">
  <text transform="translate(205.81 1456.78)" style="font-size: 121.63294982910156px;fill: #070707;font-family: MyriadPro-Regular, Myriad Pro">834,9484</text>
  <ellipse cx="82.99" cy="1415.19" rx="49.5" ry="49.5"/>
  <polygon points="82.92 1378.7 99.5 1378.7 81.54 1414.62 99.5 1414.62 68.55 1454.33 83.19 1421.63 67.58 1422.06 82.92 1378.7" style="fill: #fff"/>
  <text transform="translate(1269.14 1425.38)" style="font-size: 104.09490966796875px;font-family: MyriadPro-Regular, Myriad Pro">10</text>
</g>';

//inner source - text input tokenID
$meow .= '<g id="tokenID">
<text transform="translate(1084.21 149.82)" style="font-size: 104.09490966796875px;font-family: MyriadPro-Regular, Myriad Pro">101,101</text>
</g>'

//external source
$meow .= file_get_contents('template/logo/0.txt');

//SVG Footer
$meow .= '</svg>';

//write into SVG
$newFileName = 'images/'.$nftID.'.svg';
$newNeko = fopen($newFileName, "w") or die("Unable to open file!");
fwrite($newNeko, $meow);
fclose($newNeko);

?>