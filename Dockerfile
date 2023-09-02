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
 python-pip \
 fabric \
 jq \
 gnupg \
 && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*




