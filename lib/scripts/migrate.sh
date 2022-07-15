[ ! $# -eq 4 ] && { echo "Usage: source_file_path dest_file_path dest_username destination_host_ip"; exit; }
#get source from arg or default to current dir
[ -z "$1" ] && { source=.; echo "source set to local dir"; } || source=$1
[ -z "$2" ] && { echo "must pass a destination"; exit; } || dest=$2
[ -z "$3" ] && { echo "must pass a user"; exit; } || user=$3
[ -z "$4" ] && { echo "must pass a host"; exit; } || host=$4
echo "Pushing data" 
#check if push.sh it is executable
[ -x ./push.sh ] && { sudo chmod +x ./push.sh; } || { echo "push.sh not found"; exit 1; }
./push.sh $source $user $host $dest
