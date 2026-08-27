#!/bin/bash

docker exec aegis-ca-local mysqldump -u root -p --no-data aegis_ca_local > /tmp/schema.sql

docker cp aegis-ca-local:/tmp/schema.sql ./schema.sql