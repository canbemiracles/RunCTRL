#!/bin/bash

#Setting rights
cd /var/www/html
setfacl -dR -m u:"www-data":rwX -m u:$(whoami):rwX var;
setfacl -R -m u:"www-data":rwX -m u:$(whoami):rwX var;

#Removing all cron jobs
crontab -r

#cron job for assignment (check for assignment to send every minute)
cat <(crontab -l) <(echo "* * * * * /usr/local/bin/php /var/www/html/bin/console api:assignments:send >> /var/www/html/var/logs/assignments.log 2>&1") | crontab -
cat <(crontab -l) <(echo "* * * * * /usr/local/bin/php /var/www/html/bin/console api:notifications:send >> /var/www/html/var/logs/assignments.log 2>&1") | crontab -

cat <(crontab -l) <(echo "* * * * * /usr/local/bin/php /var/www/html/bin/console api:start-shift >> /var/www/html/var/logs/shift.log 2>&1") | crontab -
cat <(crontab -l) <(echo "* * * * * /usr/local/bin/php /var/www/html/bin/console api:close-shift >> /var/www/html/var/logs/shift.log 2>&1") | crontab -
cat <(crontab -l) <(echo "* * * * * /usr/local/bin/php /var/www/html/bin/console api:close-shift-managers >> /var/www/html/var/logs/shift.log 2>&1") | crontab -
cat <(crontab -l) <(echo "* * * * * /usr/local/bin/php /var/www/html/bin/console api:close-shift-employees >> /var/www/html/var/logs/shift.log 2>&1") | crontab -

cat <(crontab -l) <(echo "* * * * * /usr/local/bin/php /var/www/html/bin/console api:assignments:check >> /var/www/html/var/logs/assignments.log 2>&1") | crontab -

#Starting cron
service cron start

#Starting fpm instance
php-fpm 