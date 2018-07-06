#!/bin/bash

docker-compose -f docker/prod/docker-compose.yml build
docker-compose -f docker/prod/docker-compose.yml up
