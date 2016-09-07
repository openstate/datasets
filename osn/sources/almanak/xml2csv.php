<?php

$filename = '20160815220000.xml';
$file = file_get_contents($filename);

if (empty($file))
    die("empty file");
$xml = simplexml_load_string($file);
$error = libxml_get_errors();
if (!empty($error)) {
    print("\n\n\nError:\n");
    print_r($error);
    die("\n\n\nEnd of Error\n");
}

$namespaces = $xml->getNamespaces();
$ns = $namespaces["p"];
print("\nNamespace P found? " . $ns . " \n");

foreach ($xml->children($ns) as $child) {// file contains organisaties & gemeenten & else?
    print("\nChild: " . $child->getname() . " Countsub: " . $child->count() . "\n\n");
    parse_orgs($child);

    // print_r($results);
    foreach ($results[0] as $key => $value) {
        $keys[] = $key;
    }
    $header = '"' . implode('"; "', $keys) . '"' . "\n";
   
    foreach ($results as $result) {
        $values;
        foreach ($keys as $key) {
            $values[] = $result[$key];
        }
        $row = '"' . implode('"; "', $values) . '"' . "\n";      
        $rows .= $row;    
    }
    //echo $header . $rows;
    file_put_contents("organisaties.csv", $header . $rows);
           
    //no parsing of gemeenten yet
    //print_r($results);    
    break;
}

// now save results to somedisk
// assumes $organisaties->getname() ==  organisaties
function parse_orgs($organisaties, $mother = null) {
    //print("getname organisaties " . $organisaties->getname() . "\n");
    if ($organisaties->getname() != "organisaties") {
        die("OOOOPS geen organisaties namelijk " . $organisaties->getname() . "\n");
    }

    foreach ($organisaties as $organisatie) {
        parse_org($organisatie, $mother);
    }
}

function parse_org($organisatie, $mother = null) {
    //print("getname organisatie " . $organisatie->getname() . "\n");
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
    global $results;
    $results[] = array(
        "systemId" => ((string) $organisatie->systemId->systemId),
        "name" => ((string) $organisatie->naam),
        "mother" => ((string) $mother)
    );

    $mother.= "$organisatie->naam - ";
    parse_orgs(($organisatie->organisaties), $mother);
}

?>