FROM phusion/baseimage:latest
MAINTAINER Faruq Rasid <faruq.rasid@gmail.com>

# Add volumes
VOLUME ["/var/www/node_modules"]
VOLUME ["/var/www/.yarn"]

CMD ["true"]