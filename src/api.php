<?php
// Promotes the file as JSON.
header("Content-Type: application/json");

// Initializes cURL in the "ch" parameter.
$ch = curl_init();

// We define the URL and the user agent.
$url = $_GET['url'];
$user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36";

// cURL settings.
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

// We saved the output in the "response" parameter.
$response = curl_exec($ch);

// (optional) remove the "#" at the beginning of the code if you want to close the cURL connection after saving the output.
# curl_close($ch);

// We parse the image URL in the output using DOMDocument. Then we delete the first slash in the resulting URL and replace it with "originals".
$dom = new DOMDocument();
@$dom->loadHTML($response);

$xpath = new DOMXPath($dom);
$elements = $xpath->query("//img[@class='hCL kVc L4E MIw']");

$result = "";
if ($elements->length > 0) {
    $result = $elements->item(0)->getAttribute('src');
    $result = preg_replace('#^(https?://[^/]+/)\w+#', '$1originals', $result);
}

// We show the resulting data as JSON output.
$output = json_encode(array("image" => trim($result)), JSON_UNESCAPED_SLASHES);

echo $output;
