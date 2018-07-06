#!/bin/bash

cd /var/www/html
setfacl -dR -m u:"nginx":rwX -m u:$(whoami):rwX var;
setfacl -R -m u:"nginx":rwX -m u:$(whoami):rwX var;
nginx -g 'daemon off;';
