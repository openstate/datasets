<?php

$filename = '20160815220000.xml';
$file = file_get_contents($filename);
$results = [];

if (empty($file))
    die("empty file");
$xml = simplexml_load_string($file);
$error = libxml_get_errors();
if (!empty($error))
    die($error);


$namespaces = $xml->getNamespaces();
$ns = $namespaces["p"];
if (!isset($ns))
    die("namespace error");

foreach ($xml->children($ns) as $child) {// file contains organisaties & gemeenten & else?   
    parse_orgs($child);

    foreach ($results[0] as $key => $value) {
        $keys[] = $key;
    }
    $header = '"' . implode('", "', $keys) . '"' . "\n";

    foreach ($results as $result) {
        $values=[];
        foreach ($keys as $key) {
            $values[] = $result[$key];
        }
        $row = '"' . implode('", "', $values) . '"' . "\n";
        $rows .= $row;
    }
    file_put_contents("organisaties.csv", $header . $rows);

    //no parsing of gemeenten yet
    //print_r($results);    
    break;
}

// now save results to somedisk
// assumes $organisaties->getname() ==  organisaties
function parse_orgs($organisaties, $mother = null) {
    if ($organisaties->getname() != "organisaties") {
        die("OOOOPS geen organisaties namelijk " . $organisaties->getname() . "\n");
    }

    foreach ($organisaties as $organisatie) {
        parse_org($organisatie, $mother);
    }
}

function parse_org($organisatie, $mother = null) {
    if ($organisatie->getname() == "organisaties") {
        parse_org(($organisatie->organisatie), $mother);
        return;
    }

    if ($organisatie->systemId->systemId == null) {
        return;
    }
//    print("id: "
//            . $organisatie->systemId->systemId . " "
//            . $organisatie->naam . " " 
//            . $mother . " "              . "\n");
    $item = array(
        "systemId" => ((string) $organisatie->systemId->systemId),
        "name" => ((string) $organisatie->naam),
        "comment" => ((string) $mother)
    );
    push($item);

    $mother.= "$organisatie->naam - ";
    parse_orgs(($organisatie->organisaties), $mother);
}

function push($item) {
    global $results;

    $results[] = $item;     
    
}

?>