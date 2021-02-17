<?php

namespace App;

use App\Filters\Filterable;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use Filterable;
}
