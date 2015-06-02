<?php
/**
 * Class responsible of property type definition
 * @package Recommender/Api/Helpers
 * @copyright (c) Modgen s.r.o. 2015
 * @author Mirek Ratman
 * @version 1.0
 * @since 2015-05-21
 * @license
 */

namespace Recommender\Api\Helpers;

class Property
{
    /**
     * Return right property type
     * @param string $value - property to check
     * @param string $propertyName - property name
     * @param boolean $forceSetType - force type of value to "set"
     * @return string
     */
    public static function getPropertyType($value, $propertyName = null, $forceSetType = false)
    {
        $result = 'string';
        switch (gettype($value)) {
            case 'integer':
                $result = 'int';
                break;
            case 'double':
                $result = 'double';
                break;
            case 'boolean':
                $result = 'boolean';
                break;
            case 'string':
            default:
                $result = 'string';
                break;
        }

        if ($forceSetType) {
            $result = 'set';
        }

        if (self::is_timestamp($value)) {
            $result = 'timestamp';
        }

        if (self::is_timestamp(strtotime($value))) {
            $result = 'timestamp';
        }

        if( $propertyName === 'price' ){
            $result = 'double';
        }

        if( $propertyName === 'available' ){
            $result = 'boolean';
        }

        return $result;
    }

    /**
     * Check is property is timestamp
     * @param string $timestamp
     * @return boolean
     */
    private static function is_timestamp($timestamp)
    {
        $check = (is_int($timestamp) OR is_float($timestamp))
            ? $timestamp
            : (string) (int) $timestamp;

        return  ($check === $timestamp)
        AND ( (int) $timestamp <=  PHP_INT_MAX)
        AND ( (int) $timestamp >= ~PHP_INT_MAX);
    }
}
