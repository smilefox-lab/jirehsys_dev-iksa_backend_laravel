<?php

namespace Botble\Vendor\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Vendor\Http\Requests\API\ResendEmailVerificationRequest;
use Botble\Vendor\Http\Requests\API\VerifyEmailRequest;
use Botble\Vendor\Notifications\ConfirmEmailNotification;
use Botble\Vendor\Repositories\Interfaces\VendorInterface;
use Hash;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    /**
     * @var VendorInterface
     */
    protected $vendorRepository;

    /**
     * AuthenticationController constructor.
     *
     * @param VendorInterface $vendorRepository
     */
    public function __construct(VendorInterface $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * Verify email
     *
     * Mark the authenticated user's email address as verified.
     *
     * @bodyParam email string required The email of the user.
     * @bodyParam token string required The token to verify user's email.
     *
     * @group Authentication
     *
     * @param VerifyEmailRequest $request
     * @param BaseHttpResponse $response
     *
     * @return BaseHttpResponse
     */
    public function verify(VerifyEmailRequest $request, BaseHttpResponse $response)
    {
        $vendor = $this
            ->vendorRepository
            ->getFirstBy([
                'email' => $request->input('email'),
            ]);

        if (!$vendor) {
            return $response
                ->setError()
                ->setMessage(__('User not found!'))
                ->setCode(404);
        }

        if (!Hash::check($request->input('token'), $vendor->email_verify_token)) {
            return $response
                ->setError()
                ->setMessage(__('Token is invalid or expired!'));
        }

        if (!$vendor->markEmailAsVerified()) {
            return $response
                ->setError()
                ->setMessage(__('Has error when verify email!'));
        }

        event(new Verified($vendor));

        $vendor->email_verify_token = null;
        $this->vendorRepository->createOrUpdate($vendor);

        return $response
            ->setMessage(__('Verify email successfully!'));
    }

    /**
     * Resend email verification
     *
     * Resend the email verification notification.
     *
     * @bodyParam email string required The email of the user.
     *
     * @group Authentication
     *
     * @param ResendEmailVerificationRequest $request
     * @param BaseHttpResponse $response
     *
     * @return BaseHttpResponse
     */
    public function resend(ResendEmailVerificationRequest $request, BaseHttpResponse $response)
    {
        $vendor = $this
            ->vendorRepository
            ->getFirstBy([
                'email' => $request->input('email'),
            ]);

        if (!$vendor) {
            return $response
                ->setError()
                ->setMessage(__('User not found!'))
                ->setCode(404);
        }

        if ($vendor->hasVerifiedEmail()) {
            return $response
                ->setError()
                ->setMessage(__('This user has verified email'));
        }

        $token = Hash::make(Str::random(32));

        $vendor->email_verify_token = $token;
        $vendor->save();

        $vendor->notify(new ConfirmEmailNotification($token));

        return $response
            ->setMessage(__('Resend email verification successfully!'));
    }
}
