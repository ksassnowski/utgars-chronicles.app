FROM php:8.0 as base


# Install basic requirements
RUN apt-get update \
 && apt-get install -y \
 curl \
 apt-transport-https \
 git \
 build-essential \
 libssl-dev \
 wget \
 unzip \
 bzip2 \
 libbz2-dev \
 zlib1g-dev \
 mysql-client \
 libfontconfig \
 libfreetype6-dev \
 libjpeg62-turbo-dev \
 libpng-dev \
 libicu-dev \
 libxml2-dev \
 libldap2-dev \
 libmcrypt-dev \
 fabric \
 jq \
 gnupg \
 npm \
 nodejs \
 composer \
 && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Add codebase to image
ADD . /app

# set working directory
WORKDIR /app

# install php dependencies
RUN composer install

# generate database application key
RUN php artisan key:generate

# create database tables and generate base contents
RUN php artisan migrate:fresh --seed

# install javascript dependencies
RUN npm install -legacy-peer-deps

# build generated JS assets
RUN npm run development

# run the app
CMD ["php artisan serve"]




