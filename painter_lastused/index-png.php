<?php
//header('Content-Type: text/html; charset=utf-8');
//mb_internal_encoding('utf-8');

  //write into PNG

  echo 'start';

  //phpinfo();

    $nftID=$_GET['id'];

    echo 'nftID : '.$nftID.'<br>';

    $im = new Imagick();

    // first image
    $svg = file_get_contents('images/'.$nftID.'.svg');
    $im->readImageBlob($svg);
    $im->setImageFormat("png24");
    $im->writeImage("images/png/".$nftID."a.png");
    $im->clear();
    $im->destroy();

    echo $svg;
    // second image

    // add text 

    $draw = new ImagickDraw();



    $nftID2 = $nftID - 1;
    $svg = file_get_contents('images/'.$nftID2.'.svg');
    $im->readImageBlob($svg);




    /* Font properties */
    //draw->setFont('fonts-japanese-gothic.tif');
    //$im->setFont('gkai00mp.ttf');
    //$im->setFont('Arial-Unicode-Regular.ttf');
    
    $draw->setFontSize( 50 );
    //$draw->setTextEncoding('utf-8');

   // $wish = mb_convert_encoding($wish, 'UTF-8', 'GB18030');




    // Set the text encoding
    $draw->setTextEncoding('UTF-8');
    $draw->setFont($_SERVER['DOCUMENT_ROOT'].'/painter/fonts/arial-unicode-ms.ttf');
    

    /* Create text */
    $im->annotateImage($draw, 150, 400, 0, utf8_encode('The quick brown fox jumps over the lazy dog虎'));

    //$im->setFont('fonts/W0.ttc');
    $im->annotateImage($draw, 150, 450, 0, "虎の年の幸運、富と長寿の増加");
    $im->annotateImage($draw, 150, 500, 0, 'The quick Viel Glück im Jahr des Tigers jumps over the lazy dog');
    $im->annotateImage($draw, 150, 550, 0, '호랑이의 添富，添寿');



    $im->setImageFormat("png24");
    $im->writeImage("images/png/".$nftID."b.png");
    $im->clear();
    $im->destroy();

    echo $svg;

    //create GIF 

    require "src/AnimGif.php";
    $anim = new GifCreator\AnimGif();
    $frames = array(
        //imagecreatefrompng("/../images/pic1.png"),      // resource var
        //"/../images/pic2.png",                          // image file path
        //file_get_contents("/../images/pic3.jpg"),       // image binary data
        //"https://nft.neko.exchange/334.svg", // URL
        //"https://nft.neko.exchange/188.svg", // URL
        //"https://nft.neko.exchange/23.svg", // URL
        "images/png/".$nftID."a.png",                          // image file path
        "images/png/".$nftID."b.png",                          // image file path
    );

    $durations = array(50, 100);
    $anim = new GifCreator\AnimGif();
    $anim->create($frames, $durations);
    $anim->save("images/gif/".$nftID.".gif");

?>
