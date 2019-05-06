<?php

namespace Tumainimosha\Verifiable\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Tumainimosha\Verifiable\Traits\VerifiableTrait;

class Order extends Model
{
    use VerifiableTrait;
}
