#old ip $1
#new ip $2

sed -i "s/$1/$2/g" /etc/rsyslog.conf | sudo tee /etc/rsyslog.conf
service rsyslog restart
sed -i "s/$1/$2/g" /home/$USER/live/lib/configrmq.ini >> /home/$USER/live/lib/configrmq.ini
#sed -i "s/$1/$2/g" /etc/hosts | tee /etc/hosts
#[ -e /home/$USER/live/lib/config.ini ] && { sed -i "s/$1/$2/g" /home/$USER/live/lib/config.ini; } 