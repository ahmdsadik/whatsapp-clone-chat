<?php

use App\Http\Middleware\ForceAcceptJsonApiMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(ForceAcceptJsonApiMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
//        $exceptions->renderable(function (ValidationException $exception) {
//            // TODO:: See if this is good or make Api Response Static
//            $instance = new class {
//                use \App\Traits\ApiResponse;
//            };
//            return $instance->validationErrorResponse($exception->validator->errors()->all());
//        });



        $exceptions->renderable(function (AuthenticationException|NotFoundHttpException $exception) {
            $instance = new class {
                use \App\Traits\ApiResponse;
            };
            return $instance->errorResponse($exception->getMessage(), 401);
        });
    })->create();
