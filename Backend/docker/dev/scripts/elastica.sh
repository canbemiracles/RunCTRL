#!/bin/bash

#Setting rights
cd /var/www/html
setfacl -dR -m u:"www-data":rwX -m u:$(whoami):rwX var;
setfacl -R -m u:"www-data":rwX -m u:$(whoami):rwX var;

es-docker