<?php

namespace Tumainimosha\Verifiable\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    protected $fillable = [
        'code',
        'action',
        'expires_at',
    ];

    protected $casts = [
        'code' => 'string',
        'action' => 'string',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * An object element is the item being verified, this function morphs to the specific implementation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function object()
    {
        return $this->morphTo();
    }

    /**
     * An actor element is the agent performing an action on the object, this function morphs to the specific implementation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function actor()
    {
        return $this->morphTo();
    }
}
