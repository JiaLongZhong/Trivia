
#source = $1
#user = $2
#host = $3
#dest = $4
#ssh_key = $5 ~/.ssh/id_rsa
if [ $# -ne 5 ]; then
    if [ $# -eq 4 ]; then
        echo "No ssh key specified using default"
        ssh_key=~/.ssh/id_ed25519
    else { echo "Usage: push.sh source user host dest ssh_key(blank for default)"; exit 1; }
    fi
    else
        ssh_key=$5
fi

scp -i $ssh_key -r $1 $2@$3:$4