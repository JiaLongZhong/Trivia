#!/bin/sh

source=$1
destination=$2
        
timestamp=$(date "+%Y-%m-%d_+%H-%M-%S")


# make sure source and destination are absolute paths or prepare for a bad day 
if [ -d $source ]; then 
        echo "Directory: $source"
		base=$(basename $source)
		echo $destination/$base
        [ -e $destination/$base ] && { echo "DIR: $destination/$base exists"; sudo cp -arv $destination/$base ./backup/$base.$timestamp; echo "backuped"; sudo cp -avr $source $destination; sudo rm -r $source; echo "moved"; } || { echo "DIR: $destination/$base does not exist"; sudo cp -avr $source $destination; sudo rm $source; echo "moved"; }
		#[ -e $destination/$base ] || { echo "DIR: $destination/$base does not exist"; sudo mv $source $destination/$base; }
    else 
        echo "File: $source" 
		base=$(basename $source)
		[ -e $destination/$base ] && { echo "File: $destination/$base exists"; sudo cp -r -b $destination/$base ./backup/$base.$timestamp; sudo cp -avr $source $destination/$base; sudo rm -f $source; }  || { sudo mv $source $destination; }
    fi
exit 0

for filename in $source; do 
	[-e $destination/$filename]
	base=$(basename "$filename") 
	echo $(basename "$filename") 
	
	# note, migrations within the same second will overwrite the backups
    echo $destination/$base 
	if [ -e $destination/$base ]; then 
      		sudo cp -r $destination/$base ./backup/$base.$timestamp
            echo "backuped $destination/$base to ./backup/$base.$timestamp"
			if [ -f ./backup/$base.$timestamp ]; then
				rm -rf $destination/$base 
				echo "removed $destination/$base"
			fi
			echo backed up $base to ./backup/$base.$timestamp
	else
		echo "file $destination/$base not found, it's a new file"
	fi
	if [ -d $source ]; then 
        sudo cp -r $filename $destination/$base
    else 
        echo "File: $source" 

    fi

	sudo mv $filename $destination
	# maybe it •s a good idea to remove it from receive once it has been implemented 
	# rm -rf $filename 
	echo copied $filename to "$destination/$base" 
done 
mkdir -p ./dropoff/

function backup 
{
	sudo cp -r -b $destination/$base ./backup/$base.$timestamp
	echo "backuped $destination/$base to ./backup/$base.$timestamp"
}
function liveFolder 
{
	sudo cp -r $source $destination/$base
	echo "implemented $source to $destination"
}