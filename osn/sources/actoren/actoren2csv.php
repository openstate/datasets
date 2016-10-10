<?php

// 2016 Copyleft Lex Slaghuis, Open State Foundation 
// http://www.openstate.eu

$config = array(
    "server" => 'https://actorenregister.nationaalarchief.nl',
    "index" => '/oai-pmh?verb=ListRecords&metadataPrefix=eac-cpf',
    "path" => './sources/actoren',
    "source" => 'actoren',
    "errorlog" => './sources/actoren/error.log', // shoudl only be file name
);

main();
die();

function main() {
    global $config;
    while (1 == 1) {
        $link = $config['server'] . $config['index'];
        print("link is $link\n");
        extract_oai_page($link);
    }
}

function extract_oai_page($link) {
    $results = [];
    $file = file_get_contents($link);
  // print($file);
 //  die('temove me');
    if (empty($file))
        die("empty file");
    $xml = simplexml_load_string($file);
    $error = libxml_get_errors();
    if (!empty($error))
        die($error);

    $namespaces = $xml->getNamespaces();
   //var_dump($namespaces);
    
    
    $ns = $namespaces[""];
    if (!isset($ns))
        die("namespace error");

    //var_dump($xml->children($ns)->responseDate);// Datetime of response
    //var_dump($xml->children($ns)->request); // original uri
   // var_dump($xml->children($ns)->ListRecords);// the data ending w. resumpption token
    

    foreach ($xml->children($ns)->ListRecords->record as $child) {//
        
        print("\nlekker dan " . $child->getname());
        var_dump($child);
        var_dump($child->metadata);
        die();
        print ("\n");
 
        die();
        
        continue;
        die();
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
    die("END OF SCAN remove me");
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
}

/*
 * Code base that is re-used in the proces
 */

function saveCSV($results, $target) {
    foreach ($results as $result) {
        foreach ($result as $key => $value) {
            $keys[$key] = $key;
        }
    }
//var_dump($keys);

    $header = '"' . implode('","', $keys) . '"' . "\n";
//print($header);
    foreach ($results as $result) {
        $values = [];
        foreach ($keys as $key) {
            $values[] = $result[$key];
        }
        $row = '"' . implode('","', $values) . '"' . "\n";
//print($row);
        $rows .= $row;
    }
    file_put_contents($target, $header . $rows);
}

function error($string) {
    global $config;
    print($string);
    date_default_timezone_set('UTC');
    file_put_contents($config[errorlog], date("F j, Y, g:i a e O") . " $string\n", FILE_APPEND);
}
?>