<?php

// 2016 Copyleft Lex Slaghuis, Open State Foundation 
// http://www.openstate.eu
// this could have been done with SimpleXML xpath way more easy and fast
// but recursion was used to analyze the data and allow relation tracking later on
// which also can be done with a xpath parent lookup ;-(

// get a file from rijksalmanak (see README)
$filename = '20160815220000.xml';// needs a harvester / extracter
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

foreach ($xml->children($ns) as $child) {
    if ($child->getname() == "gemeenten") {

        foreach ($child as $organisatie) {
//            print("id: "
//                    . $organisatie->systemId->systemId . " "
//                    . $organisatie->naam . "\n");
            $item = array(
                "almanakId" => ((string) $organisatie->systemId->systemId),
                "almanakName" => ((string) $organisatie->naam),
                "almanalComment" => null);
            global $results;
            $results[] = $item;
        }
    } else if ($child->getname() == "organisaties") {
        parse_orgs($child);
    } else
        die("\n" . $child->getname());
}
    // process results        
    foreach ($results[0] as $key => $value) {
        $keys[] = $key;
    }
    $header = '"' . implode('", "', $keys) . '"' . "\n";

    foreach ($results as $result) {
        $values = [];
        foreach ($keys as $key) {
            $values[] = $result[$key];
        }
        $row = '"' . implode('", "', $values) . '"' . "\n";
     //  print($row);die();
        $rows .= $row;
    }
    file_put_contents("source-almanak.csv", $header . $rows); 


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
        "almanakId" => ((string) $organisatie->systemId->systemId),
        "almanakName" => ((string) $organisatie->naam),
        "almanakComment" => ((string) $mother)
    );
    //push($item);
    global $results;

    $results[] = $item;

    $mother.= "$organisatie->naam - ";
    parse_orgs(($organisatie->organisaties), $mother);
}

?>