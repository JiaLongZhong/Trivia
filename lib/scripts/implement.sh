#!/bin/sh

source=$1
destination=$2
        
# make sure source and destination are absolute paths or prepare for a bad day 
if [ -d $source ]; then 
        echo "Directory: $source"
        #source+='/*'
    else 
        echo "File: $source" 
    fi

for filename in $source; do 
    
	base=$(basename "$filename") 
	echo $(basename "$filename") 
	timestamp=$(date "+%Y-%m-%d_+%H-%M-%S")
	# note, migrations within the same second will overwrite the backups
    echo $destination/$base 
	if [ -e $destination/$base ]; then 
      		cp -r $destination/$base ./backup/$base.$timestamp
            echo "backuped $destination/$base to ./backup/$base.$timestamp"
	fi
	echo backed up $base 
	if [ -f ./backup/$base.$timestamp ]; then
		rm -rf $destination/$base 
		echo deleted $base 
	else
		echo $(basename "$filename") " backup file not found, Saving Again"
		cp -r $destination/$base ./backup/$base.$timestamp
        rm -rf $destination/$base 
        echo deleted $destination/$base 
	fi
	mv $filename $destination 
	# maybe it •s a good idea to remove it from receive once it has been implemented 
	# rm -rf $filename 
	echo copied $filename to "$destination/$base" 
done 
mkdir -p ./dropoff/