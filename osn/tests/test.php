<?php

// 2016 Copyleft Lex Slaghuis, Open State Foundation 
// http://www.openstate.eu
// tests a concept master-table before approving the commit on the master file
// when succesful, also builds a new master-view
//assume we are in root of osn

$concept_masterfile = "./tests/concept-master-table.csv";
$old_masterfile = "./master-table.csv";
$sources = array("almanak", "cbs"); // first run "almanak", "cbs");// name of the source folders 
// first build a master-view for arjan
//todo test against old file

if (validate()) {
    echo ("If this went well you can update the masterfile with your concept master file");
    echo ("cp ./tests/concept-master-table.csv ./master-table.csv");
    echo ("git add ./master-table.csv");
    echo ("git commit");
} else
    die("We died\n");

build_masterview();

function build_masterview() {
    global $concept_masterfile, $sources, $concept_mastertable;
    $concept_mastertable = loadCSV($concept_masterfile); //should be in test dir

    foreach ($sources as $source) {
        $sourcetable = loadCSV("./sources/" . $source . "/source-" . $source . ".csv");
        //lookup values with index source.Id from $src in $dst 
        if (mergeTable($concept_mastertable, $sourcetable, $source)) {
            print("Table  $source is merged into masterview\n");
        } else {
            print("Table $source is not merged is merged into masterview\n");
        }
    }
    saveCSV($concept_mastertable, "./tests/concept-master-view.csv");
}

//lookup value in $dst,does it contain all values of the source file
function mergeTable(&$src, $dst, $source) {
    global $valid;
    global $nullvalues;
    $valid = 0;
    $nullvalues = 0;
    foreach ($src as &$sourceitem) {
        if (mergeItem($sourceitem, $dst, $source)) {
            continue;
        }
//      we did not find anything to merge, no problem through(although logging would be nice)
    }
    print("Merge of table $source succesfull; Number of records valid $valid of "
            . sizeof($src) . " while padding $nullvalues nullvalues" . "  \n");
    return true;
}

//lookup value in $dst
function mergeItem(&$sourceitem, $dst, $source) {
    global $nullvalues, $valid;

    foreach ($dst as $dstitem) {
//      print(" sourceitem:" . $sourceitem["$source" . "Id"]);

        if ($sourceitem["$source" . "Id"] == "") {// drop nulls
//          print " dropping\n";
            $nullvalues++;
            break;
        }
        if ($sourceitem["$source" . "Id"] == $dstitem["$source" . "Id"]) {
//          print(" sourceitem:" . $sourceitem["$source" . "Id"] . " dstitem:" . $dstitem["$source" . "Id"]);
//          var_dump($sourceitem);
//          var_dump($dstitem);
            $sourceitem["$source" . "Name"] = $dstitem["$source" . "Name"];
            $sourceitem["$source" . "Comment"] = $dstitem["$source" . "Comment"];
//          print "\tItem found\n";
            $valid++;
            return true;
        } else {
//            $dstitem["$source" . "Name"] = "";
//            $dstitem["$source" . "Comment"] = "";
        }
//           print(" sourceitem:" . $sourceitem["$source" . "Id"] . " dstitem:" . $dstitem["$source" . "Id"]);
//           print "\tnot equal,next\n";
    }
    $sourceitem["$source" . "Name"] = "";
    $sourceitem["$source" . "Comment"] = "";
//  print "\tItem:" . $sourceitem["$source" . "Id"] . " not found\n";
    return false;
}

function validate() {
    global $concept_masterfile, $old_masterfile;

    $concept_mastertable = loadCSV($concept_masterfile); //should be in test dir
    //validate_sources($concept_mastertable);
    // validate against old masterfile
    $old_mastertable = loadCSV($old_masterfile);
    foreach ($old_mastertable as $row) {
        var_dump($row);
        die('testing validate against old masterfile');
    }
}

function validate_sources($concept_mastertable) {
    global $sources;

    foreach ($sources as $source) {
        $sourcetable = loadCSV("./sources/" . $source . "/source-" . $source . ".csv");

        //lookup values with index source.Id from $src in $dst 
        if (validateTable($sourcetable, $concept_mastertable, $source)) {
            print("Table  $source as src is valid\n");
        } else {
            print("Table $source as src invalid\n");
            die("DIE");
        }
        //lookup values with index source.Id from $dst in $src
        if (validateTable($concept_mastertable, $sourcetable, $source)) {
            print("Table $source as dst is valid\n");
        } else {
            print("Table $source as dst invalid\n");
        }
    }
}

//lookup value in $dst,does it contain all values of the source file
function validateTable($src, $dst, $source) {
    global $valid;
    global $nullvalues;
    $valid = 0;
    $nullvalues = 0;
    foreach ($src as $sourceitem) {
        if (validateItem($sourceitem, $dst, $source)) {
            //   print("item valid\n");
            $valid++;
            continue;
        }
        print("Item: " . print_r($sourceitem, true) . "\tis invalid\n");
        return false;
        // we did not find it, error
    }
    print("Validaton of table $source succesfull; Number of records valid $valid of "
            . sizeof($src) . " while dropping $nullvalues nullvalues" . "  \n");
    return true;
}

//lookup value in $dst
function validateItem($sourceitem, $dst, $source) {
    global $nullvalues;

    foreach ($dst as $dstitem) {
        // print(" sourceitem:" . $sourceitem["$source" . "Id"]);

        if ($sourceitem["$source" . "Id"] == ""
        ) {// drop nulls
            // print " dropping\n";
            $nullvalues++;
            return true;
        }
        if ($sourceitem["$source" . "Id"] == $dstitem["$source" . "Id"]) {
//            print(" sourceitem:" . $sourceitem["$source" . "Id"] . " dstitem:" . $dstitem["$source" . "Id"]);
//            print "\tItem found\n";
            return true;
        }
//           print(" sourceitem:" . $sourceitem["$source" . "Id"] . " dstitem:" . $dstitem["$source" . "Id"]);
//           print "\tnot equal,next\n";
    }
    print "\tItem:" . $sourceitem["$source" . "Id"] . " not found\n";
    return false;
}

function loadCSV($source) {
    $fp = fopen($source, "r");
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
    $header = '"' . implode('";"', $keys) . '"' . "\n";
    //print($header);
    foreach ($results as $result) {
        $values = [];
        foreach ($keys as $key) {
            $values[] = $result[$key];
        }
        $row = '"' . implode('";"', $values) . '"' . "\n";
        $rows .= $row;
    }
    file_put_contents($target, $header . $rows);
}

?>
