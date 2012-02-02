#!/bin/bash

launch_wvdial=$(ps -e|grep wvdial|wc -l)
if [ $launch_wvdial -gt 0 ] ; then
    killall -s SIGTERM wvdial
    sleep 3
    wvdial 2>&1 >/dev/null &
else
    wvdial 2>&1 >/dev/null &
fi
