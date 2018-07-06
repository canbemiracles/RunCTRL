#!/bin/bash
fixtures=0
recreate_database=0
# Sleep 10 seconds before composer container will be up
sleep 20


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
for param in $@
do
case $param in
    --recreate-database)
	recreate_database=1
	fixtures=1
    ;;
#    --apply-fixtures)
#	fixtures=1
#    ;;
    *)
      echo "Warrning! Unknown parameter ${key// /}"
    ;;
esac
done
if [ $recreate_database -ge 1 ]
then
    echo 'Recreating database...'
    php /var/www/html/bin/console doctrine:database:drop --force --env=build
    php /var/www/html/bin/console doctrine:database:create --env=build
fi 
echo 'Applying migrations'
# Running migrations
php /var/www/html/bin/console doctrine:migrations:migrate --env=build > /dev/null
echo 'Done'

if [ $? -ne 0 ]
then
	echo 'When applying migrations, an error occurs, apply the migration yourself and fix the error or restart project with flag --recreate-database for creating fresh database (You will lose your data).'	
fi
if [ $fixtures -ge 1 ]
then
    echo 'Applying fixtures'
    php /var/www/html/bin/console doctrine:fixtures:load --env=build
    echo 'Done'
fi
echo 'All jobs done. You can access API on http://127.0.0.1:nginx_port/api/doc'
