#!/bin/bash

#check backup folder for most recent backup and force it back into live folder
backupRef=$(ls -Art /home/$USER/backup | tail -n 1)
#shorten the backupRef to the name portion of the backup file name, gets rid of the timestamp
liveV=${backupRef:0:((${#backupRef})-21)}
echo "restoring backup"
#copies the backup to the live folder
cp -r /home/$USER/backup/$backupRef/* /home/$USER/live/$liveV