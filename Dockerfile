# Use the base image from ECR
FROM 767397762185.dkr.ecr.eu-north-1.amazonaws.com/prod-laravel-api-base-image:latest as php

# Copy configuration files
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

# Set working directory to /app
WORKDIR /app

# Copy files from current folder to container current folder (set in workdir)
COPY --chown=www-data:www-data . .

# Create Laravel caching folders
RUN mkdir -p ./storage/framework/{cache,testing,sessions,views,bootstrap,bootstrap/cache}

# Adjust user permission & group
RUN usermod --uid 1000 www-data
RUN groupmod --gid 1000 www-data

# Install dependencies
RUN composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader

# Copy the entrypoint script and set execute permissions
COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
