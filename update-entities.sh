#!/bin/bash

php app/console doctrine:generate:entities IC
php app/console doctrine:schema:update --force

