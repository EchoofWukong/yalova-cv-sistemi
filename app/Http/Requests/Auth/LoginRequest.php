<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            // İŞTE BURASI: KESİN VE GARANTİLİ TÜRKÇE HATA MESAJI
            throw ValidationException::withMessages([
                'email' => 'Girdiğiniz e-posta hatalı veya kayıtlı değil.',
            ]);
        }
        // GÜVENLİK KALKANI: Öğretmen onaylı değilse sistemden at!
        if (Auth::user()->role === 'teacher' && Auth::user()->is_approved == false) {
            Auth::logout(); // Oturumu anında kapat
            throw ValidationException::withMessages([
                'email' => 'Hesabınız henüz onaylanmadı. Lütfen Gökhan Bey\'in onayını bekleyin.',
            ]);
        }
        $user = Auth::user();
    if (!$user->is_approved) {
        Auth::logout(); // Girişi iptal et
        throw ValidationException::withMessages([
            'email' => 'Hesabınız henüz yönetici tarafından onaylanmamıştır. Lütfen e-postanıza gelecek onay linkini bekleyin.',
        ]);
    }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        // İŞTE BURASI: ÇOK FAZLA DENEME YAPINCA ÇIKAN TÜRKÇE MESAJ
        throw ValidationException::withMessages([
            'email' => 'Çok fazla hatalı giriş denemesi yaptınız. Lütfen ' . $seconds . ' saniye sonra tekrar deneyin.',
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}