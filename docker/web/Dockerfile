FROM php:8.0-apache

# Download GIT for when download dependencies by Composer
# Download WGET for when download anything from internet
RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y git wget \
    && apt-get clean

# Overriding the listening ports
# COPY ./config/myPorts.conf /etc/apache2/ports.conf

# Override the virtual host
COPY ./config/myVirtualHost.conf /etc/apache2/sites-available/000-default.conf

# Enabling mod_rewrite from Apache Server
RUN a2enmod rewrite

# Installing package manager Composer from Composer Image
COPY --from=composer /usr/bin/composer /usr/bin/composer
