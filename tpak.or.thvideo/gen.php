<?php 
// Input file paths
$brandsFile = 'brand.txt';
$titlesFile = 'title.txt';
$descriptionsFile = 'description.txt';
$articlesFile = 'article.txt';
$emojisFile = 'emoji.txt';

// Output file
$outputFile = 'list.json';

// Read files into arrays
$brands = file($brandsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$titles = file($titlesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$descriptions = file($descriptionsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$articles = file($articlesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$emojis = file($emojisFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Ensure no input is empty
if (empty($brands) || empty($titles) || empty($descriptions) || empty($articles) || empty($emojis)) {
    die("Error: One or more input files are empty or missing.\n");
}

// Create the JSON structure
$data = ["brands" => []];

foreach ($brands as $brandSlug) {
    // Generate brand name from slug
    $brandName = strtoupper(str_replace('-', ' ', $brandSlug));
    
    // Randomly pick a title, description, article, and emoji
    $randomTitle = $titles[array_rand($titles)];
    $randomDescription = $descriptions[array_rand($descriptions)];
    $randomArticle = $articles[array_rand($articles)];
    $randomEmoji = $emojis[array_rand($emojis)];

    // Combine brand name and emoji with title and description
    $finalTitle = "$brandName $randomEmoji $randomTitle";
    $finalDescription = $randomDescription;
    $finalArticle = $randomArticle;

    // Add the brand details to the data array
    $data["brands"][] = [
        "slug" => $brandSlug,
        "meta_title" => $finalTitle,
        "meta_description" => $finalDescription,
        "meta_article" => $finalArticle,
        "brand" => $brandName
    ];
}

// Write JSON to file
file_put_contents($outputFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "JSON udah jadi tinggal pake aja: $outputFile\n";