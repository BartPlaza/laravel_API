#!/bin/bash

docker-compose build --build-arg UID=$(id -u) --build-arg UNAME=$(id -u -n)
docker-compose up -d

