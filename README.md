# CreativeNotes

Simple REST API created with [Yggdrasil Skeleton](https://github.com/Assasz/yggdrasil-skeleton).

## Installation

Just clone repository onto your server and then install API dependencies with [Composer](https://getcomposer.org/).
PHP 7.2+ required.

```
git clone https://github.com/Assasz/yggdrasil-rest-example.git creative-notes

cd creative-notes/api

composer update
```

## Configuration

Rename **config.example.ini** file located in `api/src/CreativeNotes/Infrastructure/Configuration/Api` to **config.ini** and adjust configuration to your needs.

```
[router]
base_url = http://localhost/creative-notes/api/web/
...

[entity_manager]
db_name = DBNAME
db_user = DBUSER
db_password = DBPASSWORD
...
```

When database is connected, generate database schema and populate it with following commands:

```
php bin/console.php orm:schema-tool:update --force

php bin/console.php yggdrasil:seeds:persist Note
``` 

Ensure that YjaxPlugin has proper URL of API (**host** parameter needs to be the same as **base_url** in config.ini):

```javascript
// client/src/app.instance.js

const app = (new App())
    .initNProgress()
    .mount('yjax', new YjaxPlugin({
        host: 'http://localhost/creative-notes/api/web',
        routesProvider: '/yjax/routes'
    }))
    .mount('noteHelper', new NoteHelperPlugin());
```

## Usage

Interact with API via client application located under `localhost/creative-notes/client` address (assuming that you are using localhost).

## Testing

Before running tests create testing database, import note table from existing production database and configure it's connection in 
`api/src/CreativeNotes/Infrastructure/Configuration/Test/config.ini` file.

Then run unit tests with following command (for Windows just flip slashes):

```
./vendor/bin/phpunit
```

