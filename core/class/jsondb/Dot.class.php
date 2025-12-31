<?php

namespace Prowebcraft;

use ArrayAccess;

/**
 * Dot Notation - Improved Version
 *
 * Enhanced class providing dot notation access to arrays with improved performance,
 * additional features, and better error handling.
 */
class Dot implements ArrayAccess, \Iterator, \Countable
{
    /** @var array Data */
    protected $data = [];
    
    /** @var array Cache for parsed keys to improve performance */
    private static $keyCache = [];
    
    /** @var int Maximum cache size to prevent memory issues */
    private static $maxCacheSize = 1000;

    /**
     * Constructor
     *
     * @param array|null $data Data
     */
    public function __construct(?array $data = null)
    {
        if (is_array($data)) {
            $this->data = $data;
        }
    }

    /**
     * Parse and cache key for better performance
     *
     * @param string $key
     * @return array
     */
    private static function parseKey(string $key): array
    {
        if (!isset(self::$keyCache[$key])) {
            self::$keyCache[$key] = explode('.', $key);
            
            // Prevent cache from growing too large
            if (count(self::$keyCache) > self::$maxCacheSize) {
                self::$keyCache = array_slice(self::$keyCache, -self::$maxCacheSize, null, true);
            }
        }
        
        return self::$keyCache[$key];
    }

    /**
     * Validate key format
     *
     * @param mixed $key
     * @throws \InvalidArgumentException
     */
    private static function validateKey($key): void
    {
        if (!is_string($key) && !is_array($key)) {
            throw new \InvalidArgumentException('Key must be string or array');
        }
        
        if (is_string($key) && (empty($key) || strpos($key, '.') === 0)) {
            throw new \InvalidArgumentException('Invalid key format: key cannot be empty or start with dot');
        }
    }

    /**
     * Get value by parsed keys array (optimized version)
     *
     * @param array $data
     * @param array $keys
     * @param mixed $default
     * @return mixed
     */
    private static function getValueByKeys($data, array $keys, $default = null)
    {
        foreach ($keys as $key) {
            if (!is_array($data) || !array_key_exists($key, $data)) {
                return $default;
            }
            $data = $data[$key];
        }
        return $data;
    }

