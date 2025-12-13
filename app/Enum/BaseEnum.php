<?php


namespace App\Enum;


use ReflectionClass;

abstract class BaseEnum
{

    /**
     * @return array
     */
    public static function getStaticAssociativeArray():array
    {
        try {
            $class = new ReflectionClass(get_called_class());
        } catch (\ReflectionException $e) {
            \Log::error('Enum Reflection Class Error: '. $e->getMessage());
        }
        return $class->getDefaultProperties();
    }
    /**
     * @return array
     */
    public static function getConstAssociativeArray():array
    {
        try {
            $class = new ReflectionClass(get_called_class());
        } catch (\ReflectionException $e) {
            \Log::error('Enum Reflection Class Error: '. $e->getMessage());
        }
        return $class->getConstants();
    }
    /**
     * @return array
     */
    public static function getStaticKeys():array
    {
        try {
            $class = new ReflectionClass(get_called_class());
        } catch (\ReflectionException $e) {
            \Log::error('Enum Reflection Class Error: '. $e->getMessage());
        }
        return array_keys($class->getDefaultProperties());
    }
    /**
     * @return array
     */
    public static function getKeys():array
    {
        try {
            $class = new ReflectionClass(get_called_class());
        } catch (\ReflectionException $e) {
            \Log::error('Enum Reflection Class Error: '. $e->getMessage());
        }
        return array_keys($class->getConstants());
    }
    /**
     * @return array
     */
    public static function getStaticValues():array
    {
        try {
            $class = new ReflectionClass(get_called_class());
        } catch (\ReflectionException $e) {
            \Log::error('Reflection Class Error: '. $e->getMessage());
        }
        return array_values($class->getDefaultProperties());
    }

    /**
     * @return array
     */
    public static function getValues():array
    {
        try {
            return array_values((new ReflectionClass(get_called_class()))->getDefaultProperties());
        } catch (\ReflectionException $e) {
            \Log::error('Reflection Class Error: '. $e->getMessage());
        }
        return array_values($class->getConstants());
    }

    /**
     * @return string
     */
    public static function getStaticValue($value):string
    {
        try {
            $class = new ReflectionClass(get_called_class());
            $enum = $class->getDefaultProperties();
            return str_replace('_',' ', $enum[$value]);
        } catch (\ReflectionException $e) {
            \Log::error('Reflection Class Error: '. $e->getMessage());
            return "";
        }
    }
    /**
     * @return string
     */
    public static function getValue($value):string
    {
        try {
            $class = new ReflectionClass(get_called_class());
            $enum = $class->getConstants();
            return str_replace('_',' ', $enum[$value]);
        } catch (\ReflectionException $e) {
            \Log::error('Reflection Class Error: '. $e->getMessage());
            return "";
        }
    }
    /**
     * @return string
     */
    public static function getStaticKey($value):string
    {
        try {
            $class = new ReflectionClass(get_called_class());
            $enum = array_flip($class->getDefaultProperties());
            return $enum[$value];
        } catch (\ReflectionException $e) {
            \Log::error('Reflection Class Error: '. $e->getMessage());
        }
        return '';
    }
    /**
     * @return string
     */
    public static function getKey($value):string
    {
        try {
            $class = new ReflectionClass(get_called_class());
            $enum = array_flip($class->getConstants());
            return $enum[$value];
        } catch (\ReflectionException $e) {
            \Log::error('Reflection Class Error: '. $e->getMessage());
        }
        return '';
    }

}

