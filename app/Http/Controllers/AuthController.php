<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Helpers\CaesarCipher;

class AuthController extends Controller
{
    /**
     * Register a new user, validate against the external API,
     * automatically log them in, and redirect to the home page.
     */

    // 2. Check that 'tahun_lulus' is within the last 2 years.
    // $currentYear = \Carbon\Carbon::now()->year;
    // if ($request->tahun_lulus < ($currentYear - 2) || $request->tahun_lulus > $currentYear) {
    //     return redirect()->back()
    //         ->withErrors(['tahun_lulus' => 'Tahun lulus must be within the last 2 years.'])
    //         ->withInput();
    // }

    public function register(Request $request)
    {
        // 1. Validate input.
        $request->validate([
            'name'        => 'required|string|max:255',
            'nim'         => 'required|string|max:255|unique:users,nim',
            'prodi'       => 'required|string|max:255',
            'tahun_lulus' => 'required|integer',
            'fakultas'    => 'required|string|max:255',
            'password'    => 'required|string|min:8|confirmed',
        ]);

        // 2. Get the API token (cached or fresh request).
        $token = Cache::get('external_api_token') ?? $this->getExternalApiToken();

        if (!$token) {
            return redirect()->back()->withErrors(['api' => 'Failed to retrieve authentication token.']);
        }

        // 3. Query the external API (Only using 'nim' as parameter).
        $apiUrl = "https://cis-dev.del.ac.id/api/library-api/alumni";
        $response = Http::withToken($token)
            ->withoutVerifying()
            ->get($apiUrl, ['nim' => $request->nim]);

        // 4. Check API response.
        if ($response->failed()) {
            return redirect()->back()->withErrors(['api' => 'Failed to fetch alumni data.']);
        }

        $apiData = $response->json();

        // Ensure the structure exists and contains at least one alumni record.
        if (!isset($apiData['data']['alumni']) || empty($apiData['data']['alumni'])) {
            return redirect()->back()->withErrors(['api' => 'No matching record found in external API.']);
        }

        // 5. Compare API data with registration form data.
        $alumni = $apiData['data']['alumni'][0];

        if (
            trim($alumni['nim']) != trim($request->nim) ||
            trim($alumni['nama']) != trim($request->name) ||
            trim($alumni['prodi_name']) != trim($request->prodi) ||
            trim($alumni['tahun_lulus']) != trim($request->tahun_lulus) ||
            trim($alumni['fakultas']) != trim($request->fakultas)
        ) {
            return redirect()->back()->withErrors(['data' => 'Registration data does not match our records.']);
        }

        // 6. Convert 'fakultas' and 'prodi' strings to their respective IDs
        //    If the record doesn't exist, you can decide to create it or throw an error.
        //    Below, we throw an error if not found.

        $fakultasRecord = Fakultas::where('name', $request->fakultas)->first();
        if (!$fakultasRecord) {
            return redirect()->back()->withErrors([
                'fakultas' => "Fakultas '{$request->fakultas}' not found in local database."
            ]);
        }

        $prodiRecord = Prodi::where('name', $request->prodi)->first();
        if (!$prodiRecord) {
            return redirect()->back()->withErrors([
                'prodi' => "Prodi '{$request->prodi}' not found in local database."
            ]);
        }

        // 7. Create the new user with IDs instead of strings.
        $user = User::create([
            'name'         => CaesarCipher::encrypt($request->name),
            'nim'          => $request->nim,
            'tahun_lulus'  => $request->tahun_lulus,
            'fakultas_id'  => $fakultasRecord->id,
            'prodi_id'     => $prodiRecord->id,
            'password'     => Hash::make($request->password)
        ]);

        $user->assignRole('alumni');

        // 8. Redirect to login page with success message.
        return redirect()->route('login')->with('success', 'Registrasi sukses');
    }


    /**
     * Login a user using local authentication,
     * automatically log them in, and redirect to the home page.
     */
    public function login(Request $request)
    {
        // Validate login credentials.
        $credentials = $request->validate([
            'nim'      => 'required|string|exists:users,nim',
            'password' => 'required|string'
        ]);

        if (config('app.login_require_captcha')) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        // Retrieve the user.
        $user = User::where('nim', $credentials['nim'])->first();

        // Check if the password is correct.
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return redirect()->back()
                ->withErrors(['nim' => 'Invalid credentials'])
                ->withInput();
        }

        // Log in the user.
        Auth::login($user);

        // Redirect the user "admmin" to admin page.
        if ($user->hasRole('admin')) {
            return redirect('/admin')->with('success', 'Welcome, Ketua Guild!');
        }

        // Redirect the user "alumni" to home page.
        return redirect('/');
    }

    public function logout(Request $request)
    {
        // Log out the user from the session
        Auth::logout();

        // Invalidate the current session.
        $request->session()->invalidate();

        // Regenerate the CSRF token for security.
        $request->session()->regenerateToken();

        // Redirect to the login page with a success message.
        return redirect('/')->with('success', 'Logged out successfully');
    }


    /**
     * Log in to the external API to obtain the token for GET requests.
     * This method can be called on app initialization.
     */
    public function getExternalApiToken()
    {
        $credentials = [
            'username' => 'johannes',
            'password' => 'Del@2022',
        ];

        $response = Http::withoutVerifying()
            ->asForm()
            ->post('https://cis-dev.del.ac.id/api/jwt-api/do-auth', $credentials);


        if ($response->failed() || !$response->json('result')) {
            // For web-based methods you might want to   the error instead of returning a redirect.
            return null;
        }

        $apiToken = $response->json('token');
        // Cache the token for one hour.
        Cache::put('external_api_token', $apiToken, now()->addHour());

        return $apiToken;
    }
}
