
# $1 is the user of the machine this is runnin on (dwq2) replaced by $USER
# $1 is the type of server that this is being run on (app, db, mq, api)
# $2 Ip of MQ server
[ ! $# -eq 3 ] && { echo "Usage: APP|MQ|API|DB MQ_IP username_on_vm"; exit 1; } 



[ ! -d "/home/$USER/dropoff" ] &&  mkdir dropoff
[ ! -d "/home/$USER/backup" ] && mkdir backup
[ ! -d "/home/$USER/live" ] && mkdir live
[ ! -d "/home/$USER/scripts" ] && { mkdir scripts; echo "the other scripts are missing"; exit 1; }

service_exists() {
    local n=$1
    if [[ $(systemctl list-units --all -t service --full --no-legend "$n.service" | sed 's/^\s*//g' | cut -f1 -d' ') == $n.service ]]; then
        return 0
    else
        return 1
    fi
}

case $1 in
	"APP")
        sudo apt update
        sudo apt upgrade
        sudo apt install php-json php-curl composer php-mbstring php-zip php-gd php php-bcmath composer
		#[ sudo systemctl status apache2 | grep "Status: install" ] && { sudo apt install apache2; } 
        if service_exists apache2; then
            echo "apache2 is installed"
        else
            sudo apt install apache2
            sudo systemctl enable apache2
            sudo systemctl start apache2
        fi
        sudo /etc/init.d/apache2 restart
        echo "setup apache to serve the app"
        #setup rsyslog to log to the MQ vm 
        #TODO fix the echo is not working
        [ -e /etc/rsyslog.conf ] && { sudo apt install rsyslog; sudo chmod 666 /etc/rsyslog.conf; sudo echo "*.* @$2:514" >> /etc/rsyslog.conf; }
        sudo systemctl restart rsyslog
        #setup apache to serve the app from the live folder
        [ -e /home/$USER/dropoff/lib ] && { echo "lib found"; } || { echo "lib not found, creating it now"; sudo mkdir -p /home/$USER/dropoff/lib; }
        [ -e /home/$USER/dropoff/lib/configrmq.ini ] && { echo "mqconfig exists"; } || { echo "configrmq.ini not found, creating it now"; sudo touch /home/$USER/dropoff/lib/configrmq.ini; sudo chmod 666 /home/$USER/dropoff/lib/configrmq.ini; ./home/$USER/scripts/secretConfigs.sh $2 "APP"; sudo nano /home/$USER/dropoff/lib/configrmq.ini; sudo cp -f /home/$USER/dropoff/lib/configrmq.ini /home/$USER/scripts/configrmq.ini.bak; }
        ;;

	"MQ")
        sudo apt update
        sudo apt upgrade
        #setup rabbitmq and adds the users needed
        #[ sudo systemctl status rabbitmq-server | grep "Status: install" ] && { sudo apt install rabbitmq-server; sudo rabbitmq-plugins enable rabbitmq_management; sudo systemctl restart rabbitmq-server; }
        if service_exists rabbitmq-server; then
            echo "rabbitmq-server is already installed"
            sudo rabbitmqctl stop_app
            sudo rabbitmqctl join_cluster rabbit@mq-qa
            sudo rabbitmqctl start_app
        else
            echo "rabbitmq-server is not installed"
            #./home/$USER/scripts/mqinstall.sh
            [ -x /home/$USER/scripts/mqinstall.sh ] && { ./home/$USER/scripts/mqinstall.sh; } || { sudo chmod +x home/$USER/scripts/mqinstall.sh; ./home/$USER/scripts/mqinstall.sh; }
            sudo rabbitmq-plugins enable rabbitmq_management
            sudo systemctl restart rabbitmq-server
            sudo nano /etc/hosts
            sudo nano /var/lib/rabbitmq/.erlang.cookie
            sudo rabbitmqctl stop_app
            sudo rabbitmqctl reset
            sudo reboot
        fi
        #[ sudo systemctl status rsyslog | grep "Status: install" ] && sudo apt install rsyslog
        if service_exists rsyslog; then
            echo "rsyslog is already installed"
        else
            echo "rsyslog is not installed"
            sudo apt install rsyslog
            sudo chmod 666 /etc/rsyslog.conf
            sudo cp /etc/rsyslog.conf /etc/rsyslog.conf.bak
            #setup rsyslog to recieve logs over udp
            sudo sed '17,18s/^#//'  /etc/rsyslog.conf | sudo tee /etc/rsyslog.conf
            sudo sed '14s/^#//'  /etc/rsyslog.conf | sudo tee /etc/rsyslog.conf
            sudo echo -e '$template remote-incoming-logs, "/var/log/remote/%HOSTNAME%/%PROGRAMNAME%.log"\n
    *.* ?remote-incoming-logs\n
    &~' >> /etc/rsyslog.conf
            sudo systemctl restart rsyslog
        fi
        #"go to /etc/rsyslog.conf and uncomment the lines of provides UCP syslog reception then restart rsyslog"
        
        [ -e /home/$USER/dropoff/lib ] && { echo "lib found"; } || { echo "lib not found, creating it now"; sudo mkdir -p /home/$USER/dropoff/lib; }
        [ -e /home/$USER/dropoff/lib/configrmq.ini ] && { echo "mqconfig exists"; } || { echo "configrmq.ini not found, creating it now"; sudo touch /home/$USER/dropoff/lib/configrmq.ini; ./home/$USER/scripts/secretConfigs.sh $2 "MQ"; cp -f /home/$USER/dropoff/lib/configrmq.ini /home/$USER/scripts/configrmq.ini.bak; }
        [ -x /home/$USER/scripts/rabbitActMaker.sh ] && { ./home/$USER/scripts/rabbitActMaker.sh; } || { sudo chmod +x home/$USER/scripts/rabbitActMaker.sh; ./home/$USER/scripts/rabbitActMaker.sh; }
		;;
    "DB")
        sudo apt upgrade
        sudo apt update
        [ -e /home/$USER/live/lib] && { echo ""; } || { sudo apt install php-json php-curl composer php-mbstring php-zip php-gd php php-bcmath; sudo mysql_secure_installation; }
        # in the work \/
        
        if service_exists mariadb; then
            echo "mariadb is already installed"
        else
            sudo apt install mariadb-server
            [ -x ~/scripts/DBuserMaker.sh ] && { ~/scripts/DBuserMaker.sh; } || { sudo chmod +x ~/scripts/DBuserMaker.sh; ~/scripts/DBuserMaker.sh; }
            fi
        #setup rsyslog to log to the MQ vm
        [ -e /etc/rsyslog.conf ] && { sudo chmod 666 /etc/rsyslog.conf; sudo echo "*.* @$2:514" >> /etc/rsyslog.conf; } || { echo "rsyslog not found"; sudo apt install rsyslog; }
        #setup to update or make the mq config file
        [ -e /home/$USER/dropoff/lib ] && { echo "lib found"; } || { echo "lib not found, creating it now"; mkdir -p /home/$USER/dropoff/lib; }
        [ -e /home/$USER/dropoff/lib/configrmq.ini ] && { echo "mqconfig exists"; } || { echo "configrmq.ini not found, creating it now"; touch /home/$USER/dropoff/lib/configrmq.ini; ./home/$USER/scripts/secretConfigs.sh $2 "DB"; cp -f /home/$USER/dropoff/lib/configrmq.ini /home/$USER/scripts/configrmq.ini.bak; }
        [ -e /home/$USER/dropoff/lib/config.ini ] && { echo "config.ini exists"; } || { echo "config.ini not found, creating it now"; touch /home/$USER/dropoff/lib/config.ini; echo "dbhost = localhost" >> /home/$USER/dropoff/lib/config.ini; echo "dbport = 3306" >> /home/$USER/dropoff/lib/config.ini; echo "dbuser = DB" >> /home/$USER/dropoff/lib/config.ini; echo "dbpass = DB" >> /home/$USER/dropoff/lib/config.ini; cp -f /home/$USER/dropoff/lib/config.ini /home/$USER/scripts/config.ini.bak; }
        #echo "pull SQL tables and run init-db.php"
        #make the custom services running on boot
        #./home/$USER/scripts/implementServices.sh
        sudo cp -r /home/$USER/dropoff/services/ /etc/systemd/system/
        sudo systemctl start registeruser.service
        sudo systemctl enable registeruser.service
        sudo systemctl start loginuser.service
        sudo systemctl enable loginuser.service
        sudo systemctl start updateuser.service
        sudo systemctl enable updateuser.service
        sudo systemctl start createtrivia.service
        sudo systemctl enable createtrivia.service
        #[ -x ~/scripts/implementServices.sh ] && { ~/scripts/implementServices.sh; } || { sudo chmod +x ~/scripts/implementServices.sh; ~/scripts/implementServices.sh; }
		
        ;;
    "API")
        sudo apt upgrade
        sudo apt update
        sudo apt install php-json php-curl composer php-mbstring php-zip php-gd
        echo "pulls API files and runs ~init-api.php"
        #check and install rsyslog with the correct config
        [ ! -e /etc/rsyslog.conf ] && { sudo apt install rsyslog; sudo chmod 666 /etc/rsyslog.conf; sudo echo "*.* @$2:514" >> /etc/rsyslog.conf; }

        #setup to update or make the mq config file
        [ -e /home/$USER/dropoff/lib ] && { echo "lib found"; } || { echo "lib not found, creating it now"; mkdir -p /home/$USER/dropoff/lib; }
        [ -e /home/$USER/dropoff/lib/configrmq.ini ] && { echo "mqconfig exists"; } || { echo "configrmq.ini not found, creating it now"; touch /home/$USER/dropoff/lib/configrmq.ini; ./home/$USER/scripts/secretConfigs.sh $2 "API"; cp -f /home/$USER/dropoff/lib/configrmq.ini /home/$USER/scripts/configrmq.ini.bak; }
        ;;
    *)
        echo "Usage: APP|MQ|API|DB|*for this MQ_IP username_onVM"
        #sudo chmod 666 /home/$USER/dropoff/
        mkdir -p /home/$USER/dropoff/lib
        ;;
    esac