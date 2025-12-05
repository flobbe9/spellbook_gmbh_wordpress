# Spellbook GmbH wordpress

## Run
- create .env.local and place it in the same directory as ```docker-compose.dev.yml```
- copy all content from .env file
- inside .env.local override the following variables with following values:
    - ENV=dev
    - HOST=localhost
    - PORT=8080
    - FRONTEND_PROTOCOL=http
    - FRONTEND_HOST=localhost
    - FRONTEND_PORT=3000
- run `docker-compose -f docker-compose.dev.yml up -d`
- go to <a href="http://localhost:8080/wp-admin">http://localhost:8080/wp-admin</a>

### Debugging
`env: 'bash\r': No such file or directory` <br>
Set the line endings of the `docker-entrypoint.dev.sh` to "LF"


## Adding Namespaces Example
- "wordpress/var/www/html/wp-content/themes/gingco_relaunch/myFolder/MyClass.php"
```
<?php
namespace Gingco_relaunch\MyNamespaceName;

class MyClass {
    public static function doSomething() {
        ...
    }
    ...
} 
```
- "wordpress/var/www/html/wp-content/themes/gingco_relaunch/myFolder/MyOtherClass.php"
```
<?php
namespace Gingco_relaunch\MyNamespaceName;

class MyOtherClass {
    public static function doSomething() {
        ...
    }
    ...
} 
```

- "wordpress/var/www/html/wp-content/themes/gingco_relaunch/composer.json"
```
{
    "autoload": {
        "psr-4": {
            "Gingco_relaunch\\MyNamespaceName\\": "../gingco_relaunch/myFolder/",
            ...
        }
    },
    ...
}
```

- anywhere inside "wordpress/var/www/html/wp-content/themes/gingco_relaunch/"
```
<?php
use Gingco_relaunch\MyNamespaceName\MyClass;
use Gingco_relaunch\MyNamespaceName\MyOtherClass;

class AnyClass {

    function anyFunction() {
        MyClass::doSomething();
        MyOtherClass::doSomething();
    }

    ...
}
```
- `cd wordpress/var/www/html/wp-content/themes/gingco_relaunch/gingco_relaunch` 
- `composer dump-autoload`

### Notes
- Folder names for `"psr-4"` are relative to the "wordpress/var/www/html/wp-content/themes/gingco_relaunch/gingco_relaunch/vendor" folder.#

### Service repositories
- frontend: <a href="https://github.com/flobbe9/spellbook_gmbh_frontend">https://github.com/flobbe9/spellbook_gmbh_frontend</a>