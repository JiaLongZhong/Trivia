[ ! $# -eq 3 ] && { echo "Usage: username_on_vm | IP_remote_vm | IP_for_MQ_in branch"; exit 1; } 

 ./migrate.sh /home/$USER/Scripts/lib/scripts /home/$1/ $1 $2
 ssh -i ~/.ssh/id_rsa $1@$2 /home/$1/scripts/base.sh start $3 $1
 ./migrate.sh /home/$USER/Group/IT490-M22-TBD1/sql /home/$1/dropoff/sql $1 $2
 ./migrate.sh /home/$USER/Group/IT490-M22-TBD1/services /home/$1/dropoff/services $1 $2
 ./migrate.sh /home/$USER/Group/IT490-M22-TBD1/vendor /home/$1/dropoff/vendor $1 $2
 ./migrate.sh /home/$USER/Group/IT490-M22-TBD1/lib /home/$1/dropoff/lib $1 $2
for file in /home/$USER/Group/IT490-M22-TBD1/rpc_*.php; do
	./migrate.sh $file /home/$1/dropoff $1 $2
done


ssh -i ~/.ssh/id_rsa $1@$2 /home/$1/scripts/base.sh DB $3 $1