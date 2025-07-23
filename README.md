# spellbook_gmbh_wordpress

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


# Adding Namespaces Example
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

# Update SSL
Simply download the new .crt file from strato and replace the .crt / .crt.pem files (.pem file needs to be regenerated).