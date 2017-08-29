# Minifier
## Intallation
For installation, add a dependency on babounlek/minifier to your composer.json

```sh
composer require babounlek/minifier "dev-master"
```

## Usage
In your app.php,

In providers section add:

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

/* $source can be a css or javascript script or url of css or javascript file*/
```

### Sample
#### input
```css
.container-search {
	margin-top: 55px;
	margin-bottom: 10px;
}
```
#### output
```css
.container-search{margin-top:55px;margin-bottom:10px}
```

## Try it
Try it online at: <http://rank2me.com/en/minify>.