    /**
     * Set value by parsed keys array (optimized version)
     *
     * @param array $data
     * @param array $keys
     * @param mixed $value
     */
    private static function setValueByKeys(&$data, array $keys, $value): void
    {
        $current = &$data;
        
        foreach ($keys as $key) {
            if (!isset($current[$key]) || !is_array($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }
        
        $current = $value;
    }

    /**
     * Get value of path, default value if path doesn't exist or all data
     *
     * @param  array $array Source Array
     * @param  mixed|null $key Path
     * @param  mixed|null $default Default value
     * @return mixed Value of path
     */
    public static function getValue($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }
        
        self::validateKey($key);
        
        if (is_string($key)) {
            $keys = self::parseKey($key);
            return self::getValueByKeys($array, $keys, $default);
        }
        
        return null;
    }

    /**
     * Set value or array of values to path
     *
     * @param array $array Target array with data
     * @param mixed $key Path or array of paths and values
     * @param mixed|null $value Value to set if path is not an array
     */
    public static function setValue(&$array, $key, $value)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                self::setValue($array, $k, $v);
            }
        } else {
            self::validateKey($key);
            $keys = self::parseKey($key);
            self::setValueByKeys($array, $keys, $value);
        }
    }

    /**
     * Add value or array of values to path
     *
     * @param array $array Target array with data
     * @param mixed $key Path or array of paths and values
     * @param mixed|null $value Value to set if path is not an array
     * @param boolean $pop Helper to pop out last key if value is an array
     */
    public static function addValue(&$array, $key, $value = null, $pop = false)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                self::addValue($array, $k, $v);
            }
        } else {
            self::validateKey($key);
            $keys = self::parseKey((string)$key);
            
            if ($pop === true) {
                array_pop($keys);
            }
            
            $current = &$array;
            foreach ($keys as $key) {
                if (!isset($current[$key]) || !is_array($current[$key])) {
                    $current[$key] = [];
                }
                $current = &$current[$key];
            }
            
            $current[] = $value;
        }
    }

    /**
     * Delete path or array of paths
     *
     * @param array $array Target array with data
     * @param mixed $key Path or array of paths to delete
     */
    public static function deleteValue(&$array, $key)
    {
        if (is_array($key)) {
            foreach ($key as $k) {
                self::deleteValue($array, $k);
            }
        } else {
            self::validateKey($key);
            $keys = self::parseKey($key);
            
            if (empty($keys)) {
                return;
            }
            
            $last = array_pop($keys);
            $current = &$array;
            
            foreach ($keys as $key) {
                if (!isset($current[$key])) {
                    return;
                }
                $current = &$current[$key];
            }
            
            if (is_array($current) && array_key_exists($last, $current)) {
                unset($current[$last]);
            }
        }
    }

    /**
     * Get value of path, default value if path doesn't exist or all data
     *
     * @param  string|array|null $key Path
     * @param  mixed|null $default Default value
     * @param  bool $asObject Convert to Dot object
     * @return mixed Value of path
     */
    public function get($key, $default = null, $asObject = false)
    {
        if (is_null($key)) {
            return $this->data;
        }
        
        $value = self::getValue($this->data, $key, $default);
        
        if ($asObject && is_array($value)) {
            return new self($value);
        }

        return $value;
    }

    /**
     * Get multiple values at once
     *
     * @param array $keys Array of keys to retrieve
     * @param mixed $default Default value for missing keys
     * @return array Associative array of key => value pairs
     */
    public function getMultiple(array $keys, $default = null): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }
        return $result;
    }

    /**
     * Set value or array of values to path
     *
     * @param mixed $key Path or array of paths and values
     * @param mixed|null $value Value to set if path is not an array
     * @return $this
     */
    public function set($key, $value = null)
    {
        self::setValue($this->data, $key, $value);
        return $this;
    }

    /**
     * Set multiple values at once
     *
     * @param array $keyValues Associative array of key => value pairs
     * @return $this
     */
    public function setMultiple(array $keyValues): self
    {
        foreach ($keyValues as $key => $value) {
            $this->set($key, $value);
        }
        return $this;
    }

    /**
     * Set value only if it doesn't exist
     *
     * @param mixed $key Path
     * @param mixed $value Value to set
     * @return $this
     */
    public function setIfNotExists($key, $value): self
    {
        if (!$this->has($key)) {
            $this->set($key, $value);
        }
        return $this;
    }

    /**
     * Add value or array of values to path
     *
     * @param mixed $key Path or array of paths and values
     * @param mixed|null $value Value to set if path is not an array
     * @param boolean $pop Helper to pop out last key if value is an array
     * @return $this
     */
    public function add($key, $value = null, $pop = false)
    {
        self::addValue($this->data, $key, $value, $pop);
        return $this;
    }

    /**
     * Push multiple values to an array path
     *
     * @param string $key Path
     * @param mixed ...$values Values to push
     * @return $this
     */
    public function push($key, ...$values): self
    {
        foreach ($values as $value) {
            $this->add($key, $value);
        }
        return $this;
    }

    /**
     * Merge array with existing value at path
     *
     * @param string $key Path
     * @param array $array Array to merge
     * @return $this
     */
    public function merge($key, array $array): self
    {
        $existing = $this->get($key, []);
        $this->set($key, array_merge($existing, $array));
        return $this;
    }

    /**
     * Check if path exists
     *
     * @param  string $key Path
     * @return boolean
     */
    public function has($key): bool
    {
        self::validateKey($key);
        $keys = self::parseKey($key);
        $current = $this->data;
        
        foreach ($keys as $key) {
            if (!is_array($current) || !array_key_exists($key, $current)) {
                return false;
            }
            $current = $current[$key];
        }

        return true;
    }

    /**
     * Delete path or array of paths
     *
     * @param mixed $key Path or array of paths to delete
     * @return $this
     */
    public function delete($key)
    {
        self::deleteValue($this->data, $key);
        return $this;
    }

    /**
     * Delete multiple paths at once
     *
     * @param array $keys Array of paths to delete
     * @return $this
     */
    public function deleteMultiple(array $keys): self
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }
        return $this;
    }

    /**
     * Increase numeric value
     *
     * @param string $key
     * @param float $number
     * @return float
     */
    public function plus(string $key, float $number): float
    {
        $newAmount = $this->get($key, 0) + $number;
        $this->set($key, $newAmount);
        return $newAmount;
    }

    /**
     * Reduce numeric value
     *
     * @param string $key
     * @param float $number
     * @return float
     */
    public function minus(string $key, float $number): float
    {
        $newAmount = $this->get($key, 0) - $number;
        $this->set($key, $newAmount);
        return $newAmount;
    }

    /**
     * Delete all data, data from path or array of paths and
     * optionally format path if it doesn't exist
     *
     * @param mixed|null $key Path or array of paths to clean
     * @param boolean $format Format option
     */
    public function clear($key = null, $format = false)
    {
        if (is_string($key)) {
            $keys = self::parseKey($key);
            $current = &$this->data;
            
            foreach ($keys as $key) {
                if (!isset($current[$key]) || !is_array($current[$key])) {
                    if ($format === true) {
                        $current[$key] = [];
                    } else {
                        return;
                    }
                }
                $current = &$current[$key];
            }
            $current = [];
        } elseif (is_array($key)) {
            foreach ($key as $k) {
                $this->clear($k, $format);
            }
        } elseif (is_null($key)) {
            $this->data = [];
        }
    }

    /**
     * Set data
     *
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Set data as a reference
     *
     * @param array $data
     */
    public function setDataAsRef(array &$data)
    {
        $this->data = &$data;
    }

    /**
     * Get first element
     *
     * @param mixed $default Default value if empty
     * @return mixed
     */
    public function first($default = null)
    {
        return $this->data ? reset($this->data) : $default;
    }

    /**
     * Get last element
     *
     * @param mixed $default Default value if empty
     * @return mixed
     */
    public function last($default = null)
    {
        return $this->data ? end($this->data) : $default;
    }

    /**
     * Get nth element
     *
     * @param int $index Index to retrieve
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public function nth(int $index, $default = null)
    {
        $values = array_values($this->data);
        return $values[$index] ?? $default;
    }

    /**
     * Get random element(s)
     *
     * @param int $count Number of random elements to return
     * @return mixed
     */
    public function random($count = 1)
    {
        if (empty($this->data)) {
            return $count === 1 ? null : [];
        }
        
        if ($count === 1) {
            $randomKey = array_rand($this->data);
            return $this->data[$randomKey];
        }
        
        $count = min($count, count($this->data));
        $randomKeys = array_rand($this->data, $count);
        $randomKeys = (array)$randomKeys;
        
        return array_intersect_key($this->data, array_flip($randomKeys));
    }

    /**
     * Shuffle the data
     *
     * @return $this
     */
    public function shuffle(): self
    {
        $shuffled = $this->data;
        shuffle($shuffled);
        $this->data = $shuffled;
        return $this;
    }

    /**
     * Reverse the data
     *
     * @return $this
     */
    public function reverse(): self
    {
        $this->data = array_reverse($this->data, true);
        return $this;
    }

    /**
     * Filter data using callback
     *
     * @param callable $callback
     * @return $this
     */
    public function filter(callable $callback): self
    {
        $this->data = array_filter($this->data, $callback, ARRAY_FILTER_USE_BOTH);
        return $this;
    }

    /**
     * Filter data by key-value conditions
     *
     * @param string $key
     * @param string $operator
     * @param mixed $value
     * @return $this
     */
    public function where($key, $operator, $value = null): self
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }
        
        return $this->filter(function($item) use ($key, $operator, $value) {
            $itemValue = is_array($item) ? ($item[$key] ?? null) : $item;
            
            switch ($operator) {
                case '=':  case '==': return $itemValue == $value;
                case '===': return $itemValue === $value;
                case '!=': return $itemValue != $value;
                case '!==': return $itemValue !== $value;
                case '>':  return $itemValue > $value;
                case '<':  return $itemValue < $value;
                case '>=': return $itemValue >= $value;
                case '<=': return $itemValue <= $value;
                case 'in': return in_array($itemValue, (array)$value);
                case 'not_in': return !in_array($itemValue, (array)$value);
                default: return false;
            }
        });
    }

    /**
     * Map data using callback
     *
     * @param callable $callback
     * @return $this
     */
    public function map(callable $callback): self
    {
        $this->data = array_map($callback, $this->data);
        return $this;
    }

    /**
     * Pluck values from array items
     *
     * @param string $key
     * @return array
     */
    public function pluck($key): array
    {
        return array_map(function($item) use ($key) {
            return is_array($item) ? ($item[$key] ?? null) : null;
        }, $this->data);
    }

    /**
     * Group data by key
     *
     * @param string $key
     * @return $this
     */
    public function groupBy($key): self
    {
        $grouped = [];
        foreach ($this->data as $item) {
            $groupKey = is_array($item) ? ($item[$key] ?? 'undefined') : $item;
            $grouped[$groupKey][] = $item;
        }
        $this->data = $grouped;
        return $this;
    }

    /**
     * Sum values
     *
     * @param string|null $key Key to sum, null for direct values
     * @return float
     */
    public function sum($key = null): float
    {
        if ($key === null) {
            return array_sum($this->data);
        }
        
        return array_sum($this->pluck($key));
    }

    /**
     * Calculate average
     *
     * @param string|null $key Key to average, null for direct values
     * @return float
     */
    public function avg($key = null): float
    {
        $count = $this->count();
        return $count > 0 ? $this->sum($key) / $count : 0;
    }

    /**
     * Get maximum value
     *
     * @param string|null $key Key to find max, null for direct values
     * @return mixed
     */
    public function max($key = null)
    {
        if ($key === null) {
            return empty($this->data) ? null : max($this->data);
        }
        
        $values = $this->pluck($key);
        return empty($values) ? null : max($values);
    }

    /**
     * Get minimum value
     *
     * @param string|null $key Key to find min, null for direct values
     * @return mixed
     */
    public function min($key = null)
    {
        if ($key === null) {
            return empty($this->data) ? null : min($this->data);
        }
        
        $values = $this->pluck($key);
        return empty($values) ? null : min($values);
    }

    /**
     * Get values matching wildcard pattern
     *
     * @param string $pattern Pattern with * as wildcard
     * @param mixed $default Default value
     * @return array
     */
    public function getWildcard($pattern, $default = null): array
    {
        $result = [];
        $pattern = '/^' . str_replace('*', '(.*)', preg_quote($pattern, '/')) . '$/';
        
        foreach ($this->data as $key => $value) {
            if (preg_match($pattern, $key, $matches)) {
                $result[$key] = $value;
            }
        }
        
        return $result;
    }

    /**
     * Get values matching regex pattern
     *
     * @param string $pattern Regex pattern
     * @param mixed $default Default value
     * @return array
     */
    public function getRegex($pattern, $default = null): array
    {
        $result = [];
        
        foreach ($this->data as $key => $value) {
            if (preg_match($pattern, $key)) {
                $result[$key] = $value;
            }
        }
        
        return $result;
    }

    /**
     * Clear the key cache
     */
    public static function clearKeyCache(): void
    {
        self::$keyCache = [];
    }

    /**
     * Get cache statistics
     *
     * @return array
     */
    public static function getCacheStats(): array
    {
        return [
            'size' => count(self::$keyCache),
            'max_size' => self::$maxCacheSize,
            'memory_usage' => memory_get_usage(true)
        ];
    }

    // ArrayAccess implementation
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetUnset($offset): void
    {
        $this->delete($offset);
    }

    // Magic methods
    public function __set($key, $value = null)
    {
        $this->set($key, $value);
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __isset($key)
    {
        return $this->has($key);
    }

    public function __unset($key)
    {
        $this->delete($key);
    }

    /**
     * Check for emptiness
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    /**
     * Return all data as array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Return as json string
     *
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return $this->toArray();
    }

    // Iterator implementation
    public function current(): mixed
    {
        return current($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function key(): mixed
    {
        return key($this->data);
    }

    public function valid(): bool
    {
        $key = key($this->data);
        return ($key !== null && $key !== false);
    }

    public function rewind(): void
    {
        reset($this->data);
    }

    // Countable implementation
    public function count(): int
    {
        return count($this->data);
    }
}
