<?php

namespace Noweh\EloquentDualDatabase;

use Illuminate\Database\Eloquent\Builder;

trait EloquentDualDatabaseTrait
{
    public function newEloquentBuilder($query): Builder
    {
        return new CustomEloquentBuilder($query);
    }
}
