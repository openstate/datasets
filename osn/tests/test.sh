#!/bin/bash

# run this command not through sudo or root but as plain user

#docker requires root privileges (when groups not configured)
sudo docker run -it --rm --name my-running-script -v "$PWD/..":/usr/src/myapp -w /usr/src/myapp php:7.0-cli php tests/test.php

#thus produce root owned files, this will flip user back
sudo chown $USER concept-master-view.csv 

