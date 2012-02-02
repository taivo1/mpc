#!/bin/bash

launch_ntp_time=$(cat /etc/ntp_time.conf |grep on|wc -l)
if [ $launch_ntp_time -gt 0 ] ; then
    ntpdate pool.ntp.org
else
    echo "ntpdate not configured to actualize date"
fi
