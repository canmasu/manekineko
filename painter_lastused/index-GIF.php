<?php

echo "start";
require "src/AnimGif.php";

$anim = new GifCreator\AnimGif();

// Use an array containing file paths, resource vars (initialized with imagecreatefromXXX), 
// image URLs or binary image data.
$frames = array(
    //imagecreatefrompng("/../images/pic1.png"),      // resource var
    //"/../images/pic2.png",                          // image file path
    //file_get_contents("/../images/pic3.jpg"),       // image binary data
    "https://nft.neko.exchange/334.svg", // URL
    "https://nft.neko.exchange/188.svg", // URL
    "https://nft.neko.exchange/23.svg", // URL
);


echo $frames;

// Or: load images from a dir (sorted, skipping .files):
//$frames = "../images";

// Optionally: set different durations (in 1/100s units) for each frame
$durations = array(20, 30, 10, 10, 40, 50,10);

// Or: you can leave off repeated values from the end:
//$durations = array(20, 30, 10); // use 10 for the rest
// Or: use 'null' anywhere to re-apply the previous delay:
//$durations = array(250, null, null, 500);


$anim = new GifCreator\AnimGif();
$anim->create($frames, $durations);

// Or: using the default 100ms even delay:
//$anim->create($frames);

// Or: loop 5 times, then stop:
//$anim->create($frames, $durations, 5); // default: infinite looping

$anim->save("animated.gif");

echo "end";

header("Content-type: image/gif");
echo $gif;

exit;

?>
