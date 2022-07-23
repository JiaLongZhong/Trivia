#!/bin/bash

host=$1
backup=$2
echo $host
echo $backup
declare -i flag=1
declare -i check=1
pingcheck () {
    ping=```ping -c 1 $host | grep bytes | wc -l```
    echo $ping
    if [ "$ping" -gt 1 ];then
        check=1
    else
        check=0
    fi
}

while [ $flag -eq 1 ]
do
    pingcheck
    echo "main system still running"
    if [ $check -eq 0 ];then
        echo "main system is down"
        toggle=1
        temo=0
        Lines=$(cat "allIPs.txt")
        for i in $Lines
        #file formated as App active ip, app backup ip, db active ip, db backup ip, api active ip, api backup ip, mq active ip, mq backup ip
        do
            # if [ $toggle == 1 ];then
            #     temo=$i
            #     toggle=0
            # else
            echo $i
                #if [ $i != $host ]; then
                    ssh -i ~/.ssh/id_rsa dwq2@$i /home/$USER/scripts/configFailover.sh $1 $hosts $backup
                #fi
                toggle=1
            #fi
        done
        flag=0

    else
        sleep 2s
        continue
    fi

done