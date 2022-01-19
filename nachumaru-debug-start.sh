#!/bin/bash -x
docker-compose -f docker-compose.nachu.yml -f docker-compose.debug.yml -f docker-compose.pma.yml "$@"
