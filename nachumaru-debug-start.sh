#!/bin/bash -x
docker-compose -f docker-compose.nachu.yml -f docker-compose.local.yml "$@"
