#!/bin/bash

host=$1

declare -i flag=1
declare -i check=1
function pingcheck
{
    ping=ping -c 1 $host | grep bytes | wc -l
    if [ "$ping" -gt 1 ];then
        check=1
    else
        check=0
    fi
}

while [ $flag == 1 ]
do
    pingcheck
    echo "main system still running"
    if [ $check == 0 ];then
        echo "main system is down"
        toggle=1
        temo=0
        for i in allIPs.txt
        #file formated as App active ip, app backup ip, db active ip, db backup ip, api active ip, api backup ip, mq active ip, mq backup ip
        do
            if [ $toggle == 1 ];then
                temo=$i
                toggle=0
            else
                if i != $host; then
                    ssh -i ~/.ssh/id_rsa dwq2@$temo /home/dwq2/scripts/configFailover.sh $temo $i 
                fi
                toggle=1
            fi
        done
        flag=0

    else
        sleep 30
        continue
    fi

done