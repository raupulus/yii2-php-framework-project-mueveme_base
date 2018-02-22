#!/bin/sh

if [ "$1" = "travis" ]
then
    psql -U postgres -c "CREATE DATABASE mueveme_test;"
    psql -U postgres -c "CREATE USER mueveme PASSWORD 'mueveme' SUPERUSER;"
else
    [ "$1" != "test" ] && sudo -u postgres dropdb --if-exists mueveme
    [ "$1" != "test" ] && sudo -u postgres dropdb --if-exists mueveme_test
    [ "$1" != "test" ] && sudo -u postgres dropuser --if-exists mueveme
    sudo -u postgres psql -c "CREATE USER mueveme PASSWORD 'mueveme' SUPERUSER;"
    [ "$1" != "test" ] && sudo -u postgres createdb -O mueveme mueveme
    sudo -u postgres createdb -O mueveme mueveme_test
    LINE="localhost:5432:*:mueveme:mueveme"
    FILE=~/.pgpass
    if [ ! -f $FILE ]
    then
        touch $FILE
        chmod 600 $FILE
    fi
    if ! grep -qsF "$LINE" $FILE
    then
        echo "$LINE" >> $FILE
    fi
fi
