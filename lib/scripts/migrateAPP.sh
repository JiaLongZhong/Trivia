
host=34.125.165.210
user=jlz6

[ -z "$1" ] && { source=.; echo "source set to local dir"; } || source=$1
if [ -z "$2" ]; then 
	echo "Must pass a destination"
	exit   
fi 
dest=$2 
echo "Pushing data" 
[ -x ./push.sh ] && sudo chmod +x ./push.sh || { echo "push.sh not found"; exit 1; }
    ./push.sh $source $user $host $dest


./push.sh /home/dwq2/Group/IT490-M22-TBD1/lib jlz6 34.125.165.210 /home/dropoff/lib

/home/dwq2/Group/IT490-M22-TBD1/lib