<?php

namespace Tumainimosha\Verifiable\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use Tumainimosha\Verifiable\Models\VerificationCode;
use Tumainimosha\Verifiable\Services\PinService;

/**
 * Trait Verifiable.
 * @package Tumainimosha\Verifiable
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait VerifiableTrait
{
    /**
     * @return MorphMany
     */
    public function verification_codes()
    {
        return $this->morphMany(VerificationCode::class, 'object');
    }

    /**
     * @param string|null $action
     * @param \Illuminate\Database\Eloquent\Model|null $actor
     * @param \DateTimeInterface|null $expires_at
     * @return string
     */
    public function getVerificationToken(string $action = null, Model $actor = null, \DateTimeInterface $expires_at = null): string
    {
        $token_ttl = config('verifiable.token_ttl');

        /** @var PinService $pinService */
        $pinService = app(PinService::class);

        $token = $pinService->generatePin(4);

        if (is_null($expires_at)) {
            $expires_at = Carbon::now()->addMinutes($token_ttl);
        }

        DB::transaction(function () use ($token, $action, $expires_at, $actor) {
            // Persist token in db
            $codeModel = $this->verification_codes()->create([
                'code' => $token,
                'action' => $action,
                'expires_at' => $expires_at,
            ]);

            // Attach Actor Model if provided
            if (!is_null($actor)) {
                $codeModel->actor()->attach($actor);
            }
        });

        return $token;
    }

    public function verifyToken(string $token, string $action = null): array
    {
        // Fetch token from db
        $codeModel = $this->verification_codes()
            ->latest()
            ->where('action', '=', $action)
            ->first();

        if (!$codeModel instanceof VerificationCode) {
            return [
                'status' => false,
                'statusDesc' => 'NotFound',
                'message' => 'Token Not Supplied',
            ];
        }

        if ((string) $codeModel->code !== (string) $token) {
            if (is_null($codeModel->attempts)) {
                $codeModel->attempts = 1;
            } else {
                $codeModel->increment('attempts');
            }

            $codeModel->save();

            return [
                'status' => false,
                'statusDesc' => 'Invalid',
                'message' => 'Invalid Token Provided',
            ];
        }

        // Maximum attempts exceeded
        if ($codeModel->attempts >= config('verifiable.max_attempts')) {
            $codeModel->increment('attempts');
            $codeModel->save();

            $backOffTime = Carbon::now()->diffForHumans($codeModel->expires_at);

            return [
                'status' => false,
                'statusDesc' => 'MaxAttemptsExceeded',
                'retry_after' => $codeModel->expires_at,
                'message' => 'Maximum attempts to validate token exceeded. Retry after ' . $backOffTime,
            ];
        }

        if (Carbon::now()->greaterThan($codeModel->expires_at)) {
            return [
                'status' => false,
                'statusDesc' => 'Expired',
                'message' => 'Token has expired',
            ];
        }

        $codeModel->verified_at = now();
        $codeModel->save();

        return [
            'status' => true,
            'statusDesc' => 'Success',
            'message' => 'Token verified successfully',
        ];
    }
}
