<?php

// 2016 Copyleft Lex Slaghuis, Open State Foundation 
// http://www.openstate.eu
// get an overview from http://dataderden.cbs.nl/ODataApi/
// http://dataderden.cbs.nl/ODataApi/OData/45006NED/Gemeenten
// http://dataderden.cbs.nl/ODataApi/OData/45009NED/Provincies
// http://dataderden.cbs.nl/ODataApi/OData/45025NED/GemeenschappelijkeRegelingen
// (But we now take in everything!)

$results = [];

$whitelist = array("Gemeenten", "Provincies", "Waterschappen",
    "GemeenschappelijkeRegelingen");

$server = 'http://dataderden.cbs.nl';
$index = '/ODataApi'; // needs a harvester / extracter
// Create DOM from URL or file
$file = file_get_contents($server . $index);
$html = new DOMDocument();
$html->loadHTML($file);

// Find all links & load page & search for links
//$govmap = [];
foreach ($html->getElementsByTagName('a') as $element) {
    $link = $element->nodeValue;
    //echo $link;
    $sub = file_get_contents($server . $link);
    //print($sub);
    $json = json_decode($sub);
    // print_r($json);
    foreach ($json->value as $record) {
        if (in_array($record->name, $whitelist)) {
            print_r($record->url . "\n");
            $data = file_get_contents($record->url);
            // print($data);
            $json_data = json_decode($data);
            foreach ($json_data->value as $organisatie) {
                //var_dump($organisatie);
//                print('cbsId: "'
//                        . trim($organisatie->Key) . '" "'
//                        . $organisatie->Title . '" "'
//                        . $organisatie->Description . '"' . "\n");
                $item = array(
                    "cbsId" => ((string) trim($organisatie->Key)),
                    "name" => ((string) $organisatie->Title),
                    "comment" => ((string) 
                     str_replace('"',"'"
                        ,str_replace("\r\n", '. <br>', $organisatie->Description)                                                
                      )
                        ));
                //global $results;
                $results[] = $item;
            }
        }
    }
}

// process results        
print(" aantal originele records " . sizeof($results) . "\n");

foreach ($results[0] as $key => $value) {
    $keys[] = $key;
}
$header = '"' . implode('", "', $keys) . '"' . "\n";
//print($header);

foreach ($results as $result) {
//    print_r($result);
 
    if (isset($deduped[$result['cbsId']])) {
     //   print " reeds gevonden " . $result['cbsId'] . "  \n";
    } else {
     //   print" nieuw! " . $result['cbsId'] . "\n";
    }
    
    $deduped[$result['cbsId']] = array( "cbsId"=>$result['cbsId'], "name"=>$result['name']
        , "comment"=>$result['comment']);
}

print(" aantal deduped records " . sizeof($deduped) . "\n");

foreach($deduped as $key=>$values){
    $row = '"' . implode('", "', $values) . '"' . "\n";
    $rows .= $row;
}


file_put_contents("organisaties.csv", $header . $rows);


?>