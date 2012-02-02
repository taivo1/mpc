#/bin/bash

# Usage mkfsext3.sh [mountpoint]

# Give a point to mount partition formated.

#In meshlium user partition is on hda3. On other systems check this value to avoid problems.
device_path='/dev/hda3'
encrypted_device_path='/dev/mapper/user'

umount $encrypted_device_path
umount $device_path
mkfs.ext3 $device_path
if [ ! -r $1 ] ; then
	mkdir -p $1
fi
mount $device_path $1
