1. add file to ./src/
2. in ```Dockerfile``` add ```COPY ./src/[fileName] ./[fileName]``` below the other ones (replace '[fileName]' with the file name)
3. in ```./docker-entrypoint.sh``` add ```mv /var/www/html/[fileName] /var/www/html/wp-includes/[fileName]``` 
   below the other ones at the bottom of the file (replace '[fileName]' with the file name)
3. copy ```./docker-entrypoint.sh``` to remote