FROM php:8.1.3 as base

# Install dependencies
RUN apt-get update && apt-get install -y \
    apt-utils \
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
    libxml2-dev


# install exentsion
RUN docker-php-ext-install mysqli pdo_mysql mbstring zip exif pcntl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install nodejs
RUN cd ~ && curl -fsSL https://deb.nodesource.com/setup_20.x -o /tmp/nodesource_setup.sh
RUN bash /tmp/nodesource_setup.sh
RUN apt-get install -y nodejs


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

EXPOSE 8000 5173




