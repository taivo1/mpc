#/bin/bash
#Ath0 distance provided by user.
athctrl -i wifi0 -d 123000
acktimeout=`cat /proc/sys/dev/wifi0/acktimeout`
echo $acktimeout >/proc/sys/dev/wifi0/slottime
