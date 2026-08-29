<?php

namespace Chindit\Collection;

use ArrayIterator;
use Traversable;

/**
 * @template T
 * @implements \IteratorAggregate<array-key, T>
 */
class Collection implements \IteratorAggregate
{
    /**
     * @var array<array-key, T>
     */
    private array $data;

    /**
     * @param array<array-key, T> $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @return list<T>
     */
    public function all(): array
    {
        return array_values($this->data);
    }

    /**
     * @param T $search
     */
    public function contains(mixed $search): bool
    {
        return in_array($search, $this->data, true);
    }

    public function count(): int
    {
        return count($this->data);
    }

    /**
     * @param callable(T, array-key): mixed $callback
     */
    public function each(mixed $callback): self
    {
        if (!is_callable($callback)) {
            return $this;
        }

        foreach ($this->data as $key => $datum) {
            if ($callback($datum, $key) === false) {
                break;
            }
        }

        return $this;
    }

	/**
	 * @param callable(T, array-key): bool|null $callback
	 */
	public function filter(?callable $callback = null): self
	{
		$accepted = new self();
		foreach ($this->data as $key => $datum) {
			if ($callback === null) {
				if (!empty($datum)) {
					$accepted->put($key, $datum);
				}
			} elseif ($callback($datum, $key) === true) {
				$accepted->put($key, $datum);
			}
		}

		return $accepted;
	}

    /**
     * @return T|null
     */
    public function first(): mixed
    {
        return count($this->data) > 0 ? reset($this->data) : null;
    }

    /**
     * @return Collection<mixed>
     */
    public function flatten(int $depth = 500): self
    {
        $result = [];

        foreach ($this->data as $item) {
            $item = $item instanceof Collection ? $item->all() : $item;

            if (!is_array($item)) {
                $result[] = $item;
            } elseif ($depth === 1) {
                $result = array_merge($result, array_values($item));
            } else {
                $result = array_merge($result, (new self($item))->flatten($depth - 1)->toArray());
            }
        }

        return new self($result);
    }

    /**
     * @template TDefault
     * @param array-key $key
     * @param TDefault $defaultValue
     * @return T|TDefault
     */
    public function get(mixed $key, mixed $defaultValue = null): mixed
    {
        return $this->has($key) ? $this->data[$key] : $defaultValue;
    }

    /**
     * @return Traversable<array-key, T>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    /**
     * @param string|callable(T): (array-key|null) $groupKey
     * @return Collection<T|Collection<T>>
     */
    public function groupBy(mixed $groupKey): self
    {
    	$result = new self();

    	foreach ($this->data as $datum) {
            if (is_string($groupKey)) {
                $value = $this->getValueByAccessor($datum, $groupKey);
            } elseif (is_callable($groupKey)) {
                $value = $groupKey($datum);
            } else {
                $value = null;
            }

    		if ($value === null) {
    			$result->push($datum);
		    } else {
    			$keyData = $result->get((string)$value, new Collection());
    			if (!$keyData instanceof Collection) {
    				$result->put((string)$value, new Collection());
    				$keyData = $result->get((string)$value);
			    }
    			$result->put((string)$value, $keyData->push($datum));
		    }
	    }

    	return $result;
    }

    /**
     * @param array-key $key
     */
    public function has(mixed $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function isEmpty(): bool
    {
        return count($this->data) === 0;
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * @param array-key|callable(T, array-key): array-key $callback
     * @return Collection<T>
     */
    public function keyBy(mixed $callback): self
    {
        $results = [];
        foreach ($this->data as $key => $item) {
            $results[] = (is_callable($callback)) ? $callback($item, $key) : $this->getValueByAccessor($item, $callback);
        }

        return new self(array_combine($results, $this->data));
    }

    /**
     * @return Collection<array-key>
     */
    public function keys(): self
    {
        return new self(array_keys($this->data));
    }

    /**
     * @template U
     * @param callable(T, array-key): U $callback
     * @return Collection<U>
     */
    public function map(mixed $callback): self
    {
        if (!is_callable($callback)) {
            return $this;
        }

        $result = [];

        foreach ($this->data as $key => $item) {
            $result[] = $callback($item, $key);
        }

        return new self($result);
    }

    /**
     * @template U
     * @param Collection<U> $collection
     * @return Collection<T|U>
     */
    public function merge(self $collection): self
    {
        return new self(array_merge($this->data, $collection->toArray()));
    }

    /**
     * @template U
     * @param Collection<U> $collection
     * @return Collection<mixed>
     */
    public function mergeRecursive(self $collection): self
    {
        return new self(array_merge_recursive($this->data, $collection->toArray()));
    }

    /**
     * @return Collection<mixed>
     */
    public function pluck(string $name, string|int|null $key = null): self
    {
        if (empty($this->data)) {
            return new self();
        }

        $results = new self();
        foreach ($this->data as $item) {
            if (!$key) {
                $results->push($this->getValueByAccessor($item, $name));
            } elseif ($this->isBasicType($item, $key)) {
                $results->put($this->getValueByAccessor($item, $key), $this->getValueByAccessor($item, $name));
            }
        }

        $results = $results->filter(fn($item) => $item !== null);
        if (!$key) {
            return new self(array_values($results->toArray()));
        } else {
            return $results;
        }
    }

    /**
     * @param T $item
     */
    public function push(mixed $item): self
    {
        $this->data[] = $item;

        return $this;
    }

    /**
     * @param array-key $key
     * @param T $value
     */
    public function put(mixed $key, mixed $value): self
    {
    	$this->data[$key] = $value;

    	return $this;
    }

    /**
     * @return self
     */
    public function sort(): self
    {
    	sort($this->data);

    	return $this;
    }

    /**
     * @return self
     */
    public function rsort(): self
    {
    	rsort($this->data);

    	return $this;
    }

    /**
     * @return array<array-key, mixed>
     */
    public function toArray(): array
    {
        return array_map(function ($value) {
            return $value instanceof self ? $value->toArray() : $value;
        }, $this->data);
    }

    /**
     * @return self
     */
    public function unique(): self
    {
        return new self(array_unique($this->data));
    }

    /**
     * @param mixed $item
     * @param string|int $name
     */
    private function getValueByAccessor(mixed $item, string|int $name): mixed
    {
        if (is_array($item)) {
            if (isset($item[$name])) {
                return $item[$name];
            }
        } elseif (is_object($item)) {
            if (method_exists($item, $name)) {
                return $item->$name();
            } elseif (method_exists($item, 'get' . ucfirst($name))) {
                $methodName = 'get' . ucfirst($name);
                return $item->$methodName();
            } elseif (property_exists($item, $name)) {
                return $item->$name;
            }
        }

        return null;
    }

    private function isBasicType(mixed $item, int|string $key): bool
    {
        $value = $this->getValueByAccessor($item, $key);

        return is_string($value) || is_numeric($value);
    }
}
