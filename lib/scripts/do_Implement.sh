sudo chmod +x /home/dwq2/scripts/implement.sh
backupRef=$(ls -Art /home/dwq2/backup | tail -n 1)
case $1 in
    "backup")
        /home/dwq2/scripts/implement.sh /home/dwq2/live/* /home/dwq2/backup
        ;;
    "restore")
        /home/dwq2/scripts/implement.sh /home/dwq2/backup/$backupRef/* /home/dwq2/live/${$backupRef:0:-21}
        ;;
    "dropoff")
        /home/dwq2/scripts/implement.sh /home/dwq2/dropoff/* /home/dwq2/live
        ;;
    *)
        echo "Usage: backup|restore|dropoff|live"
        ;;
esac
#/home/dwq2/scripts/implement.sh /home/dwq2/dropoff /home/dwq2/live