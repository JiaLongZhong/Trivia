
[ ! $# -eq 3 ] && { echo "Usage: username_on_vm IP_remote_vm IP_for_MQ_in branch"; exit 1; } 
#34.138.248.175
./migrate.sh /home/$USER/Scripts/lib/scripts /home/$1/ $1 $2
#the paths need to be generalized
ssh -i ~/.ssh/id_rsa $1@$2 /home/$1/scripts/base.sh start $3 $1
./migrate.sh /home/$USER/Group/IT490-M22-TBD1/static /home/$1/dropoff/static $1 $2
./migrate.sh /home/$USER/Group/IT490-M22-TBD1/partials /home/$1/dropoff/partials $1 $2
./migrate.sh /home/$USER/Group/IT490-M22-TBD1/vendor /home/$1/dropoff/vendor $1 $2
./migrate.sh /home/$USER/Group/IT490-M22-TBD1/lib/helpers.php /home/$1/dropoff/lib $1 $2
./migrate.sh /home/$USER/Group/IT490-M22-TBD1/lib/index.php /home/$1/dropoff/lib $1 $2
./migrate.sh /home/$USER/Group/IT490-M22-TBD1/README.md /home/$1/dropoff $1 $2
#copies all php outside of the lib folder to the dropoff folder
for file in /home/$USER/Group/IT490-M22-TBD1/*.php; do
	./migrate.sh $file /home/$1/dropoff $1 $2
done
ssh -i ~/.ssh/id_rsa $1@$2 /home/$1/scripts/base.sh APP $3 $1
#move changes to the live folder
#ssh -i ~/.ssh/id_rsa $1@$2 /home/$1/scripts/do_Implement.sh dropoff