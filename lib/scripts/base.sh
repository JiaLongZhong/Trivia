
# $1 is the user of the machine this is runnin on (dwq2) replaced by $USER
# $1 is the type of server that this is being run on (app, db, mq, api)
# $2 Ip of MQ server
[ ! $# -eq 3 ] && { echo "Usage: APP|MQ|API|DB MQ_IP username_on_vm"; exit 1; } 



[ ! -d "/home/$USER/dropoff" ] &&  sudo mkdir  dropoff
[ ! -d "/home/$USER/backup" ] && sudo mkdir backup
[ ! -d "/home/$USER/live" ] && sudo mkdir live
[ ! -d "/home/$USER/scripts" ] && { sudo mkdir scripts; echo "the other scripts are missing"; exit 1; }

case $1 in
	"APP")
        sudo apt update
        sudo apt upgrade

		sudo apt install php php-bcmath composer 
		[ sudo systemctl status apache2 | grep "Status: install" ] && sudo apt install apache2
        
        echo "setup apache to serve the app"
        #setup rsyslog to log to the MQ vm 
        #TODO fix the echo is not working
        [ sudo systemctl status rsyslog | grep "Status: install" ] && { sudo apt install rsyslog; sudo chmod 666 /etc/rsyslog.conf; sudo echo "*.* @$2:514" >> /etc/rsyslog.conf; }
        sudo systemctl restart rsyslog
        #setup apache to serve the app from the live folder
        [ -e /home/$USER/dropoff/lib] && { echo "lib found"; } || { echo "lib not found, creating it now"; sudo mkdir -p /home/$USER/dropoff/lib; }
        [ -e /home/$USER/dropoff/lib/configmq.ini ] && { echo "mqconfig exists"; } || { echo "configmq.ini not found, creating it now"; sudo touch /home/$USER/dropoff/lib/configmq.ini; sudo chmod 660 /home/$USER/dropoff/configmq.ini; echo "brokerhost = $2" >> /home/$USER/dropoff/lib/configmq.ini; echo "brokerport = 5672" >> /home/$USER/dropoff/lib/configmq.ini; echo "brokeruser = APP" >> /home/$USER/dropoff/lib/configmq.ini; echo "brokerpass = APP" >> /home/$USER/dropoff/lib/configmq.ini; cp -f /home/$USER/dropoff/lib/configmq.ini /home/$USER/scripts/configmq.ini.bak; }
        #[ -e /home/$USER/live/lib/configmq.ini ] && { echo "configmq.ini not found, creating it now"; touch /home/$USER/live/lib/configmq.ini; echo "brokerhost = $2" >> /home/$USER/live/lib/configmq.ini; echo "brokerport = 5672" >> /home/$USER/live/lib/configmq.ini; echo "brokeruser = APP" >> /home/$USER/live/lib/configmq.ini; echo "brokerpass = APP" >> /home/$USER/live/lib/configmq.ini;}
		;;
	"MQ")
        sudo apt update
        sudo apt upgrade
        #setup rabbitmq and adds the users needed
        [ sudo systemctl status rabbitmq-server | grep "Status: install" ] && { sudo apt install rabbitmq-server; sudo rabbitmq-plugins enable rabbitmq_management; sudo rabbitmqctl add_user dwq2 dwq2; sudo rabbitmqctl set_user_tags dwq2 administrator; sudo rabbitmqctl set_permissions dwq2  "." "." ".*"; sudo rabbitmqctl add_user smit smit; sudo rabbitmqctl set_user_tags smit administrator; sudo rabbitmqctl set_permissions smit "." "." ".*"; sudo rabbitmqctl add_user API API; sudo rabbitmqctl set_user_tags API administrator; sudo rabbitmqctl set_permissions API "." "." ".*"; sudo rabbitmqctl add_user DB DB; sudo rabbitmqctl set_user_tags DB administrator; sudo rabbitmqctl set_permissions DB "." "." ".*"; sudo rabbitmqctl add_user APP APP; sudo rabbitmqctl set_user_tags APP administrator; sudo rabbitmqctl set_permissions APP "." "." ".*"; sudo systemctl restart rabbitmq-server; }
        [ sudo systemctl status rsyslog | grep "Status: install" ] && sudo apt install rsyslog
        #"go to /etc/rsyslog.conf and uncomment the lines of provides UCP syslog reception then restart rsyslog"
        #setup rsyslog to recieve logs over udp
        sudo chmod 666 /etc/rsyslog.conf
        sudo sed '17,18s/^#//'  /etc/rsyslog.conf
        sudo sed '14s/^#//'  /etc/rsyslog.conf
        sudo echo -e '$template remote-incoming-logs, "/var/log/remote/%HOSTNAME%/%PROGRAMNAME%.log"\n
