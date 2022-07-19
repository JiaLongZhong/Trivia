#old ip $1
#new ip $2

sed -i "s/$1/$2/g" /etc/rsyslog.conf
service rsyslog restart
sed -i "s/$1/$2/g" /home/$USER/live/configrmq.ini
[ -e /home/$USER/live/config.ini ] && { sed -i "s/$1/$2/g" /home/$USER/live/config.ini; } 