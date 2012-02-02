#!/bin/bash

launch_iperf=$(cat /etc/iperf_server.conf |grep on|wc -l)
if [ $launch_iperf -gt 0 ] ; then
    killall -9 iperf
    iperf -s 2>&1 >/dev/null &
else
    killall -9 iperf
fi
