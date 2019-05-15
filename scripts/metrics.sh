#!/bin/bash

BASE_PATH="./scripts"
PHPUNIT_PATH="$BASE_PATH/phpunit.sh"
INFECTION_PATH="$BASE_PATH/infection.sh"
PHPMETRICS_PATH="$BASE_PATH/phpmetrics.sh"

. "$PHPUNIT_PATH"
. "$INFECTION_PATH"
. "$PHPMETRICS_PATH"
