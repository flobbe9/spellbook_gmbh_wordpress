ARG WORDPRESS_VERSION=latest


#####
FROM wordpress:${WORDPRESS_VERSION}

WORKDIR /var/www/html/

# Args
ARG WORDPRESS_DB_NAME
ARG WORDPRESS_DB_USER
ARG WORDPRESS_DB_PASSWORD

# set args as env
ENV WORDPRESS_DB_NAME=${WORDPRESS_DB_NAME}
ENV WORDPRESS_DB_USER=${WORDPRESS_DB_USER}
ENV WORDPRESS_DB_PASSWORD=${WORDPRESS_DB_PASSWORD}

# copy code
COPY ./src/wp-content/ ./wp-content
COPY ./src/wp-config.php ./
COPY ./src/wp-settings.php ./
COPY ./src/favicon.ico ./
COPY ./src/.env ./