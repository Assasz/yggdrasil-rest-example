# CreativeNotes

Simple REST API created with [Yggdrasil Skeleton](https://github.com/Assasz/yggdrasil-skeleton).

## Installation

Just clone or download. PHP 7.2+ required.

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

Ensure that YjaxPlugin has proper URL of API (first parameter):

```javascript
// client/src/app.instance.js

const app = (new App())
    .initNProgress()
    .mount('yjax', new YjaxPlugin('http://localhost/creative-notes/api/web', '/yjax/routes'));
```