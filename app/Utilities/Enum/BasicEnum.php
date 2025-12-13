<?php


namespace App\Utilities\Enum;

use ReflectionClass;

abstract class BasicEnum
{
    /**
     * @return array
     */
    public static function getKeys()
    {
        try {
            $class = new ReflectionClass(get_called_class());
        } catch (\ReflectionException $e) {
            \Log::error('Reflection Class Error: ' . $e->getMessage());
        }

        return array_keys($class->getConstants());
    }

    public static function getKeysValues()
    {
        try {
            $class = new ReflectionClass(get_called_class());
        } catch (\ReflectionException $e) {
            \Log::error('Reflection Class Error: ' . $e->getMessage());
        }

        return array_flip($class->getConstants());
    }


    public static function getKeysValuesArray()
    {
        try {
            $class = new ReflectionClass(get_called_class());
        } catch (\ReflectionException $e) {
            \Log::error('Reflection Class Error: ' . $e->getMessage());
        }

        $arr = [];
        $data_original = $class->getConstants();

        $i = 0;
        foreach ($data_original as $key => $value) {
            $arr[$i]['key'] = $key;
            $arr[$i]['value'] = $value;
            $i++;
        }
        return $arr;
    }



    /**
     * @return array
     */
    public static function getValues()
    {
        try {
            $class = new ReflectionClass(get_called_class());
        } catch (\ReflectionException $e) {
            \Log::error('Reflection Class Error: ' . $e->getMessage());
        }

        return array_values($class->getConstants());
    }

    public static function getValueByKey($key)
    {
        try {
            $class = new ReflectionClass(get_called_class());
            $key = strtoupper($key);

            return $class->getConstants()[$key];
        } catch (\ReflectionException $e) {
            \Log::error('Reflection Class Error: ' . $e->getMessage());
        }
    }

    /**
     * @return integer
     */
    public static function getKey($value)
    {
        if($value==null){
            return '';
        }
        try {
            $class = new ReflectionClass(get_called_class());
            $enum = array_flip($class->getConstants());
            return str_replace('_', ' ', $enum[$value]);
        } catch (\ReflectionException $e) {
            \Log::error('Reflection Class Error: ' . $e->getMessage());
        }
        return null;
    }
}
