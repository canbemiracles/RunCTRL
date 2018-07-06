#!/bin/bash
# Sleep 10 seconds before composer container will be up
sleep 10


while true
do
	# Checking if composer is still alive
	ping -c 1 runctrl_dev_composer_container > /dev/null
	if [ $? -ne 0 ]
	then
		# if composer is down, breaking from loop
		break
	fi
	sleep 15
done

while true
do
	php /var/www/html/docker/dev/scripts/mysql_alive.php > /dev/null
	if [ $? -ne 0 ]
	then
		echo 'Composer is down, but mysql is still not up. Waiting another 10 seconds'
	else
		break
	fi
	sleep 10
done

php /var/www/html/bin/console gos:websocket:server