<?php

namespace Botble\Vendor\Http\Controllers;

use App\Http\Controllers\Controller;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Vendor\Models\Vendor;
use Botble\Vendor\Repositories\Interfaces\VendorInterface;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Botble\ACL\Traits\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SeoHelper;
use URL;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = null;

    /**
     * @var VendorInterface
     */
    protected $vendorRepository;

    /**
     * Create a new controller instance.
     *
     * @param VendorInterface $vendorRepository
     */
    public function __construct(VendorInterface $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
        $this->redirectTo = route('public.vendor.register');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function showRegistrationForm()
    {
        SeoHelper::setTitle(__('Register'));

        return view('plugins/vendor::auth.register');
    }

    /**
     * Confirm a user with a given confirmation code.
     *
     * @param $email
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param VendorInterface $vendorRepository
     * @return BaseHttpResponse
     */
    public function confirm($email, Request $request, BaseHttpResponse $response, VendorInterface $vendorRepository)
    {
        if (!URL::hasValidSignature($request)) {
            abort(404);
        }

        $vendor = $vendorRepository->getFirstBy(['email' => $email]);

        if (!$vendor) {
            abort(404);
        }

        $vendor->confirmed_at = Carbon::now(config('app.timezone'));
        $this->vendorRepository->createOrUpdate($vendor);

        $this->guard()->login($vendor);

        return $response
            ->setNextUrl(route('public.vendor.dashboard'))
            ->setMessage(trans('plugins/vendor::vendor.confirmation_successful'));
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('vendor');
    }

    /**
     * Resend a confirmation code to a user.
     *
     * @param \Illuminate\Http\Request $request
     * @param VendorInterface $vendorRepository
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function resendConfirmation(Request $request, VendorInterface $vendorRepository, BaseHttpResponse $response)
    {
        $vendor = $vendorRepository->getFirstBy(['email' => $request->input('email')]);
        if (!$vendor) {
            return $response
                ->setError()
                ->setMessage(__('Cannot find this account!'));
        }

        $this->sendConfirmationToUser($vendor);

        return $response
            ->setMessage(trans('plugins/vendor::vendor.confirmation_resent'));
    }

    /**
     * Send the confirmation code to a user.
     *
     * @param Vendor $vendor
     */
    protected function sendConfirmationToUser($vendor)
    {
        // Notify the user
        $notificationConfig = config('plugins.vendor.general.notification');
        if ($notificationConfig) {
            $notification = app($notificationConfig);
            $vendor->notify($notification);
        }
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function register(Request $request, BaseHttpResponse $response)
    {
        $this->validator($request->input())->validate();

        event(new Registered($vendor = $this->create($request->input())));

        if (setting('verify_account_email', config('plugins.vendor.general.verify_email'))) {
            $this->sendConfirmationToUser($vendor);
            return $this->registered($request, $vendor)
                ?: $response->setNextUrl($this->redirectPath())->setMessage(trans('plugins/vendor::vendor.confirmation_info'));
        }

        $vendor->confirmed_at = now(config('app.timezone'));
        $this->vendorRepository->createOrUpdate($vendor);
        $this->guard()->login($vendor);

        return $this->registered($request, $vendor) ?: $response->setNextUrl($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:120',
            'last_name'  => 'required|max:120',
            'email'      => 'required|email|max:255|unique:vendors',
            'password'   => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return Vendor
     */
    protected function create(array $data)
    {
        return $this->vendorRepository->create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getVerify()
    {
        return view('plugins/vendor::auth.verify');
    }
}
