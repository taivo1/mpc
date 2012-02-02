#!/bin/bash
# usage: encrypt-user_part.sh [password] [base_path] [mountpoint]

#In meshlium user partition is on hda3. On other systems check this value to avoid problems.
device_path='/dev/hda3'

cd "$2/bin"
umount $device_path
cryptsetup luksClose /dev/mapper/user
./loop.sh "$1" "$device_path"
echo "$1" | cryptsetup luksOpen /dev/hda3 user
mkfs.ext3 /dev/mapper/user
if [ ! -r $3 ] ; then
	mkdir $3
fi
mount /dev/mapper/user $3
