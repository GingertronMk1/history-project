FROM ruby:2.4.2-stretch

RUN apt-get update -qq

# Set locale, unicode in Ruby falls over without
RUN DEBIAN_FRONTEND=noninteractive apt-get install -qqy locales
RUN sed -i -e 's/# en_GB.UTF-8 UTF-8/en_GB.UTF-8 UTF-8/' /etc/locale.gen && \
    echo 'LANG="en_GB.UTF-8"'>/etc/default/locale && \
    dpkg-reconfigure --frontend=noninteractive locales && \
    update-locale LANG=en_GB.UTF-8
ENV LANG en_GB.UTF-8

RUN curl -sL https://deb.nodesource.com/setup_6.x | bash -
RUN apt-get install -y nodejs

RUN gem install bundler
RUN npm install -g bower gulp-cli

RUN mkdir -p /srv/env
WORKDIR /srv/env

ADD Gemfile .
ADD Gemfile.lock .
RUN bundle install --jobs=3 --retry=3 --system

ADD package.json .
RUN npm install -g

ENV USER_ID=1001
ENV USER_NAME="nt"
RUN useradd -u $USER_ID -U $USER_NAME

RUN mkdir /srv/history-project && chown $USER_NAME:$USER_NAME /srv/history-project

WORKDIR /srv/history-project
USER $USER_NAME
CMD gulp $1
