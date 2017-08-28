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

```php
  use Minifier;

  $min = new Minifier($source);
  $result = $min->minify();
  
  or

  $min = new Minifier();
  $min->addsource($source);
  $result = $min->minify();

```
