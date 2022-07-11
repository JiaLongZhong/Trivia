#!/bin/bash
host="34.138.248.175" 
user="dwq2"
echo "remote restore of back from "

#check backup folder for most recent backup and force it back into live folder
backupRef=$(ls -Art /home/dwq2/backup | tail -n 1)
echo $backupRef
liveV=${backupRef:0:((${#backupRef})-21)}
#$(echo "$backupRef" | grep -E -o '([0-9]{4}-[0-9]{2}-[0-9]{2}_+[0-9]{2}-[0-9]{2}-[0-9]{2})')
echo $liveV
echo 
echo "restoring backup"
#/home/dwq2/scripts/implement.sh /home/dwq2/backup/$backupRef /home/dwq2/live/${backupRef:0:-21}

cp -r /home/dwq2/backup/$backupRef/* /home/dwq2/live/$liveV