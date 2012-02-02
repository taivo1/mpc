#!/bin/bash

launch_gps_time=$(cat /etc/gps_time.conf |grep on|wc -l)
if [ $launch_gps_time -gt 0 ] ; then
    gpsd=$(ps -e |grep gpsd)
    if [ -n "$gpsd" ] ; then
        date=`gps_parser.py`
        echo "date $date"
    else
        echo "gpsd not active"
    fi
else
    echo "gps not configured to actualize date"
fi