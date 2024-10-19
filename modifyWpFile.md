1. add file to ./src/
2. in ```Dockerfile``` add 
    ```
    COPY ./src/[fileName] /[fileName]
    RUN chmod 777 /[fileName]
    ``` 
    below the other ones (replace '[fileName]' with the file name)<br>
3. in ```./docker-entrypoint.sh``` add ```mv /[fileName] /var/www/html/wp-includes/[fileName]``` 
   below the other ones at the bottom of the file (replace '[fileName]' with the file name)
4. copy ```./docker-entrypoint.sh``` to remote