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

class Roles
{
    /**
     * Return right property type
     * @param string $value - property to check
     * @param boolean $forceSetType - force type of value to "set"
     * @return string
     */
    public static function getPropertyType($name, $forceSetType = false)
    {
        switch ($name) {
            case 'price':
                return 'income';
                break;
            case 'name':
                return 'name';
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * Check is property is timestamp
     * @param string $timestamp
     * @return boolean
     */
    private static function is_timestamp($timestamp)
    {
        return ((string)(int)$timestamp === $timestamp)
        && ($timestamp <= PHP_INT_MAX)
        && ($timestamp >= ~PHP_INT_MAX)
        && (!strtotime($timestamp));
    }
}
