# minify
## Intallation
For installation, add a dependency on babounlek/minify to your composer.json

```sh
composer require babounlek/minify "dev-master"
```

## Usage
In your app.php,

In providers section add:

```php
babounlek\minify\minifyServiceProvider::class,
```
Now in aliases section add:

```php
'minify' => babounlek\minify\minify::class,
```

```php
use minify;

$min = new minify($source);
$result = $min->minify();

or

$min = new minify();
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
=======
# minify
## Intallation
For installation, add a dependency on babounlek/minify to your composer.json

```sh
composer require babounlek/minify "dev-master"
```

## Usage
In your app.php,

In providers section add:

```php
babounlek\minify\minifyServiceProvider::class,
```
Now in aliases section add:

```php
'minify' => babounlek\minify\minify::class,
```

```php
use minify;

$min = new minify($source);
$result = $min->minify();

or

$min = new minify();
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
Try it online at: <http://rank2me.com/en/minify>
