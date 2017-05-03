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

# Update software list, install nginx & clear cache
RUN apt-get update && \
    apt-get install -y --force-yes nginx build-essential \
    git curl && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* \
           /tmp/* \
           /var/tmp/*

# Configure nginx
RUN echo "daemon off;" >>                                               /etc/nginx/nginx.conf
RUN sed -i "s/sendfile on/sendfile off/"                                /etc/nginx/nginx.conf
RUN mkdir -p                                                            /var/www

# Add nginx service
RUN mkdir                                                               /etc/service/nginx
ADD build/nginx/run.sh                                                  /etc/service/nginx/run
RUN chmod +x                                                            /etc/service/nginx/run

# Add nginx
VOLUME ["/var/www", "/etc/nginx/sites-available", "/etc/nginx/sites-enabled"]

EXPOSE 80
