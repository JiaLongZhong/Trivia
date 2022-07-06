#source = $1
#user = $2
#host = $3
#dest = $4
scp -i ~./ssh/vmkey -r $1 $2@$3:$4