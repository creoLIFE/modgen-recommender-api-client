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
     * @param boolean $forceSetType - force type of value to "set"
     * @return string
     */
    public static function getPropertyType($value, $forceSetType = false)
    {
        switch (gettype($value)) {
            case 'integer':
                return 'int';
                break;
            case 'double':
                return 'double';
                break;
            case 'boolean':
                return 'boolean';
                break;
            case 'string':
            default:
                if ($forceSetType) {
                    return 'set';
                }
                if ((is_numeric($value) && (int)$value == $value)) {
                    return 'timestamp';
                }
                return 'string';
                break;
        }
    }

}
