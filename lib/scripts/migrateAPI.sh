[ ! $# -eq 3 ] && { echo "Usage: username_on_vm IP_remote_vm IP_for_MQ_in branch"; exit 1; } 
#34.138.248.175
./migrate.sh /home/$USER/Scripts/lib/scripts /home/$1/ $1 $2
#the paths need to be generalized
ssh -i ~/.ssh/id_rsa $1@$2 /home/$1/scripts/base.sh start $3 $1
 ./migrate.sh /home/$USER/Group/IT490-M22-TBD1/vendor /home/$1/dropoff $1 $2
 ./migrate.sh /home/$USER/Group/IT490-M22-TBD1/lib /home/$1/dropoff $1 $2
 ./migrate.sh /home/$USER/Group/IT490-M22-TBD1/rpc_api_consumer.php /home/$1/dropoff $1 $2

    ssh -i ~/.ssh/id_rsa $1@$2 /home/$1/scripts/base.sh "API" $3 $1