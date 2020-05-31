<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{

  /**
   * index
   */
  public function index(Request $request)
  {
    if ($request->session()->get('linkedin_access_token') == null)
    {
      $linkedin_response_type = 'code';
      $linkedin_client_id = env('LINKEDIN_CLIENT_ID');
      $callback_url = url('auth/callback');
      $linkedin_csrf = env('LINKEDIN_CSRF');
      $linkedin_scope = 'r_liteprofile%20r_emailaddress%20w_member_social';

      $data = [
        'linkedin_login_url' => 'https://www.linkedin.com/oauth/v2/authorization' .
                                  '?response_type=' . $linkedin_response_type .
                                  '&client_id=' . $linkedin_client_id .
                                  '&redirect_uri=' . $callback_url .
                                  '&state=' . $linkedin_csrf .
                                  '&scope=' . $linkedin_scope,
      ];

      return view('welcome', $data);
    }
    elseif ($request->session()->get('linkedin_access_token') != null
            && $request->session()->get('linkedin_access_token') != null
            && time() > $request->session()->get('linkedin_token_expires_in'))
    {
      $request->session()->forget('linkedin_access_token');
      $request->session()->forget('linkedin_token_expires_in');
      
      return redirect('/');
    }
    else
    {
      $linkedin_access_token = $request->session()->get('linkedin_access_token');
      $linkedin_member_profile_url = 'https://api.linkedin.com/v2/me?projection=(id,firstName,lastName,profilePicture(displayImage~:playableStreams))';

      $response = Http::withToken($linkedin_access_token)->get($linkedin_member_profile_url);

      $linkedin_profile_response = $response->json();
      
      $full_name = $response['firstName']['localized']['en_US'] . ' ' . $response['lastName']['localized']['en_US'];
      $profile_image = $response['profilePicture']['displayImage~']['elements'][0]['identifiers'][0]['identifier'];

      $data = [
        'full_name' => $full_name,
        'profile_image' => $profile_image,
      ];
      return view('home', $data);
    }
  }

  /**
   * callback
   * remember to set LINKEDIN_CSRF, LINKEDIN_CLIENT_ID, LINKEDIN_CLIENT_SECRET in .env file.
   */
  public function callback(Request $request)
  {
    $linkedin_code = '';

    if ($request->isMethod('get'))
    {
      $linkedin_code = $request->input('code');
      $linkedin_csrf = $request->input('state');
      
      if ($linkedin_csrf != env('LINKEDIN_CSRF'))
      {
        die('Illegal request');
      }
    }

    $response = Http::asForm()->post('https://www.linkedin.com/oauth/v2/accessToken', [
      'grant_type' => 'authorization_code',
      'code' => $linkedin_code,
      'redirect_uri' => url('auth/callback'),
      'client_id' => env('LINKEDIN_CLIENT_ID'),
      'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
    ]);

    $linkedin_accesstoken_response = $response->json();
    
    $request->session()->put('linkedin_access_token', $linkedin_accesstoken_response['access_token']);
    $request->session()->put('linkedin_token_expires_in', time() + $linkedin_accesstoken_response['expires_in']);

    return redirect('/');
  }

  /**
   * logout
   */
  public function logout(Request $request)
  {
    $request->session()->forget('linkedin_access_token');
    $request->session()->forget('linkedin_token_expires_in');
    
    return redirect('/');
  }
}