#/bin/bash -x
docker rm `docker ps -a -q`
docker rmi `docker images -q`
docker system prune -f -a
docker volume prune -f


