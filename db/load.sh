#!/bin/sh

BASE_DIR=$(dirname $(readlink -f "$0"))
if [ "$1" != "test" ]
then
    psql -h localhost -U mueveme -d mueveme < $BASE_DIR/mueveme.sql
fi
psql -h localhost -U mueveme -d mueveme_test < $BASE_DIR/mueveme.sql
