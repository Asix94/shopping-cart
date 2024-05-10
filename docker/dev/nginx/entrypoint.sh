#!/bin/sh

envsubst '$NGINX_SERVER_PORT,$API_FPM_HOST' < /opt/default.template > /etc/nginx/conf.d/default.conf

exec nginx -g "daemon off;"
