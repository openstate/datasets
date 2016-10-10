#!/bin/bash

# run this command not through sudo or root but as plain user

#docker requires root privileges (when groups not configured)

name=$1


if [ -z $name ]; then echo "Source name is unset";exit; else echo "name is set to '$name'"; fi

sudo docker run -it --rm --name my-running-script -v "$PWD/..":/usr/src/myapp -w /usr/src/myapp php:7.0-cli php tests/test_source.php $name

#thus produce root owned files, this will flip user back


