<?php

namespace App\Models;

/**
 * App\Models\Config
 *
 * @property string $token
 * @property string|null $value
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Config whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Config whereValue($value)
 * @mixin \Eloquent
 */
class Config extends BaseModel
{
    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var bool $timestamps */
    public $timestamps = false;

    /** @var string $table */
    protected $table = 'config';

    /** @var string $primaryKey */
    protected $primaryKey = 'token';
}
