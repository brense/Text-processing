<pre><?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once('src/Request.php');
require_once('src/TextProcessingClient.php');
require_once('src/TextRazorClient.php');

/*
$key = file_get_contents('textProcessingKey');
$language = 'dutch';
$text = 'Hallo Theuy, gaat het goed in Leiden?';

$client = new TextProcessingClient($key);

$response = $client->phrases($language, $text);
print_r($response);

$response = $client->sentiment($language, $text);
print_r($response);

$response = $client->stem($language, $text);
print_r($response);

$response = $client->tag($language, $text);
print_r($response);
*/

$key = file_get_contents('textRazorKey');
$text = 'Het ministerie van Justitie zegt dat het zoekteam goede contacten onderhoudt met de burgemeester van Grabovo. Met hem wordt onder meer besproken of het er veilig genoeg is om te zoeken. In het gebied is het door gevechten tussen het Oekra&iuml;ense leger en pro-Russische rebellen onrustig. Als de veiligheid en het weer het toelaten, bezoekt het team morgen meer dorpen in de omgeving van het rampgebied. Ook dan wordt weer samengewerkt met de OVSE en Oekra&iuml;ense hulpdiensten.';

$client = new TextRazorClient($key);

$client->addExtractor('entities');
//$client->addExtractor('words');
$client->addEnrichmentQuery("fbase:/location/location/geolocation>/location/geocode/latitude");
$client->addEnrichmentQuery("fbase:/location/location/geolocation>/location/geocode/longitude");

$response = $client->analyze($text);
print_r($response);
