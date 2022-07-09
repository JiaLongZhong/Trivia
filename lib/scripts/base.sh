
[ ! $# -eq 2 ] && { echo "Usage: username_on_vm APP|MQ|API|DB"; exit 1; } 



[ ! -d "/home/$1/dropoff" ] && sudo mkdir dropoff
[ ! -d "/home/$1/backup" ] && sudo mkdir backup
[ ! -d "/home/$1/live" ] && sudo mkdir live
[ ! -d "/home/$1/scripts" ] && { sudo mkdir scripts; echo "the other scripts are missing"; exit 1; }


case $2 in
	"APP")
        sudo apt update
        sudo apt upgrade

		sudo apt install php php-bcmath
		 [ sudo systemctl status apache2 | grep "Status: install" ] && sudo apt install apache2
        echo "pulls all folders lib, partials, vendor and static from the vm"
        echo "setup apache to serve the app"
        #brings over config.php and configmq.php
		;;
	"MQ")
        sudo apt update
        sudo apt upgrade
        [ sudo systemctl status rabbitmq-server | grep "Status: install" ] && sudo apt install rabbitmq-server
		#sudo apt install rabbitmq-server
		sudo rabbitmq-plugins enable rabbitmq_management
		sudo rabbitmqctl add_user dwq2 dwq2
		sudo rabbitmqctl set_user_tags dwq2 administrator 
		sudo rabbitmqctl set_permissions dwq2  "." "." ".*"
		sudo rabbitmqctl add_user smit smit
		sudo rabbitmqctl set_user_tags smit administrator 
		sudo rabbitmqctl set_permissions smit "." "." ".*"
		sudo rabbitmqctl add_user API API
		sudo rabbitmqctl set_user_tags API administrator 
		sudo rabbitmqctl set_permissions API "." "." ".*"
		sudo rabbitmqctl add_user DB DB
		sudo rabbitmqctl set_user_tags DB administrator 
		sudo rabbitmqctl set_permissions DB "." "." ".*"
		sudo rabbitmqctl add_user APP APP
		sudo rabbitmqctl set_user_tags APP administrator 
		sudo rabbitmqctl set_permissions APP "." "." ".*"
        sudo apt install rsyslog
        [ sudo systemctl status rsyslog | grep "Status: install" ] && sudo apt install rsyslog
        echo "go to /etc/rsyslog.conf and uncomment the lines of provides UCP syslog reception then restart rsyslog"
        echo "sudo systemctl restart rsyslog"
        
		;;
    "DB")
        sudo apt upgrade
        sudo apt update
        [ sudo systemctl status mysql-server | grep "Status: install" ] && sudo apt install mysql-server
        sudo mysql_secure_installation
        [ sudo systemctl status rsyslog | grep "Status: install" ] && sudo apt install rsyslog
        echo "go to /etc/rsyslog.conf and change IP to the IP of the MQ server then restart rsyslog"
        echo "sudo systemctl restart rsyslog"

        echo "pull SQL tables and run init-db.php"
        ;;
    "API")
        echo "pulls API files and runs ~init-api.php"
        ;;
    *)
        echo "Usage: username_on_vm APP|MQ|API|DB|*for none"
        ;;
    esac