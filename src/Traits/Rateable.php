<?php

namespace Laravelir\Rateable\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Rateable
{
    protected static function bootRateable()
    {
        static::deleted(function ($rateable) {
            foreach ($rateable->rates as $rate) {
                $rate->delete();
            }
        });
    }

    public function rateableModel(): string
    {
        return config('rateable.model');
    }

    public function rates(): MorphMany
    {
        return $this->ratesRelation();
    }

    public function ratesRelation(): MorphMany
    {
        return $this->morphMany($this->rateableModel(), 'rateable');
    }

    public function approvedComments()
    {
        return $this->morphMany($this->rateableModel(), 'rateable')->approved();
    }

    public function rateAs(Model $user, string $rate)
    {
        $rateClass = config('rateable.model');

        $rate = $rateClass::create([
            'rate' => $rate,
            // 'approved' => ($user instanceof User) ? !$user->mustBeCommentApprove($this) : false,
            'rateable_id' => $this->id,
            'rateable_type' => get_class($this),
            'rateorable_id'   => $user->id,
            'rateorable_type' => get_class($user),
        ]);

        return $rate;
    }
}
