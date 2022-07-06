
source =$1
destination=$2
#make sure source and destination are absolute path
for filename in $source/*; do
        base=$(basename "$filename")
        echo $(basename "$filename")
        #migrations within the same minute will overwrite the backup
        # cp -r $destination/$base ./backup/$base.$(date "+%Y%m%d_+%H-%M")
        echo backed up $base
        #TODO verify file backed up
        #rm -rf $destination/$base
        echo deleted $base
        # cp -r $filename $destination
        #its a good idea to remove it from receive once it has been implemented
        #rm -rf $filename
        echo copied $filename to "$destination/$base"
done