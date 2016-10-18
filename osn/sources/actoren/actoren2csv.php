<?php

// 2016 Copyleft Lex Slaghuis, Open State Foundation 
// http://www.openstate.eu

$config = array(
    "server" => 'https://actorenregister.nationaalarchief.nl',
    "index" => '/oai-pmh?verb=ListRecords&metadataPrefix=eac-cpf',
    "resumptionpath" => '/oai-pmh?verb=ListRecords&resumptionToken=',
    "path" => './sources/actoren',
    "source" => 'actoren',
    "errorlog" => './sources/actoren/error.log', // shoudl only be file name
    "target" => './sources/actoren/source-actoren.csv'
);

// link https://actorenregister.nationaalarchief.nl/oai-pmh?verb=ListRecords&metadataPrefix=eac-cpf

$results = [];

main();
die();

function main() {
    global $config, $results;
    $uri = $config['server'] . $config['index'];

    while ($res = extract_oai_page($uri)) {
        $uri = $config['server'] . $config['resumptionpath'] . $res;
    }

    saveCSV($results, $config['target']);
}

function extract_oai_page($link) {
    global $results;
    print("Starting extractor page w $link\n");
    $file = file_get_contents($link);

    if (empty($file)) {
        die("empty file");
    }
    $xml = simplexml_load_string($file);
    $error = libxml_get_errors();
    if (!empty($error)) {
        die($error);
    }

    $arr = json_decode(json_encode($xml), 1); //magic

    $res = $arr["ListRecords"]["resumptionToken"];

    if (!isset($res)) {
        print("No resumptiontoken found.. assuming last page\n");
        return false;
    }

    foreach ($arr["ListRecords"]["record"] as $child) {//
        $cpfDes = $child["metadata"]["eac-cpf"]["cpfDescription"];
        $id = $cpfDes["identity"]["entityId"];
        $name = $cpfDes["identity"]["nameEntry"]["part"];

        if (is_array($name)) {//  var_dump($cpfDes["identity"]);
            continue;
        }

        $comment = implode("-", $cpfDes["description"]["existDates"]["dateRange"]);

        print ("id $id name $name comment $comment\n");

        $results[] = array("actorenId" => $id, "actorenName" => $name,
            "actorenComment" => "$comment");
    }
    print("exiting extract_oai_page ...\n");
    return $res;
}

/*
 * Libs
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
