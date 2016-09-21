<?php

// 2016 Copyleft Lex Slaghuis, Open State Foundation 
// http://www.openstate.eu
// get an overview from http://dataderden.cbs.nl/ODataApi/
// http://dataderden.cbs.nl/ODataApi/OData/45006NED/Gemeenten
// http://dataderden.cbs.nl/ODataApi/OData/45009NED/Provincies
// http://dataderden.cbs.nl/ODataApi/OData/45025NED/GemeenschappelijkeRegelingen
// 
// http://opendata.cbs.nl/ODataApi/
// (But we now take in everything!)
// http://opendata.cbs.nl/ODataApi/OData/80202ned/RegioS (Corop regios)
//http://opendata.cbs.nl/ODataApi/OData/83163ENG/DataProperties

$config = array(
    "servers" => array('http://dataderden.cbs.nl', 'http://opendata.cbs.nl'),
    "blacklist_datasets" => array(),
    "blacklist_id_values" => array(
        "WBL", "NL00", "NL10", "99900", "99901", "99902", "99903", 
        "GM9995", "GM9994" ,"GM9993", "GM9992" ,"GM9991" ,"GM9990"
    ),
    "blacklist_name_values" => array(
        "Waterschappen met traditionele taken", "Waterschappen met waterzuiveringstaak", "Alle waterschappen tezamen"
        , "Nederland", "G4"
    ),
    "index" => '/ODataApi',
    "path" => './sources/cbs',
    "source" => 'cbs',
    "errorlog" => './sources/cbs/error.log', // shoudl only be file name
    "whitelist" => array("Gemeenten", "Provincies", "Waterschappen",
        "GemeenschappelijkeRegelingen"));

// not sure about "RegioS"  (corop regio's), Gemeentenaam_1 (?), Gemeentecode_72
//, Gemeentenaam_73, Gemeentecode_1, Waterschappen, GGDRegio
// test error log consequtive ly

main();
die();

function main() {
    global $config; //get_map();//gets all specified fieldnames from all cbs endpoints
    //extract_data();
    // die(); // let this one run to generate data and then start transform
    $resume_results = json_decode(file_get_contents($config["path"] . "/concept-extracted-" . $config['source'] . ".json"));


    transform_data($resume_results);
    //  load does the validation against old source
    // and puts new file in place ; also provides a delta
    // and logs the delta against a changes file
}

function extract_data() {
    global $config, $results;
    $results = [];

    foreach ($config[servers] as $server) {

// Create DOM from URL or file
        $file = file_get_contents($server . $config[index]);
        $html = new DOMDocument();
        $html->loadHTML($file);

// Find all links & load page & search for links
        foreach ($html->getElementsByTagName('a') as $element) {
            get_data_set($server, $element->nodeValue);
        }
    }

    // save extract results        
    file_put_contents($config["path"] . "/concept-extracted-" . $config['source'] . ".json", json_encode($results)); //allows resuming
    saveCSV($results, $config["path"] . "/concept-extracted-" . $config['source'] . ".csv");
}

