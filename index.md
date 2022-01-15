## Collection

Collection is a library aimed at providing you easy tools to handle your PHP arrays.

### Installation

Just add `chindit/collection` to your _composer.json_

```bash
composer require chindit/collection
```

### Usage

To initiate a collection, just call the constructor with your array as parameter

`$myCollection = new Chindit\Collection(['a', 'b' => 'c']);`


### Methods

- `all()` _mixed_ : return all **values** of your collection
- `contains($search)` _bool_ : **true** if any element of your collection is _strictly_ equal to `$search`.  False otherwise
- `count()` _int_ : number of elements in your collection
- `current()` _mixed_ : element on which iterator is pointing.  First element if not accessed before
- `each($callable)` _Collection_ : apply `$callable` on every element of your collection.  If `$callable` returns *false*, processing is interrupted
- `filter($callable)` _Collection_ : return a copy of your collection with only the elements for which `$callable` returned *true*
- `first()` _mixed_ : first element of the collection (equals to `$array[0]`)
- `flatten($depth = 500)` _Collection_ : flatten the colletion until `$depth` level.  `$depth` **must** be an int.  All sub-collections will be merged to parent level
- `get($key, $defaultValue = null)` _mixed_ : return the value for the `$key` element.  If `$key` element doesn't exist, `$defaultValue` will be returned instead.
- `groupBy($key)` _Collection_ : return a copy of your collection, grouped by `$key`
- `has($key)` _bool_ : check if `$key` is a valid key for this collection
- `isEmpty()` _bool_ : wether the collection is empty
- `isNotEmpty()` _bool_ : wether the collection is not empty
- `key()` _mixed_ : key for the current element in iterator
- `keyBy($key)` _Collection_ : rewrite keys based on `$key`.  `$key` can be either a callable (in this case, key will be the value returned by the function) or, in the case of an object, a property of this object.
- `keys()` _Collection_ : return all keys for the collection
- `map($callable)` _Collection_ : apply `$callable` on each element of the collection
- `merge($collection)` _Collection_ : merge `$collection` into actual collection
- `mergeRecursive($collection)` _Collection_ : merge recursively `$collection` into actual collection
- `next()` _void_ : move iterator to next element
- `pluck($string)` _Collection_ : take only `$string` value for all elements of the collection and create a collection for these elements
- `push($element)` _Collection_ : add `$element` to actual collection
- `put($key, $elemnt)` _Collection_ : add `$element` to actual collection and set its key to `$key`
- `rewind()` _void_ : rewind iterator
- `rsort()` _Collection_ : sort collection by reverse order
- `sort()` _Collection_ : sort collection
- `toArray()` _array_ : transfort actual collection to an array
- `unique()` _Collection_ : return a collection with all unique elements in this collection
- `valid()` _bool_ : check if iterator is in a valid position


### Examples

```
// Let's assume we have some Car objects
$myObject = new Car();

$myCollection = new Collection($arrayOfCarObjects);

// «pluck» will access «brand» property or «getBrand» method on all elements of the collection and return its value
// «unique» will remove all duplicates
$uniqueBrands = $myCollection->pluck('brand')->unique();
```

### Support & Contact

If you have any issue or question with this repository, do not hesitate to leave a comment in the «Issue» sections ^^
