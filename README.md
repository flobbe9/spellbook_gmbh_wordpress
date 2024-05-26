# spellbook_ug_wordpress

# Run
- create .env.local and place it in the same directory as ```docker-compose.dev.yml```
- copy all content from .env file
- inside .env.local override the following variables with following values:
    - ENV=dev
    - HOST=localhost
    - PORT=8080
    - FRONTEND_PROTOCOL=http
    - FRONTEND_HOST=localhost
    - FRONTEND_PORT=3000
- run ```docker-compose -f docker-compose.dev.yml up```