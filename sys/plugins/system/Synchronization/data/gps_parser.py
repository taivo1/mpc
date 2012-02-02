#!/usr/bin/python

import time, logging
from subprocess import Popen,PIPE

#Get gprmc sentence
p1 = Popen(["gpspipe","-r","-n 6"],bufsize=100, stdout=PIPE)
p2 = Popen(["grep","GPRMC"], stdin=p1.stdout, stdout=PIPE)
p3 = Popen(["head","-n 1"], stdin=p2.stdout, stdout=PIPE)
output = p3.communicate()[0]
line=output.split(",")
# Get utc hour
hourstring = line[1].split(".")[0]
utchour = str(hourstring[0:2]), str(hourstring[2:4]), str(hourstring[4:6])
# Get utc date
datestring=line[9]
utcdate = str(datestring[0:2]) , str(datestring[2:4]), str(datestring[4:6])

#make an argument for date set
    #date accepts an argument in form:
    #mmddhhmmyy[.ss]
    #where mm is the optional number of the month (01-12), dd is the optional day of
    #the month, hh is the hour in 24 hour format (required) mm is the minutes (required),
    # yy is the optional last 2 digits of the year, and ss is the optional seconds.
dateset = utcdate[1],utcdate[0],utchour[0],utchour[1],utcdate[2]

print "%s%s%s%s%s" % (dateset)

