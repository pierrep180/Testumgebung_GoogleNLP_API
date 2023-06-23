#!/usr/bin/env bash

DOCKER=/usr/bin/docker
DOCKER_IMAGE_TAG='google-nlp'
PROJECT_DIRECTORY=$(cd "$(dirname "$0")/.." && pwd)

if grep -q Microsoft /proc/version; then
  if [[ 'mnt' == $(echo "${PROJECT_DIRECTORY}" | cut -d'/' -f 2) ]]; then

    WINDOWS_DRIVE=/$(echo "${PROJECT_DIRECTORY}" | cut -d'/' -f 3)

    echo "Creating mount target $WINDOWS_DRIVE"
    [[ -d ${WINDOWS_DRIVE} ]] || sudo mkdir "${WINDOWS_DRIVE}"

    echo "Mounting drive /mnt$WINDOWS_DRIVE to $WINDOWS_DRIVE"
    sudo mount --bind "/mnt$WINDOWS_DRIVE" "${WINDOWS_DRIVE}"

    PROJECT_DIRECTORY=$(echo "${PROJECT_DIRECTORY}" | cut -c 5-)
  fi
fi

MYSQL_DATA_DIRECTORY="$PROJECT_DIRECTORY/.docker/mariadb/data"

if [[ ! -f "$DOCKER" ]]; then
  DOCKER=/usr/local/bin/docker
fi

echo  "using $DOCKER"

$DOCKER run -it --rm \
  -p 8080:80 \
  -p 33060:3306 \
  -p 63790:6379 \
  -v "${PROJECT_DIRECTORY}":/var/www/html \
  -v "${MYSQL_DATA_DIRECTORY}":/var/lib/mysql \
  -w /var/www/html \
  ${DOCKER_IMAGE_TAG}
