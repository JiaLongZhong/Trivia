
#host is the pointer to next VM 
#user being the user that the ssh will be using
host="34.138.248.175" 
user="dwq2"
#get source from arg or default to current dir
[ -z "$1" ] && { source=.; echo "source set to local dir"; } || source=$1
if [ -z "$2" ]; then 
	echo "Must pass a destination"
	exit   
fi 
dest=$2 
echo "Pushing data" 
[ -x ./push.sh ] && sudo chmod +x ./push.sh || { echo "push.sh not found"; exit 1; }
    ./push.sh $source $user $host $dest
#[ -d ./LastSentVersion ] && echo "copying data to lastSentVersion folder" || {mkdir ./LastSentVersion; echo "created lastSentVersion folder"; }
#cp -r $source ./LastSentVersion/$source.$dest
echo "Running remote migrate" 
#ssh -i ~/.ssh/id_rsa $user@$host /home/$user/scripts/do_Implement.sh dropoff