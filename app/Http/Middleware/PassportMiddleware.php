<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Uri;
use Symfony\Component\HttpFoundation\Response;

class PassportMiddleware
{
    /**
     * @param  Closure(Request): (Response)  $next
     */

    public mixed $redirectUri;

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->path() == 'oauth/authorize' && $response->headers->get('content-type') == 'application/json') {
            $statusCode = $response->getStatusCode();
            $content = json_decode($response->getContent());

            if ($statusCode == 400 || $statusCode == 401) {
                if ($request->query('redirect_uri') && Str::isUrl($request->query('redirect_uri'))) {
                    $url = Uri::of($request->query('redirect_uri'));
                    $this->redirectUri = Str::of($url->scheme())->append('://')->append($url->host());
                } elseif ($request->query('client_id') && Str::isUuid($request->query('client_id'))) {
                    $oAuthClient = DB::table('oauth_clients')->where('id', $request->query('client_id'))->first();

                    if ($oAuthClient) {
                        $url = Uri::of(json_decode($oAuthClient->redirect_uris)[0]);
                        $this->redirectUri = Str::of($url->scheme())->append('://')->append($url->host());
                    }
                } else {
                    $this->redirectUri = route('home');
                }

                return response()->view('oauth.error', [
                    'message' => $content->error_description,
                    'redirect_uri' => $this->redirectUri
                ]);
            }
        }

        return $response;
    }
}
