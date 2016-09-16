<?php

// 2016 Copyleft Lex Slaghuis, Open State Foundation 
// http://www.openstate.eu

$configfile = "./tests/config.json";
$config = json_decode(file_get_contents($configfile));
print_r($config);

echo ("\n");
if (validate()) {
    echo ("\n\nIf this went well you can update the masterfile with your concept master file\n");
    echo ("cp ./tests/concept-master-table.csv ./master-table.csv\n");
    echo ("git add ./master-table.csv\n");
    echo ("git commit\n\n\n");
die('remove me');   
// build_masterview();
} else {
    die("We died validating\n");
}
die('remove me');
/*
 * Functions
 */


function build_masterview() {
    global $config;    //$concept_masterfile, $sources, $concept_mastertable;
       
    $concept_mastertable = loadCSV($config->concept_masterfile); //should be in test dir

    foreach ($config->sources as $source) {
        $sourcetable = loadCSV("./sources/" . $source . "/source-" . $source . ".csv");
        //lookup values with index source.Id from $src in $dst 
        if (mergeTable($concept_mastertable, $sourcetable, $source)) {
            print("\tTable  $source is merged into masterview\n");
        } else {
            print("\tTable $source is not merged is merged into masterview\n");
        }
    }
    saveCSV($concept_mastertable, "./tests/concept-master-view.csv");// should be in config
    print("\nMerged masterview is saved to disk\n");
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
            $sourceitem["$source" . "Name"] = $dstitem["$source" . "Name"];
            $sourceitem["$source" . "Comment"] = $dstitem["$source" . "Comment"];
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
    global $config;//    global $concept_masterfile, $old_masterfile;

    $concept_mastertable = loadCSV($config->concept_masterfile); //should be in test dir
    if (!validate_sources($concept_mastertable)) {
        die("validate_sources failed\n");
    } else {
        print("\nvalidate_sources success\n");
    }

// validate against old masterfile
    $old_mastertable = loadCSV($config->old_masterfile);
    if (!validate_oldmastertable($old_mastertable, $concept_mastertable)) {
        die("validate_oldmastertable failed\n");
    } else {
        print("\nvalidate_oldmastertable success\n");
    }
    return true;
}

function validate_oldmastertable($old_mastertable, $concept_mastertable) {
    foreach ($old_mastertable as $omrow) {
        if (!validate_oldmasterrow($concept_mastertable, $omrow)) {
            die("validate_oldmasterrow failed\n");
            return false;
        }
        //     print("\tvalidate_oldmasterrow success\n");
    }
    return true;
}

function validate_oldmasterrow($concept_mastertable, $omrow) {
    // print_r($omrow);
    foreach ($omrow as $omkey => $omvalue) {
        //    print("\t\tValidating in old mastertable key $omkey with value $omvalue \n");
        if ($omvalue == null) {
            //    print("\t\t\tskipping validation of old masterfile null value\n");
            continue;
        }
        if (!validate_oldmasterkey($concept_mastertable, $omrow, $omkey, $omvalue, array_keys($omrow))) {
            die("validate_oldmasterrow failed\n");
            return false;
        }
    }
    return true;
}

function validate_oldmasterkey($concept_mastertable, $omrow, $omkey, $omvalue, $keys) {
    foreach ($concept_mastertable as $cmrow) {
        if ($cmrow[$omkey] == $omvalue) {
            //    print("\t\tSearchkey $omkey Validated  with $cmrow[$omkey] with $omvalue \n");
            if (!validate_oldmastersubrowkeys($omrow, $omkey, $keys, $cmrow)) {
                die("validate_oldmastersubrowkeys failed\n");
            }
            //    print("\t\tSearchkey $omkey Validated with $cmrow[$omkey] with $omvalue success\n");
            return true;
        }
    }
    die("We did not find any matching key\n");
    return false;
}

function validate_oldmastersubrowkeys($omrow, $omkey, $keys, $cmrow) {
    foreach ($keys as $key) {
        if ($key == $omkey) {
            //          print("\t\t\tKey $key w $omkey already checked \n");
            continue;
        } else if ($omrow[$key] == null && $cmrow[$key] == null) {
            //         print("\t\t\tSkipping Both $key in oldmaster $cmrow[$key] and conceptmaster have null value\n");
            continue;
        } else if ($omrow[$key] == null || $cmrow[$key] == null) {
            var_dump($omrow);
            var_dump($cmrow);
            die("either one has null value");
        }

        if ($cmrow[$key] == $omrow[$key]) {
            //      print("\t\t\tRowkey Validated $key with $cmrow[$key] and $omrow[$key] \n");
            // everyhting ok, value matched, continue w. n. $key
            continue;
        } // else { // other field is either something else or null
        //var_dump($omrow); var_dump($cmrow);
        die("Row Matching  $key  error $cmrow[$key] != $omrow[$key]\n");
        return false;
    }
    return true;
}

function validate_sources($concept_mastertable) {
    global $config;
    global $valid;
    global $nullvalues;

    foreach ($config->sources as $source) {
        $sourcetable = loadCSV("./sources/" . $source . "/source-" . $source . ".csv");

        //var_dump($sourcetable);
       // var_dump($concept_mastertable);
  
        //lookup values with index source.Id from $src in $dst 
        if (validateTable($sourcetable, $concept_mastertable, $source)) {
            print("\tvalidateTable $source as src is success; Number of records valid $valid of "
                    . sizeof($sourcetable) . " while dropping $nullvalues nullvalues" . "\n");
        } else {
            die("validateTable $source as src failed\n");
            return false;
        }
        //lookup values with index source.Id from $dst in $src
        if (validateTable($concept_mastertable, $sourcetable, $source)) {
            print("\tvalidateTable $source as dst is success; Number of records valid $valid of "
                    . sizeof($concept_mastertable) . " while dropping $nullvalues nullvalues" . "\n");
        } else {
            die("validateTable $source as dst failed");
            return false;
        }
    }
    return true;
}

//lookup value in $dst,does it contain all values of the source file
function validateTable($src, $dst, $source) {
    global $valid;
    global $nullvalues;
    $valid = 0;
    $nullvalues = 0;
    foreach ($src as $sourceitem) {
        if (validateItem($sourceitem, $dst, $source)) { 
 // print("item valid\n");
            $valid++;
            continue;
        }
        print("Item: " . print_r($sourceitem, true) . "\tis invalid\n");
        return false; // we did not find it, error
    }
    return true;
}

//lookup value in $dst
function validateItem($sourceitem, $dst, $source) {
    global $nullvalues;

    //var_dump($dst);die();
    
    foreach ($dst as $dstitem) {
//         print(" sourceitem:" . $sourceitem["$source" . "Id"]);
  //          print(" sourceitem:" . $sourceitem["$source" . "Id"] . " dstitem:" . $dstitem["$source" . "Id"]);
//var_dump($sourceitem);
//            var_dump($dstitem);
//            die();      
        if ($sourceitem["$source" . "Id"] == "") {// print " dropping\n";
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
    print "\tItem:" . $sourceitem["$source" . "Id"] . " not found\n";//errrors
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
