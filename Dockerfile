FROM php:8.1.3 as base

# update apt
RUN apt-get update

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libonig-dev \
    libpng-dev \
    libzip-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    npm \
    nodejs \
    libxml2-dev

# install exentsion
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Add codebase to image
ADD . /app

# install php dependencies
RUN composer update --lock
RUN composer install --ignore-platform-reqs # TODO fix ignore

# install javascript dependencies
RUN npm install -legacy-peer-deps

# generate database application key
RUN php artisan key:generate

# create database tables and generate base contents
# RUN php artisan migrate:fresh --seed

# build generated JS assets
RUN npm run dev

# run the app
CMD ["php artisan serve"]




