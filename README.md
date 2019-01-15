# CreativeNotes

Simple REST API created with [Yggdrasil Skeleton](https://github.com/Assasz/yggdrasil-skeleton).

## Installation

Just clone or download. PHP 7.2+ required.

Then install API dependencies with [Composer](https://getcomposer.org/):

```
cd api

composer update
```

## Configuration

Rename **config.example.ini** (api/src/CreativeNotes/Infrastructure/Configuration) to **config.ini** and adjust configuration to your needs.

```
[router]
; ...
base_url = http://localhost/creative-notes/api/web/
; ...

[entity_manager]
db_name = DBNAME
db_user = DBUSER
db_password = DBPASSWORD
; ...
```

When database is connected, generate schema via following command:

```
php bin/console.php orm:schema-tool:update --force
``` 

Ensure that YjaxPlugin has proper URL of API (host parameter):

```javascript
// client/src/app.instance.js

const app = (new App())
    .initNProgress()
    .mount('yjax', new YjaxPlugin({
        host: 'http://localhost/creative-notes/api/web',
        routesProvider: '/yjax/routes'
    }));
```