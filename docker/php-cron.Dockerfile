FROM phusion/baseimage:latest
MAINTAINER Faruq Rasid <faruq.rasid@gmail.com>

# Default baseimage settings
ENV HOME /root
#RUN /etc/my_init.d/00_regen_ssh_host_keys.sh
CMD ["/sbin/my_init"]
ENV DEBIAN_FRONTEND noninteractive

# SG Timezone
ENV TZ="/usr/share/zoneinfo/Asia/Singapore"

# Workdir
WORKDIR /var/www

# Use SG mirror
RUN sed -i 's/http:\/\/archive.ubuntu.com/http:\/\/mirror.0x.sg/g' /etc/apt/sources.list

RUN add-apt-repository -y ppa:ondrej/php
RUN add-apt-repository -y ppa:pinepain/php

# Update software list, install php & clear cache
RUN apt-get update && \
    apt-get install -y --force-yes build-essential cron \
    php7.1 php7.1-common php7.1-fpm php-v8 php7.1-mysql php7.1-mcrypt \
    php7.1-curl php7.1-gd php7.1-intl php7.1-mbstring php7.1-json php7.1-dom php7.1-readline php7.1-opcache php7.1-zip php7.1-dev php-pear \
    git curl && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* \
           /tmp/* \
           /var/tmp/*

# Configure PHP
RUN sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/"                      /etc/php/7.1/fpm/php.ini
RUN sed -i "s/;date.timezone =.*/date.timezone = Asia\/Singapore/"          /etc/php/7.1/fpm/php.ini
RUN sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g"                     /etc/php/7.1/fpm/php-fpm.conf
RUN sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/"                      /etc/php/7.1/cli/php.ini
RUN sed -i "s/;date.timezone =.*/date.timezone = Asia\/Singapore/"          /etc/php/7.1/cli/php.ini
RUN phpenmod mcrypt

# Add Cron service
ADD build/cron/crontab                                                  /etc/cron.d/laravel
RUN chmod 0644                                                          /etc/cron.d/laravel
RUN touch                                                               /var/log/cron.log
