#!/bin/bash

sudo docker run -it --rm --name my-running-script -v "$PWD/../..":/usr/src/myapp -w /usr/src/myapp php:7.0-cli php sources/actoren/actoren2csv.php

