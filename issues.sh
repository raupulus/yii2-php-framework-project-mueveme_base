#!/bin/sh

while read i
do
    ghi open -m "$i"
done < issues.txt
