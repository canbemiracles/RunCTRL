#!/bin/bash

docker exec -ti runctrl_dev_mysql_container mysqladmin -u root -pN5GeDWeWmxHYwN53 drop run_control_test -f
docker exec -ti runctrl_dev_mysql_container mysqladmin -u root -pN5GeDWeWmxHYwN53 create run_control_test -f;
docker exec -ti runctrl_dev_php_container php bin/console doctrine:migrations:migrate --env=test --no-interaction;
docker exec -ti runctrl_dev_php_container php bin/console doctrine:fixtures:load --env=test --no-interaction
docker exec -ti runctrl_dev_php_container php bin/console doctrine:fixtures:load --fixtures=tests/fixtures/ --append --env=test

echo 'JOB DONE !'
