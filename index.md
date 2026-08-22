## Collection

Collection is a small PHP library that provides convenient tools for handling arrays.

The current release requires PHP 8.3 or later.

### Installation

Just add `chindit/collection` to your _composer.json_

```bash
composer require chindit/collection
```

### Usage

To create a collection, pass an array to the constructor:

```php
use Chindit\Collection\Collection;

$collection = new Collection(['a', 'b' => 'c']);
```


### Methods

- `all()` _array_ : return all values of the collection, without preserving keys
- `contains($search)` _bool_ : check whether a value is strictly present
- `count()` _int_ : return the number of elements
- `each($callable)` _Collection_ : apply a callback to every element; stop when it returns `false`
- `filter($callable)` _Collection_ : return only the elements accepted by the callback
- `first()` _mixed_ : return the first element
- `flatten($depth = 500)` _Collection_ : flatten nested arrays and collections up to `$depth`
- `get($key, $defaultValue = null)` _mixed_ : return the value for a key, or the default value
- `getIterator()` _Traversable_ : provide a fresh iterator for use with `foreach`
- `groupBy($key)` _Collection_ : group elements by a field/accessor or by a callback
- `has($key)` _bool_ : check whether a key exists
- `isEmpty()` _bool_ : check whether the collection is empty
- `isNotEmpty()` _bool_ : check whether the collection is not empty
- `keyBy($key)` _Collection_ : rewrite keys using an accessor or callback
- `keys()` _Collection_ : return the collection's keys
- `map($callable)` _Collection_ : apply a callback to each element
- `merge($collection)` _Collection_ : merge another collection
- `mergeRecursive($collection)` _Collection_ : recursively merge another collection
- `pluck($string)` _Collection_ : extract a field from every element
- `push($element)` _Collection_ : append an element
- `put($key, $element)` _Collection_ : add an element with a specific key
- `rsort()` _Collection_ : sort the collection in reverse order
- `sort()` _Collection_ : sort the collection
- `toArray()` _array_ : convert the collection, including nested collections, to an array while preserving keys
- `unique()` _Collection_ : return the collection without duplicate values


### Examples

```php
// Let's assume we have some Car objects
$myObject = new Car();

$myCollection = new Collection($arrayOfCarObjects);

// «pluck» will access «brand» property or «getBrand» method on all elements of the collection and return its value
// «unique» will remove all duplicates
$uniqueBrands = $myCollection->pluck('brand')->unique();
```

Collections can be iterated with `foreach`. A new iterator is created for
each loop:

```php
foreach ($myCollection as $key => $value) {
    // use $key and $value
}
```

### Support & Contact

If you have any issue or question with this repository, do not hesitate to leave a comment in the «Issue» sections ^^
