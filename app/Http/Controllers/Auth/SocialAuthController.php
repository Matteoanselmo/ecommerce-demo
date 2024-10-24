<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialAuthController extends Controller {
    // Reindirizza a Google
    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    // Gestisce il callback di Google
    public function handleGoogleCallback() {
        $user = Socialite::driver('google')->user();
        dump($user);
    }

    // Reindirizza a Facebook
    public function redirectToFacebook() {
        return Socialite::driver('facebook')->redirect();
    }

    // Gestisce il callback di Facebook
    public function handleFacebookCallback() {
        $user = Socialite::driver('facebook')->stateless()->user();
        $this->loginOrCreateUser($user, 'facebook');
    }

    // Reindirizza ad Apple
    public function redirectToApple() {
        return Socialite::driver('apple')->redirect();
    }

    // Gestisce il callback di Apple
    public function handleAppleCallback() {
        $user = Socialite::driver('apple')->stateless()->user();
        $this->loginOrCreateUser($user, 'apple');
    }

    // Funzione per login o registrazione
    protected function loginOrCreateUser($socialUser, $provider) {
        $user = User::updateOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ]
        );

        Auth::login($user);

        return redirect()->route('dashboard'); // Reindirizza alla dashboard o altra pagina
    }
}
