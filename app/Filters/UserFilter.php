<?php


namespace App\Filters;


class UserFilter extends QueryFilter
{
    public function name($value)
    {
        return parent::like('name', $value);
    }

    public function username($value)
    {
        return parent::like('username', $value);
    }

    public function email($value)
    {
        return parent::like('email', $value);
    }
}
