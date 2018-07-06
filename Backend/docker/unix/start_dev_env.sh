#!/bin/bash

mysql_port='3306';
nginx_port='80';
migrations_param='';
function ask_mysql()
{
clear
	    echo "$(tput setaf 7)$(tput setab 1)Seems like your machine running local mysql instance. Run-control application also uses 3306 port. What should we do? $(tput sgr 0)"
	optionsAudits=("Kill my current instance of mysql (Default)" "Use another port for Run-control application" "Change to a random port for Run-control application" "Quit")
	while :
	do
	    i=1
	    #we recreate manually the menu here
	    for o in  "${optionsAudits[@]}"; do
		echo "$i) $o"
		let i++
	    done
	echo "Your choice: "
	    read reply
	    #the user can either type the option number or copy the option text
	    case $reply in
		"1"|"${optionsAudits[0]}") kill_mysql; break;;
		"2"|"${optionsAudits[1]}") ask_mysql_port; break;;
		"3"|"${optionsAudits[2]}") generate_random_mysql_port; break;;
		"4"|"${optionsAudits[3]}") exit; break;;
		"") kill_mysql; break;;
		*) echo "$(tput setaf 7)$(tput setab 1)Invalid choice. Please choose an existing option number. $(tput sgr 0)";;
	    esac
	done
}
function kill_mysql()
{
	kill -TERM $(lsof -ti tcp:3306);
}
function ask_mysql_port()
{
	unset port
	while [[ ! ${port} =~ ^[0-9]+$ ]]; do
		echo 'Enter new mysql port (1025:65534)'
		read port
		! [[ ${port} -ge 1025 && ${port} -le 65535 ]] && unset port
	done
	check_mysql_port
}
function generate_random_mysql_port()
{

	random_port=`shuf -i 1025-65000 -n 1`;
	pid_for_port $random_port
	while [[ $last_pid > 0 ]]; do
		random_port=`shuf -i 1025-65534 -n 1`;
		pid_for_port $random_port
	done
	mysql_port=$random_port
}

function check_mysql_port()
{
#	pid=$(lsof -ti tcp:$port);
	pid_for_port $port
	if [[ $last_pid > 0 ]];
	then
		echo "This port is already in use, choose antoher one"
		ask_mysql_port
	else
		mysql_port=$port
	fi
}
function ask_nginx()
{
clear
	    echo "$(tput setaf 7)$(tput setab 1)Seems like your machine running local web server instance. Run-control application also uses 80 port. What should we do?$(tput sgr 0)"
	optionsAudits=("Kill my current instance of webserver (Default)" "Use another port for Run-control webserver" "Change to a random port for Run-control webserver" "Quit")
	while :
	do
	    i=1
	    #we recreate manually the menu here
	    for o in  "${optionsAudits[@]}"; do
		echo "$i) $o"
		let i++
	    done
	echo "Your choice: "
	    read reply
	    #the user can either type the option number or copy the option text
	    case $reply in
		"1"|"${optionsAudits[0]}") kill_nginx; break;;
		"2"|"${optionsAudits[1]}") ask_nginx_port; break;;
		"3"|"${optionsAudits[2]}") generate_random_nginx_port; break;;
		"4"|"${optionsAudits[3]}") exit; break;;
		"") kill_nginx; break;;
		*) echo "$(tput setaf 7)$(tput setab 1)Invalid choice. Please choose an existing option number. $(tput sgr 0)";;
	    esac
	done
}
function kill_nginx()
{
	kill -TERM $(lsof -ti tcp:80);
}
function ask_nginx_port()
{
	unset port
	while [[ ! ${port} =~ ^[0-9]+$ ]]; do
		echo 'Enter new mysql port (1025:65534)'
		read port
		! [[ ${port} -ge 1025 && ${port} -le 65535 ]] && unset port
	done
	check_nginx_port
}
function generate_random_nginx_port()
{

	random_port=`shuf -i 1025-65000 -n 1`;
	pid_for_port $random_port
	while [[ $last_pid > 0 ]]; do
		random_port=`shuf -i 1025-65534 -n 1`;
		pid_for_port $random_port
	done
	nginx_port=$random_port
}

function check_nginx_port()
{
	pid_for_port $port
	if [[ $last_pid > 0 ]];
	then
		echo "This port is already in use, choose antoher one"
		ask_nginx_port
	else
		nginx_port=$port
	fi
}
function pid_for_port()
{
	port_param=$1
	last_pid=$(lsof -ti tcp:$port_param)
}
command -v docker-compose >/dev/null 2>&1 || { echo "This software requires 'docker-compose' package but it's not installed.  Aborting." >&2; exit 1; }
while [[ $# -gt 0 ]]
do
key="$1"
case ${key// /} in
    --recreate-database|-rdb)
    migrations_param="$migrations_param--recreate-database "
    shift # past argument
    ;;
#    --apply-fixtures|-af)
#    migrations_param="$migrations_param--apply-fixtures "
#    shift # past argument
#    ;;
    *)
            # unknown option
	shift # past argument or value
    ;;
esac
done
mysql_pid=$(lsof -ti tcp:$mysql_port);
if [[ $mysql_pid > 0 ]];
then
        ask_mysql
        else 
	echo 'Seems like port 3306 is available, using it.'
fi
nginx_pid=$(lsof -ti tcp:$nginx_port)
if [[ $nginx_pid > 0 ]];
then
	ask_nginx
	else
	echo 'Seems like port 80 is available, using it.'
fi	
clear

echo "$(tput setab 2)Starting mysql on port: $(tput setaf 4)$mysql_port $(tput sgr 0)"
echo "$(tput setab 2)Starting nginx on port: $(tput setaf 4)$nginx_port $(tput sgr 0)" 
export RC_MYSQL_PORT=$mysql_port
export RC_NGINX_PORT=$nginx_port
export RC_MIGRATIONS_PARAMS=$migrations_param
docker-compose -f docker/dev/docker-compose.yml build
docker-compose -f docker/dev/docker-compose.yml up
