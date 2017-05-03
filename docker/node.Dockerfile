FROM node:7.9
MAINTAINER Faruq Rasid <faruq.rasid@gmail.com>

# Use SG mirror
RUN sed -i 's/http:\/\/archive.ubuntu.com/http:\/\/mirror.0x.sg/g' /etc/apt/sources.list

# Install yarn
RUN apt-get update && \
    apt-get install apt-transport-https
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get update && \
    apt-get install -y --force-yes build-essential yarn && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* \
           /tmp/* \
           /var/tmp/*

# Install Bower & Gulp
RUN yarn global add bower gulp

# Define working directory.
WORKDIR /var/www

# Define default command.
CMD ["bash"]