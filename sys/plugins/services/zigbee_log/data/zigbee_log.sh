#!/bin/bash
if [ -f /etc/zigbee_log.conf ] ; then
        . /etc/zigbee_log.conf
else
        echo "Error configuration file not found"
        exit 1
fi

if [ "x$exec" = "xtrue" ] ; then
        squidBeeGW $port >> $path 2>&1 &
else
        echo "SquidBeeGW stopped"
fi



