#!/bin/bash

#In meshlium user partition is on hda3. On other systems check this value to avoid problems.
device_path='/dev/mapper/user'


umount $device_path
cryptsetup luksClose /dev/mapper/user