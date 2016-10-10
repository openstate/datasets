<?php

// 2016 Copyleft Lex Slaghuis, Open State Foundation 
// http://www.openstate.eu

$github = "https://raw.githubusercontent.com/openstate/datasets/master/osn/sources";
$source = $argv[1];
if ($source == null)
    die("DIE SOURCE IS NULL\n");
print("\n");
if (validate()) {
    echo ("\n\nIf this went well you can update the sourcefile with your concept-source file\n");
    echo ("cp ./tests/concept-source.csv ./sources/[nameofsource]/[nameofsource]-source.csv\n");
    echo ("git add ./sources/[nameofsource]/[nameofsource]-source.csv\n");
    echo ("git commit \"source file added\" \n\n\n");
} else {
    die("We died validating\n");
}
die();



/*
 *
 *  *   *   * Functions
 * 
 */

function validate() {
    global $source; //    global $concept_masterfile, $old_masterfile;

    $concept_sourcefile = "./sources/$source/concept-$source-source.csv";
    print("Loading " . $concept_sourcefile . "\n");


    $concept_sourcetable = loadCSV($concept_sourcefile);

    if (!validate_source($concept_sourcetable)) {
        die("validate_sources failed\n");
    } else {
        print("\nvalidate_sources success\n");
    }


    return true;
}

function validate_source($concept_sourcetable) {
    global $uri, $source, $github;

    $uri = $github . "/" . $source . "/source-" . $source . ".csv";
    print($uri . "\n");
    $sourcefile = file_get_contents($uri);
    $temp = "temp.tmp";
    file_put_contents($temp, $sourcefile);
    $sourcetable = loadCSV($temp);

    // completeness, each cbsID in source should be  in concepttable
    foreach ($sourcetable as $srcrow) {
        if (!validate_row($srcrow, $concept_sourcetable)) {
            die("validaterow sourcetable failed\n");
            return false;
        }
    }
    print("validate sourcetable success\n");
    // count insertions completeness, which cbsID in source is in ...
    $insertions = 0;
    foreach ($concept_sourcetable as $srcrow) {
        if (!validate_row($srcrow, $sourcetable)) {
            // die("validaterow concept_sourcetable failed\n");
            // return false;
            $insertions++;
        }
    }
    print("validate concept_sourcetable success with $insertions insertions\n");
    return true;
}

//lookup value in $dst
function validate_row($srcrow, $table) {

    foreach ($table as $dstrow) {
        if ($srcrow['cbsId'] == $dstrow['cbsId']) {
            //  print("Match found for " . $srcrow['cbsId'] . "\n");
            return true;
        }
    }
    print("No Match found for " . $srcrow['cbsId'] . " " . $srcrow['cbsName'] . " \n");
    return false;
}

function loadCSV($source) {

    $fp = @fopen($source, "r");
    if (!$fp)
        die("file not exists\n");

    $keys = fgetcsv($fp);

    // print_r($keys);
    while ($row = fgetcsv($fp)) {
        $record = [];
        for ($i = 0; $i < sizeof($keys); $i++) {
            $record["$keys[$i]"] = "$row[$i]";
        }

        $result[] = $record;
    }
    return $result;
}

function saveCSV($results, $target) {
    foreach ($results[0] as $key => $value) {
        $keys[] = $key;
    }
    $header = '"' . implode('","', $keys) . '"' . "\n";
    //print($header);
    foreach ($results as $result) {
        $values = [];
        foreach ($keys as $key) {
            $values[] = $result[$key];
        }
        $row = '"' . implode('","', $values) . '"' . "\n";
        $rows .= $row;
    }
    file_put_contents($target, $header . $rows);
}

?>
