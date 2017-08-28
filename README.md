# Minifier
## Intallation
composer require babounlek/minifier "dev-master"

## Usage
In your app.php providers section add:

```php
babounlek\minifier\minifierServiceProvider::class,
```
Now in aliases section add:

```php
'Minifier' => babounlek\minifier\Minifier::class,
```
