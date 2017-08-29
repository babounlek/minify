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
/*$result is an array containing of tree elements as:
- first is the compressed string
- second is the size of original string
- third is the size of final string
*/
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
```php
['.container-search{margin-top:55px;margin-bottom:10px}', 66,56]
```

## Try it
Try it online at: <http://rank2me.com/en/minify>.
