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
//$currentPrice = number_format((float)($_GET['PRICE']/1000000000000000000), 2, '.', '');
$currentPrice = $_GET['PRICE'];



header('Content-type: text/html; charset=utf-8');
?>



<!DOCTYPE html>
<html lang="en-US">
    <head>  
        <meta name="viewport" content="width=device-width"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title> Maneki-Meow </title>
        <meta name="description" content="Maneki-Meow 猫 #<?php echo $meow_id; ?> is for Sale now" itemprop="description"/>
        <meta name="Keywords" content="Maneki Meow, Lucky Cat, Fortune, Wish">

        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Maneki-Meow 猫 #<?php echo $meow_id; ?> is for Sale now" />
        <meta name="twitter:site" content="Maneki-Meow 猫 #<?php echo $meow_id; ?> is for Sale now" />
        <meta name="twitter:creator" content="@ManekiMarket"/>
        <meta name="twitter:description" content="Price <?php echo $tokenSymbol.' '.$currentPrice; ?> , get the lastest bid by click live bid button" />
        <meta name="twitter:image" content="https://nft.neko.exchange/png/<?php echo $meow_id; ?>.png" />

        <meta property="og:image" content="https://nft.neko.exchange/png/<?php echo $meow_id; ?>.png" itemprop="image"/>
        <meta property="og:image:alt" content="Maneki-Meow 猫 #<?php echo $meow_id; ?> For Sale, Price <?php echo $tokenSymbol.' '.$currentPrice; ?> , get the lastest bid by click live bid button" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />

        <meta property="og:site_name" content="Maneki-Meow" />
        <meta property="og:type" content="object" />
        <meta property="og:title" content="Maneki-Meow 猫 #<?php echo $meow_id; ?> is for Sale now" itemprop="name" />
        <meta property="og:url" content="https://auction.maneki.market/?MID=<?php echo $meow_id; ?>&TOKENSYMBOL=<?php echo $tokenSymbol?>&PRICE=<?php echo $currentPrice;?>" />
        <meta property="og:description" content="Price <?php echo $tokenSymbol.' '.$currentPrice; ?> , get the lastest bid by click live bid button" />
  
        <style>
            body {
                font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen,Ubuntu,Cantarell,Open Sans,Helvetica Neue,sans-serif;
                background :#000; background: url('/src/Bg-DApp.png'); background-repeat: no-repeat; ;
            }
            .logo{
                margin-top:20px;
                margin-left:20px
            }
            .buy_btn {
                text-align: center;
                padding:10px 30px;
                float:right; 
                width:80px; 
                font-size:1.2em; 
                background-color:#ffe000; 
                color:#ff3600; 
                border:1px solid #ff3600;
                text-align: center;
                border-radius: 5px;
            }
            a {
                text-decoration:none;
            }

            @media only screen and (max-width: 768px) {

            .logo{
                margin:10px auto;
                width:400px;

            }

            }
        </style>

    </head>
    <body >
        <div class="main_container">
            <a href = "https://testnet.maneki.market/" >
                <img src="/src/logo-maneki_logo.png" class="logo"/>
            </a>
            
            <div class="card" style="background: #ffe000; width:400px; height:540px; margin:0 auto; padding:15px; border:1px solid black; border-radius:20px;"> 

                <div style="font-size:1.6em; padding:5px 10px;padding-top:0; ">
                    For Sale
                </div>

                <img src="https://nft.neko.exchange/gif/<?php echo $meow_id ?>.gif" style="width:400px; height:400px;" />
            
                <div  class="auction_detail" style="padding:10px; width: 380px; height:60px; background-color:#ff4800; color:#262626; margin-top:0; border-radius: 0 0 10px 10px; "> 
                    <div class="price-tab" style=" height:70px; ">  
                        <!-- Action Button -->
                        <div class="buy_btn" >
                            <a href="https://testnet.maneki.market/#/auction/<?php echo $meow_id ?>/0x0/">
                            Live bid 
                            </a>
                        </div>

                        <!-- Price tag -->
                        <div style="width:230px; float:right;"> Price <br>
                            <span style="font-size:1.5em;"><?php echo $tokenSymbol.' '.$currentPrice ?> </span>
                        </div>

                         <div style="clear:both;"></div>
                    </div>

                    <!-- disclaimer -->
                    <div style="width:300px font-size:0.7em;"> Price shown not latest update, view live bid </div>
                </div>
            </div>
        </div>
    </body>

</html>

