FROM nginx:latest

LABEL maintainer="simplex.ink" 

ADD dist /var/www/html/dist/
ADD docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf
ADD docker/nginx/certificate /opt/certs