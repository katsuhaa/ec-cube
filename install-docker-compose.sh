#!/bin/bash -x
sudo curl -L https://github.com/docker/compose/releases/download/2.2.3/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
docker-compose --version

