#!/bin/bash

# usage: activate_crypt_part.sh [password] [mountpoint]

#In meshlium user partition is on hda3. On other systems check this value to avoid problems.
device_path='/dev/hda3'

echo "$1" | cryptsetup luksOpen $device_path user
mount /dev/mapper/user $2