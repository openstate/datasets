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
master-file. This file is tested against:
*the old master file
*different source files that are integrated earlier on.
*new source files that were the building ground for an update