function transform_data($resume_results) {
    global $results, $config; //import results from extractor
    if (isset($resume_results))
        $results = $resume_results;
   
    $target = [];
    print("Aantal originele records " . sizeof($results) . "\n");
    foreach ($results as $result) {


        // ignore blacklist stuff
        if (in_array($result->cbsId, $config['blacklist_id_values']) || in_array($result->cbsName, $config['blacklist_name_values'])) {
           // print("\nDROPPING " . $result->cbsId . "\n");
            continue;
        }

        if (isset($target[$result->cbsName])) {
            //  print " reeds gevonden " . $target[$result->cbsName]["cbsName"] . ": ";
        } else {
            //   print" nieuw! " . $result->cbsName . "\n ";
        }
        $id = $result->cbsId; // should be instead Id colomn (bactrack extractor)
     //   print("\n dus:" . $result->cbsName . ":\n");

        if (is_cbsId($id)) {
            $target[$result->cbsName]["cbsId"] = $id;
            $target[$result->cbsName]["cbsIdCount"] = !isset($target[$result->cbsName]["cbsIdCount"]) ? 1 :
                    $target[$result->cbsName]["cbsIdCount"] + 1;
        } else if (is_cbsShort($id)) {
            $target[$result->cbsName]["cbsShort"] = $id;
            $target[$result->cbsName]["cbsShortCount"] = !isset($target[$result->cbsName]["cbsShortCount"]) ? 1 :
                    $target[$result->cbsName]["cbsShortCount"] + 1;
        } else if (is_cbsLong($id)) {
            $target[$result->cbsName]["cbsLong"] = $id;
            $target[$result->cbsName]["cbsLongCount"] = !isset($target[$result->cbsName]["cbsLongCount"]) ? 1 :
                    $target[$result->cbsName]["cbsLongCount"] + 1;
        } else {
            die("FAIL");
        }
        $target[$result->cbsName]["cbsName"] = $result->cbsName; // make conditional 
        $target[$result->cbsName]["cbsComment"] = $result->cbsComment; // make cs list                
    }

     print("Aantal deduped records " . sizeof($target) . "\n");


    $records = array_values($target);
 //   var_dump($records[0]);

    saveCSV($records, $config["path"] . "/concept-transformed-source-" . $config['source'] . ".csv");
}

// as GM1345 , fist 2 letters are GM, PV, WS, GR then 4 bytes, optionally extended with letters and numbers
// exceptions: GM3136S03 (stadsdeel)
function is_cbsId($id) {
    //print($id);
    $prefix = substr($id, 0, 2);
    if ($prefix == "WS") {
        if (strlen($id) == 4) {
            $number = substr($id, 2, 4);
            if (ctype_digit($number[0]) && ctype_digit($number[1])) {
                return true;
            }// return false
        }//return false
    } else if ($prefix == "PV") {
        if (strlen($id) == 4) {
            $number = substr($id, 2, 4);
            if (ctype_digit($number[0]) && ctype_digit($number[1])) {
                return true;
            }// return false
        }//return false
    } else if (strlen($id) >= 6) {// case GM but perhaps broader
        if (( $prefix == "GM" || $prefix == "WS" || $prefix == "GR")) {

            $number = substr($id, 2, 6);
            if (ctype_digit($number[0]) && ctype_digit($number[1]) && ctype_digit($number[2]) && ctype_digit($number[3])) {

                if (strlen($id) > 6) {// it has an extension
                    $extension = substr($id, 6, strlen($id));

                    if (ctype_upper($extension[0])) {
                        $remain = substr($extension, 1);
                        foreach (str_split(substr($extension, 1)) as $ch) {
                            if (!
                                    ( ctype_upper($ch) || ctype_digit($ch) )) {
                                return false;
                            }
                        }
                        return true; //every thing ok
                    }
                } else {
                    // no extension 
                    return true;
                }
            }
        }
    }
    return false;
}

// Some word / words? then a space and a cbsShort
function is_cbsLong($id) {

    $word = explode(" ", $id);
   // print( " END: " . end($word) . ":\n");

    if (is_numeric(end($word))) {
     //   print("ends with numeric\n");
        return true;
    } else {
        print("does not end with numeric\n");
        die();
    }
}

// as 1234 4 bytes, but also 789 (zero accidently dropped)
function is_cbsShort($id) {
   // print($id . "\t");
    if (strlen($id) == 4) {
        foreach (str_split($id) as $ch) {
            if (! ctype_digit($ch)) {
                die("er is iets ID:$id mis 1");
                return false;
            }
        }
   //     print("we keuren CBSSHORT hem goed :$id:\n");
        return true;
    }
    return false;
}

