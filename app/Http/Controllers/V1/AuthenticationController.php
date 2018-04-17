<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticationController extends Controller {

    /**
     * @var JWTAuth
     */
    private $auth;

    /**
     * @param JWTAuth $auth
     */
    public function __construct(JWTAuth $auth){
        $this->auth = $auth;
    }


    /**
     * @api {post} /authorizations (create a token)
     * @apiDescription create a token
     * @apiGroup Auth
     * @apiPermission none
     * @apiParam {Email} email
     * @apiParam {String} password
     * @apiVersion 0.2.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 201 Created
     *     {
     *         "data": {
     *             "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbHVtZW4tYXBpLWRlbW8uZGV1L2FwaS9hdXRob3JpemF0aW9ucyIsImlhdCI6MTQ4Mzk3NTY5MywiZXhwIjoxNDg5MTU5NjkzLCJuYmYiOjE0ODM5NzU2OTMsImp0aSI6ImViNzAwZDM1MGIxNzM5Y2E5ZjhhNDk4NGMzODcxMWZjIiwic3ViIjo1M30.hdny6T031vVmyWlmnd2aUr4IVM9rm2Wchxg5RX_SDpM",
     *             "expired_at": "2017-03-10 15:28:13",
     *             "refresh_expired_at": "2017-01-23 15:28:13"
     *         }
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401
     *     {
     *       "error": "User credential is not match"
     *     }
     */

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        try {
            // attempt to verify the credentials and create a token for the user
            $token = $this->auth->attempt($credentials);
            if (!$token) {
                return response()->json(['error' => 'invalid_credentials'], IlluminateResponse::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        // all good so return the token
        return response()->json(compact('token'));
    }

    /**
     * @api {post} /authorizations register new user
     * @apiDescription register new user
     * @apiGroup Auth
     * @apiPermission none
     * @apiParam {String} name
     * @apiParam {Email} email
     * @apiParam {String} password
     * @apiVersion 0.2.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 201 Created
     *     {
     *         "data": {
     *             "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbHVtZW4tYXBpLWRlbW8uZGV1L2FwaS9hdXRob3JpemF0aW9ucyIsImlhdCI6MTQ4Mzk3NTY5MywiZXhwIjoxNDg5MTU5NjkzLCJuYmYiOjE0ODM5NzU2OTMsImp0aSI6ImViNzAwZDM1MGIxNzM5Y2E5ZjhhNDk4NGMzODcxMWZjIiwic3ViIjo1M30.hdny6T031vVmyWlmnd2aUr4IVM9rm2Wchxg5RX_SDpM",
     *             "expired_at": "2017-03-10 15:28:13",
     *             "refresh_expired_at": "2017-01-23 15:28:13"
     *         }
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401
     *     {
     *       "error": "Register failed"
     *     }
     */
    public function register(Request $request)
    {
        // \Log::info(json_encode($request->all()));
        // \Log::info($request);

        $validator = Validator::make($request->input(), [
            'email' => 'required|email|unique:users',
            'name' => 'required|string',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator],
                IlluminateResponse::HTTP_BAD_REQUEST);
        }

        $password = $request->get('password');

        $attributes = [
            'email' => $request->get('email'),
            'name' => $request->get('name'),
            'password' => app('hash')->make($password),
            'active' => 1 // for test, lets activate automatically
        ];

        $user = User::create($attributes);

        $credentials = $request->only('email', 'password');

        // Validation failed will return 401
        if (! $token = $this->auth->attempt($credentials)) {
            return response()->json(['errors' => "auth.incorrect"],
                IlluminateResponse::HTTP_BAD_REQUEST);
        }

        // Send the email after the user has successfully registered
        //dispatch(new SendRegisterEmail($user));

        $result['data'] = [
            'user' => $user,
            'token' => $token,
            'expired_at' => Carbon::now(env('APP_TIMEZONE'))->addMinutes(config('jwt.ttl'))->toDateTimeString(),
            'refresh_expired_at' => Carbon::now(env('APP_TIMEZONE'))->addMinutes(config('jwt.refresh_ttl'))->toDateTimeString(),
        ];

        return response()->json($result, IlluminateResponse::HTTP_CREATED);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sayHello(){
        echo "You have arrived!";
    }
}