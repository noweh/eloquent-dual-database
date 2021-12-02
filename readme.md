# DualDB plugin for Eloquent models

[![Laravel](https://img.shields.io/badge/Laravel-v6/8-828cb7.svg?logo=Laravel&color=FF2D20)](https://laravel.com/)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-green.svg)](licence.md)

This package provides a trait that override the Eloquent Builder in order to improve the management of Dual DB, with one DB for **Read** and Another for **Write**.

Indeed, this plugin allows you to retrieve freshly inserted data, even if the have not yet been duplicated on the Read Database.

## Installation
First you need to add the component to your composer.json
```
composer require noweh/laravel-plugin-dual-database
```
Update your packages with *composer update* or install with *composer install*.

## Simple Usage

Use the trait `Noweh\EloquentDualDatabase\PluginDualDBTrait` in your Model.

### Example

```
use Noweh\EloquentDualDatabase\EloquentDualDatabaseTrait;

class MyModel extends Model
{
    use EloquentDualDatabaseTrait;
    
    ...
}
```

## Or, for a custom usage

Override the Eloquent Method `newEloquentBuilder($query)` in your Model.

### Example

```
use Noweh\EloquentDualDatabase\CustomEloquentBuilder;

class MyModel extends Model
{
    public function newEloquentBuilder($query): Builder
    {
        ...
        return new CustomEloquentBuilder($query);
    }
    
    ...
}
```

