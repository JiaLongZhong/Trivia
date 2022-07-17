# $1 is the brokers ip
# $2 is the username and password for the new broker
echo "brokerhost = $1" >> /home/$USER/dropoff/lib/configmq.ini
echo "brokerport = 5672" >> /home/$USER/dropoff/lib/configmq.ini
echo "brokeruser = $2" >> /home/$USER/dropoff/lib/configmq.ini
echo "brokerpass = $2" >> /home/$USER/dropoff/lib/configmq.ini