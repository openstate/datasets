#OSN tests

Github URL: https://github.com/openstate/datasets/tree/master/osn

###TESTS
Whenever a new master file is sent, tests will be run to make sure:
* no data is removed by accident
* no data is changed by accident
* new inserted data is shown
* new inserted data is tested against the source
* etc

###PROCESS OF TESTING

By running test.sh through docker, an application is run with a new
concept-master-file. This file is tested against:
* Sources; For every approved^ source it checks:
a. is every id in the source available in the concept-masterfile
b. is every id in the concept masterfile available in the source
* Old master file. For each row it checks for each key in the old masterfile:
a. that the concept masterfile has a row containing the same key
b. and that this row contains identical values (both null or equal)
// the latter should be updated when loading a new data file to allow for additions
* Build  an new masterview


Note approved^: a source that was succesfully imported and is listed in the
array of 'sources' in test.php. --> config.json. This implies new sources should be committed and tested (on github) before
commiting to a new masterfile!!!!!

