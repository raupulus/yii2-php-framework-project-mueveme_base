#!/bin/sh

for i in "$(cat issues.txt)"
do
    ghi open -m "$i"
done
