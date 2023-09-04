FROM php:8.1.3 as base

# update apt
RUN apt-get update

# install system dependencies
RUN  apt-get install -y \
 ca-certificates \
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
 default-mysql-client \
 libonig-dev \
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
 nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# copy composer from
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Add codebase to image
ADD . /app

# set working directory
WORKDIR /app

# install php dependencies
RUN composer update --lock
RUN composer install --ignore-platform-reqs # TODO fix ignore

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




