#!/bin/bash

# load /etc/crontab.conf
if [ -r /etc/crontab.conf ] ; then
    crontab /etc/crontab.conf
else
    echo "No crontab.conf to load"
    exit 1
fi
