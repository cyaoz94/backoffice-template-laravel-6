<?php


namespace App\Filters;


class RoleFilter extends QueryFilter
{
    public function name($value)
    {
        return parent::like('name', $value);
    }
}
