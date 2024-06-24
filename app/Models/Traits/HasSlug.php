<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait HasSlug {
    /**
     * The column name for generating the slug.
     *
     * @var string
     */
    protected static $slugColumn = 'name';

    /**
     * The "booted" method of the trait.
     */
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            $slugColumn = self::$slugColumn;

            if (empty($model->$slugColumn)) {
                $model->slug = Str::slug($model->$slugColumn);
            }
        });
    }
}