function get_data_set($server, $link) {
    global $results, $config;
    //$link = $element->nodeValue;
    echo ($server . " link:" . $link . "\n");

    $sub = file_get_contents($server . $link);
    if (!$sub)
        return false;
    //print($sub);
    $json = json_decode($sub);
    // print_r($json);
    //die();
    foreach ($json->value as $record) {
        if (in_array($record->name, ($config['whitelist']))) {// we kunnen later ooknog die metadata pag pakken ipv dee
            // print_r($record->url . "\n");
            $data = file_get_contents($record->url);
            // print($data);
            $json_data = json_decode($data);
            foreach ($json_data->value as $organisatie) {
                print('cbsId: "'
                        . trim($organisatie->Key) . '" "'
                        . $organisatie->Title . '" "'
                        . $organisatie->Description . '"' . "\n");

                $item = array(
                    "cbsId" => ((string) trim($organisatie->Key)),
                    "cbsName" => ((string) $organisatie->Title),
                    "cbsComment" => ((string)
                    str_replace('"', "\'"
                            , str_replace("\r\n", '. <br>', $organisatie->Description)
                    )
                    ));
                //global $results;
                $results[] = $item;
            }
        }
    }
}

function saveCSV($results, $target) {
    foreach ($results as $result){
        foreach($result as $key => $value) {
        $keys[$key] = $key;
        }
    }
    //var_dump($keys);
        
    $header = '"' . implode('","', $keys) . '"' . "\n";
    print($header);
    foreach ($results as $result) {
        $values = [];
        foreach ($keys as $key) {
            $values[] = $result[$key];
        }
        $row = '"' . implode('","', $values) . '"' . "\n";
        print($row);
        $rows .= $row;
    }
    file_put_contents($target, $header . $rows);
}

// obsolete?
function mapIntoCSV($map, $target) {
    $file = file_get_contents($config[path] . "/govmap.json");
    $govmap = json_decode($file);
// convert govmap into rows
    foreach ($map as $key => $value) {
        $rows[] = array("word" => $key, "count" => $value);
    }
    saveCSV($rows, $target); //"./sources/cbs/govmap.csv");
}

/*
 * Code base that is re-used in the proces
 */

// function that get all fieldnames of CBS through crawling
//output 
function get_map() {
    global $govmap, $config;
    $govmap = [];

    foreach ($config[servers] as $server) {
        get_map_server($server);
    }
    file_put_contents($config[path] . "/map.json", json_encode($govmap));

// convert govmap into rows
    foreach ($govmap as $key => $value) {
        $govrows[] = array("word" => $key, "count" => $value);
    }
    saveCSV($govrows, $config[path] . "/map.csv");
}

// sub function to get both OD and dataderden 
function get_map_server($server) {
    global $config, $govmap;

    // Create DOM from URL or file
    $file = file_get_contents($server . $config[index]);
    $html = new DOMDocument();
    $html->loadHTML($file);

    // Find all links & load page & search for links
    foreach ($html->getElementsByTagName('a') as $element) {
        $link = $element->nodeValue;
        //  echo ("$link \n");

        if (!$sub = file_get_contents($server . $link . "/DataProperties")) {
            $e = error_get_last();
            error("exception $e caught and handled: " . $server . $link);

            sleep(2);
            if (!$sub = file_get_contents($server . $link . "/DataProperties")) {
                $e = error_get_last();
                error("repeat exception $e caught and handled: " . $server . $link . " now failing\n");
                continue;
            }
        }
        $json = json_decode($sub);

        foreach ($json->value as $record) {
            $key = $record->Key;
            isset($govmap[$key]) ?
                            $govmap[$key] = $govmap[$key] + 1 : $govmap[$key] = 1;
            //   print("$key w.v. $govmap[$key] \n");
        }
        //   print_r($govmap);die();
    }
}

function error($string) {
    global $config;
    print($string);
    date_default_timezone_set('UTC');
    file_put_contents($config[errorlog], date("F j, Y, g:i a e O") . " $string\n", FILE_APPEND);
}

?>