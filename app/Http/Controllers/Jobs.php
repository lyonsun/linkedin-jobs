<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Jobs extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $linkedin_access_token = $request->session()->get('linkedin_access_token');
        
        $response = Http::withToken($linkedin_access_token)->get('https://api.linkedin.com/v2/jobs');
        $linkedin_jobs_response = $response->json();

        $status = $linkedin_jobs_response['status'];

        if ($status != 200)
        {
            $data = [
                'status' => $status,
                'message' => $linkedin_jobs_response['message'],
            ];

            return view('jobs', $data);
        }

    }
}
