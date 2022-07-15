#!/bin/sh
sudo chmod +x /home/$USER/scripts/*.sh

case $1 in
    "backup")
        /home/$USER/scripts/implement.sh /home/$USER/live/* /home/$USER/backup
        ;;
    "restore")
        /home/$USER/scripts/do_backupRestore.sh
        ;;
    "dropoff")
        for file in /home/$USER/dropoff/* ; do
           /home/$USER/scripts/implement.sh $file /home/$USER/live
        done
        #/home/$USER/scripts/implement.sh /home/$USER/dropoff/* /home/$USER/live
        ;;
    *)
        echo "Usage: backup|restore|dropoff|live"
        ;;
esac
