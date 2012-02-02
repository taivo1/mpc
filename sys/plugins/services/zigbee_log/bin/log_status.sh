#!/bin/bash
# Check squidBeeGW status.
# return 0 for off or return 1 for on.
ps -e | grep -i squidBeeGW | wc -l