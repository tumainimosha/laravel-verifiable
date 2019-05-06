<?php

namespace Tumainimosha\Verifiable\Services;

class PinService
{
    /**
     * Refer https://thisinterestsme.com/generating-4-digit-pin-code-php/.
     *
     * @param int $digits
     * @return string
     */
    public function generatePin(int $digits = 4): string
    {
        $i = 0; //counter
        $pin = ''; //our default pin is blank.
        while ($i < $digits) {
            //generate a random number between 0 and 9.
            $pin .= mt_rand(0, 9);
            $i++;
        }

        return $pin;
    }
}