*.* ?remote-incoming-logs\n
&~' >> /etc/rsyslog.conf
        sudo systemctl restart rsyslog
        [ -e /home/$USER/live/lib] && { echo "lib found"; } || { echo "lib not found, creating it now"; sudo mkdir -p /home/$USER/live/lib; }
        [ -e /home/$USER/live/lib/configmq.ini ] && { echo "mqconfig exists"; } || { echo "configmq.ini not found, creating it now"; sudo touch /home/$USER/live/lib/configmq.ini; echo "brokerhost = $2" >> /home/$USER/live/lib/configmq.ini; echo "brokerport = 5672" >> /home/$USER/live/lib/configmq.ini; echo "brokeruser = dwq2" >> /home/$USER/live/lib/configmq.ini; echo "brokerpass = dwq2" >> /home/$USER/live/lib/configmq.ini; cp -f /home/$USER/live/lib/configmq.ini /home/$USER/scripts/configmq.ini.bak; }
		;;
    "DB")
        sudo apt upgrade
        sudo apt update
        [ (sudo systemctl status mysql-server | grep "Status: install") ] && sudo apt install mysql-server
        sudo mysql_secure_installation
        #setup rsyslog to log to the MQ vm
        [ -e /etc/rsyslog.conf ] && { sudo chmod 666 /etc/rsyslog.conf; sudo echo "*.* @$2:514" >> /etc/rsyslog.conf; } || { echo "rsyslog not found"; sudo apt install rsyslog; }
        #setup to update or make the mq config file
        [ -e /home/$USER/dropoff/lib] && { echo "lib found"; } || { echo "lib not found, creating it now"; mkdir -p /home/$USER/dropoff/lib; }
        [ -e /home/$USER/dropoff/lib/configmq.ini ] && { echo "mqconfig exists"; } || { echo "configmq.ini not found, creating it now"; touch /home/$USER/dropoff/lib/configmq.ini; echo "brokerhost = $2" >> /home/$USER/dropoff/lib/configmq.ini; echo "brokerport = 5672" >> /home/$USER/dropoff/lib/configmq.ini; echo "brokeruser = DB" >> /home/$USER/dropoff/lib/configmq.ini; echo "brokerpass = DB" >> /home/$USER/dropoff/lib/configmq.ini; cp -f /home/$USER/dropoff/lib/configmq.ini /home/$USER/scripts/configmq.ini.bak; }
        [ -e /home/$USER/dropoff/lib/config.ini ] && { echo "config.ini exists"; } || { echo "config.ini not found, creating it now"; touch /home/$USER/dropoff/lib/config.ini; echo "dbhost = $2" >> /home/$USER/dropoff/lib/config.ini; echo "dbport = 3306" >> /home/$USER/dropoff/lib/config.ini; echo "dbuser = DB" >> /home/$USER/dropoff/lib/config.ini; echo "dbpass = DB" >> /home/$USER/dropoff/lib/config.ini; cp -f /home/$USER/dropoff/lib/config.ini /home/$USER/scripts/config.ini.bak; }
        #echo "pull SQL tables and run init-db.php"
        ;;
    "API")
        sudo apt upgrade
        sudo apt update

        echo "pulls API files and runs ~init-api.php"
        #check and install rsyslog with the correct config
        [ sudo systemctl status rsyslog | grep "Status: install" ] && { sudo apt install rsyslog; sudo chmod 666 /etc/rsyslog.conf; sudo echo "*.* @$2:514" >> /etc/rsyslog.conf; }

        #setup to update or make the mq config file
        [ -e /home/$USER/live/lib] && { echo "lib found"; } || { echo "lib not found, creating it now"; mkdir -p /home/$USER/live/lib; }
        [ -e /home/$USER/live/lib/configmq.ini ] && { echo "mqconfig exists"; } || { echo "configmq.ini not found, creating it now"; touch /home/$USER/live/lib/configmq.ini; echo "brokerhost = $2" >> /home/$USER/live/lib/configmq.ini; echo "brokerport = 5672" >> /home/$USER/live/lib/configmq.ini; echo "brokeruser = API" >> /home/$USER/live/lib/configmq.ini; echo "brokerpass = API" >> /home/$USER/live/lib/configmq.ini; cp -f /home/$USER/live/lib/configmq.ini /home/$USER/scripts/configmq.ini.bak; }
        ;;
    *)
        echo "Usage: APP|MQ|API|DB|*for this MQ_IP username_onVM"
        ;;
    esac