<?php

namespace Francerz\Enum;

use ReflectionClass;

abstract class AbstractEnum
{
    private static $enumCache = [];
    private $value;

    final public function __construct($value)
    {
        if (!static::hasValue($value)) {
            throw new EnumException("Invalid value {$value} for enum.");
        }
        $this->value = $value;
    }

    public static function getConstants()
    {
        $class = get_called_class();
        if (array_key_exists($class, self::$enumCache)) {
            return self::$enumCache[$class];
        }

        $ref = new ReflectionClass($class);
        $consts = $ref->getConstants();
        self::$enumCache[$class] = $consts;
        return $consts;
    }

    public static function getKeys()
    {
        return array_keys(static::getConstants());
    }

    public static function getValues()
    {
        return array_values(static::getConstants());
    }

    public static function hasKey($key)
    {
        $consts = static::getConstants();
        return in_array($key, array_keys($consts), true);
    }

    public static function hasValue($value, $strict = true)
    {
        $consts = static::getConstants();
        return in_array($value, $consts, $strict);
    }

    public static function findValue($key)
    {
        $constants = static::getConstants();
        return $constants[$key] ?? null;
    }

    public static function findKey($value, $strict = true)
    {
        $constants = static::getConstants();
        $key = array_search($value, $constants, $strict);
        if ($key === false) {
            return null;
        }
        return $key;
    }

    public static function fromValue($value)
    {
        return new static($value);
    }

    public static function fromKey($key)
    {
        $constants = static::getConstants();
        if (!array_key_exists($key, $constants)) {
            throw new EnumException('Unknown Enum constant name.');
        }
        return new static($constants[$key]);
    }

    public static function coerce($valueOrKey, $strict = true)
    {
        if ($valueOrKey instanceof static) {
            return $valueOrKey;
        }
        $constants = static::getConstants();
        if (in_array($valueOrKey, $constants, $strict)) {
            return new static($valueOrKey);
        }

        if (!is_scalar($valueOrKey)) {
            return null;
        }

        if (array_key_exists($valueOrKey, $constants)) {
            return new static($constants[$valueOrKey]);
        }

        return null;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function is($other, $strict = true)
    {
        if ($other instanceof static) {
            return $strict ?
                $this->value === $other->value :
                $this->value == $other->value;
        }
        return $strict ?
            $this->value === $other :
            $this->value == $other;
    }

    public function in(array $others)
    {
        foreach ($others as $o) {
            if ($this->is($o)) {
                return true;
            }
        }
        return false;
    }

    public function __toString()
    {
        return (string)$this->value;
    }
}
