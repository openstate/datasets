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
* Sources; does it contain all identifiers from all approved^ sources. Does
it not contain identifiers that are not identified in sources?
Note approved^: a source that was succesfully imported and is listed in the
array of 'sources' in test.php. --> config.json 
This implies new sources should be committed and tested (on github) before
commiting to a new masterfile!!!!!
*the old master file. does it contain all the values of the old masterfile
(and more). Are the values identical or not, expain why something needs to
be updated.


