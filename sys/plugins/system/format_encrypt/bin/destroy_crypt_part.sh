#!/bin/bash

#This script destroy info needed to mount a crypted partition, so no mount will be possible and data will be lost.

#In meshlium user partition is on hda3. On other systems check this value to avoid problems.
device_path='/dev/hda3'

umount $device_path
dmesg > $device_path
cryptsetup luksClose /dev/mapper/user