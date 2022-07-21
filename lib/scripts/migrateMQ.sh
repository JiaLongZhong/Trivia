[ ! $# -eq 3 ] && { echo "Usage: username_on_vm IP_remote_vm IP_for_MQ_in branch"; exit 1; } 

./migrate.sh /home/$USER/Scripts/lib/scripts /home/$1/ $1 $2
ssh -i ~/.ssh/id_rsa $1@$2 /home/$1/scripts/base.sh start $3 $1

ssh -i ~/.ssh/id_rsa $1@$2 /home/$1/scripts/base.sh MQ $3 $1
sleep 20s
pause
ssh -i ~/.ssh/id_rsa $1@$2 /home/$1/scripts/base.sh MQ $3 $1