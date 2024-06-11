#!/bin/bash

set -e
export uid="$(id -u)"
export gid="$(id -g)"
export DOCKER_BUILDKIT=1

dc_loc="docker compose --ansi never"

$dc_loc build --parallel
$dc_loc up -d

container="test-php"
ATTEMPTS=0
MAX_ATTEMPTS=20
CHECK_INTERVAL=5
until [ $ATTEMPTS -eq $MAX_ATTEMPTS ]; do
    status=$(docker inspect --format='{{.State.Status}}' $container)
    if [ "$status" = "running" ]; then
        echo "$container is $status"
        break
    else
        ATTEMPTS=$((ATTEMPTS + 1))
        echo "$ATTEMPTS/$MAX_ATTEMPTS, $container $status..."
        sleep $CHECK_INTERVAL
    fi
done

$dc_loc exec -T nginx nginx -s reload
