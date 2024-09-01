ARG WORDPRESS_VERSION=latest


#####
FROM wordpress:${WORDPRESS_VERSION}

WORKDIR /var/www/html/

ENV TZ="Europe/Berlin"

# install vim
RUN apt-get update
RUN yes | apt-get install vim

# copy code
COPY ./src/wp-content/ ./wp-content
COPY ./src/wp-config.php ./
COPY ./.env ./

# copy certain wp files into tmp folder first, to be copied by entrypoint later on
COPY ./src/template-loader.php /template-loader.php
COPY ./src/api-fetch.min.js /api-fetch.min.js

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# install wp dependencies
RUN cd /var/www/html/wp-content/themes/spellbook_gmbh_theme/ && composer update