<?php
// PATH Harvest Server > Folder : auction_card

// HTML LANDING PAGE
// GET DATA for display 
// MEOW_ID, CURRENT PRICE, AUNCTION END 
// LOAD Metadata from JSON
// Call to action > back to Maneki DApp > Auction

// MEOW ID
$meow_id = $_GET['MID'];
$tokenSymbol = $_GET['TOKENSYMBOL'];
$currentPrice = $_GET['PRICE'];
?>





<!DOCTYPE html>
<html lang="en" data-color-mode="auto" data-light-theme="light" data-dark-theme="dark" >
  <head>
    <meta charset="utf-8">
    <title> Maneki-Meow </title>
        

        <meta name="twitter:image:src" content="https://opengraph.githubassets.com/551c2e0a8a70cbf0b199a35beca194f0cc08f094a336fdb138330ca21cdef1e9/pedroborges/meta-tags" />
        <meta name="twitter:site" content="@github" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="pedroborges/meta-tags: HTML meta tags generator for PHP. Supports Open Graph, Twitter Cards, and JSON Linked Data out of the box" />
        <meta name="twitter:description" content="HTML meta tags generator for PHP. Supports Open Graph, Twitter Cards, and JSON Linked Data out of the box - pedroborges/meta-tags: HTML meta tags generator for PHP. Supports Open Graph, Twitter Car..." />
        <meta property="og:image" content="https://opengraph.githubassets.com/551c2e0a8a70cbf0b199a35beca194f0cc08f094a336fdb138330ca21cdef1e9/pedroborges/meta-tags" />
        <meta property="og:image:alt" content="HTML meta tags generator for PHP. Supports Open Graph, Twitter Cards, and JSON Linked Data out of the box - pedroborges/meta-tags: HTML meta tags generator for PHP. Supports Open Graph, Twitter Car..." />
        <meta property="og:image:width" content="1200" /><meta property="og:image:height" content="600" /><meta property="og:site_name" content="GitHub" />
        <meta property="og:type" content="object" />
        <meta property="og:title" content="pedroborges/meta-tags: HTML meta tags generator for PHP. Supports Open Graph, Twitter Cards, and JSON Linked Data out of the box" /><meta property="og:url" content="https://github.com/pedroborges/meta-tags" /><meta property="og:description" content="HTML meta tags generator for PHP. Supports Open Graph, Twitter Cards, and JSON Linked Data out of the box - pedroborges/meta-tags: HTML meta tags generator for PHP. Supports Open Graph, Twitter Car..." />
    


    </head>
    <body>
        <div class="card"> 
            MEOW 猫 # <?php echo $meow_id ?>' current best bid <?php echo $tokenSymbol.' '.$currentPrice; ?>
        </div>
    </body>

</html>

