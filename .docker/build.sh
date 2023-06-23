#!/usr/bin/env bash

PATH=$(dirname "$0")
DOCKER=/usr/bin/docker
DOCKER_IMAGE_TAG='google-nlp'

if [[ ! -f "$DOCKER" ]]; then
  DOCKER=/usr/local/bin/docker
fi

echo  "using $DOCKER"

DOCKER_BUILDKIT=0 $DOCKER build -t ${DOCKER_IMAGE_TAG} "${PATH}"