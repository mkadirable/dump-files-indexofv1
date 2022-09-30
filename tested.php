<?php
echo "LET'S TO DUMP DATA INSTANT \n";
echo "example: site.com/dir/dir or etc. \n";
echo "url site: ";
$urlx = trim(fgets(STDIN));

$curl = curl_init();
$url = "$urlx";

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($curl);
curl_close($curl);

preg_match_all('!<a href="(.*\.png)">!', $result, $match);
$finded = $match[1];

foreach ($finded as $findkey => $valfind) {
    $filename = basename($valfind);
    $dir = "./files/";
    $saveloc = $dir . $filename;
    if ($fp = fopen($saveloc, "wb")) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url" . $filename);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        echo "File $filename downloded. \n";
    } else {
        echo "File downloading failed \n";
    }
}

$zipname = "data.zip";
$zip = new ZipArchive();
if ($zip->open($zipname, ZipArchive::CREATE)) {
    $direc = opendir($dir);
    while ($file = readdir($direc)) {
        if (is_file($dir . $file)) {
            $zip->addFile($dir . $file, $file);
        }
    }
    echo "\n==================================== \n";
    echo "All data has been $zipname \n";
    $zip->close();
    $folder = "files";
    $locdel = glob($folder . "/*");
    foreach ($locdel as $del) {
        if (is_file($del)) {
            unlink($del);
        }
    }

    echo "done and have fun! \n";
    echo "==================================== \n";
}

?>
