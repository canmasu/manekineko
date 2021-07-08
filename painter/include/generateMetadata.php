<?php

data =
Array (
    attributes =>
    Array (
        "0" => Array (
            "trait_type" => "世代",
            "value" => $gen_Display,
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
            "trait_type" => "オリジナルの気",
            "value" => number_format((int)$NFT -> BasePower),
        ),
        "5" => Array (
            "trait_type" => "豊か",
            "value" => number_format((int)$NFT ->Wealth),
        ),
        "6" => Array (
            "trait_type" => "機会",
            "value" => number_format((int)$NFT ->Opportunity),
        ),
        "7" => Array (
            "trait_type" => "関係",
            "value" => number_format((int)$NFT ->Relationship),
        ),
        "8" => Array (
            "trait_type" => "知恵",
            "value" => number_format((int)$NFT ->Wisdom),
        ),
        "9" => Array (
            "trait_type" => "元気",
            "value" => number_format((int)$NFT ->Health),
        ),
        "10" => Array (
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
    "wish" => $wish,
);

// encode array to json
$json = json_encode($data);
$filename = 'metadata/storage/'.$nftID.'.json';

//write json to file
if (file_put_contents($filename, $json))
    echo "JSON file created successfully...";
else
    echo "Oops! Error creating json file...";

echo json_encode($data);
?>