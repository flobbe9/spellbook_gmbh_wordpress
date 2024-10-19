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
RUN chmod 777 /template-loader.php
COPY ./src/api-fetch.min.js /api-fetch.min.js
RUN chmod 777 /api-fetch.min.js

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# install wp dependencies
RUN cd /var/www/html/wp-content/themes/spellbook_gmbh_theme/ && composer update





# ARG WORDPRESS_VERSION=latest

# ### Build
# FROM wordpress:${WORDPRESS_VERSION} AS web-build

# ENV TZ="Europe/Berlin"

# # apt
# RUN apt-get update; 

# # wp-cli
# RUN curl -sL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o wp; \
#   chmod +x wp; \
#   mv wp /usr/local/bin/; \
#   mkdir /var/www/.wp-cli; \
#   chown www-data:www-data /var/www/.wp-cli

# # composer
# RUN curl -sL https://raw.githubusercontent.com/composer/getcomposer.org/master/web/installer | php -- --version=2.1.3; \
#   mv composer.phar /usr/local/bin/composer; \
#   mkdir /var/www/.composer; \
#   chown www-data:www-data /var/www/.composer

# # ensure wordpress has write permission on linux host https://github.com/postlight/headless-wp-starter/issues/202
# RUN chown -R www-data:www-data /var/www/html

# # include composer-installed executables in $PATH
# ENV PATH="/var/www/.composer/vendor/bin:${PATH}"

# WORKDIR /var/www/html/

# # install vim
# RUN apt-get update
# RUN yes | apt-get install vim

# # copy code
# COPY ./var/www/html/wp-content/ ./wp-content
# COPY ./.env ./

# # install composer
# # COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# # install wp dependencies
# # RUN cd /var/www/html/wp-content/themes/gingco_relaunch/ && composer install