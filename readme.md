# Lumen with JWT Authentication
Basically this is a starter kit for you to integrate Lumen with [JWT Authentication](https://jwt.io/).

## What's Added

- [Lumen 5.4](https://github.com/laravel/lumen/tree/v5.4.0).
- [JWT Auth](https://github.com/tymondesigns/jwt-auth) for Lumen Application. <sup>[1]</sup>
- [CORS and Preflight Request](https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS) support.

## Quick Start

- Clone this repo or download it's release archive and extract it somewhere
- You may delete `.git` folder if you get this code via `git clone`
- Run `composer install`
- Run `php artisan jwt:secret`
- Configure your `.env` file for authenticating via database

## Examples

To authenticate a user, make a `POST` request to `/api/v1/auth` with parameter as mentioned below:

```
email: edward@example.com
password: edward123
```

Response:

```
{
  "success": {
    "message": "token_generated",
    "token": "a_long_token_appears_here"
  }
}
```

```
⇒  php artisan route:list
+--------+----------------------+---------------------+------------------------------------------+------------------+------------+
| Verb   | Path                 | NamedRoute          | Controller                               | Action           | Middleware |
+--------+----------------------+---------------------+------------------------------------------+------------------+------------+
| POST   | /api/v1/auth/login      | api.auth.login      | App\Http\Controllers\Auth\AuthController | postLogin        |            |
| GET    | /api                 | api.index           | App\Http\Controllers\APIController       | getIndex         | jwt.auth   |
| GET    | /api/auth/user       | api.auth.user       | App\Http\Controllers\Auth\AuthController | getUser          | jwt.auth   |
| PATCH  | /api/auth/refresh    | api.auth.refresh    | App\Http\Controllers\Auth\AuthController | patchRefresh     | jwt.auth   |
| DELETE | /api/auth/invalidate | api.auth.invalidate | App\Http\Controllers\Auth\AuthController | deleteInvalidate | jwt.auth   |
+--------+----------------------+---------------------+------------------------------------------+------------------+------------+
```

## License

```
Laravel and Lumen is a trademark of Taylor Otwell
Sean Tymon officially holds "Laravel JWT" license
```
