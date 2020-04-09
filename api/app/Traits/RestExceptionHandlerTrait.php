<?php

namespace App\Traits;

use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * Trait RestExceptionHandlerTrait
 * @package App\Traits
 */
trait RestExceptionHandlerTrait
{
    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse
     */
    protected function getJsonResponseForException(
        Request $request,
        Throwable $e
    ): JsonResponse {
        $isProduction = getenv('APP_ENV') === 'production';

        switch (true) {
            case $this->isValidationException($e):
                $result = $this->validationErrors($e->validator->errors());
                break;

            case $this->isModelNotFoundException($e):
                $result = $this->modelNotFound($e->getMessage());
                break;
            default:
                if (!$isProduction) {
                    $message = $e->getMessage();
                    $result = $this->badRequest($message);
                } else {
                    $result = $this->badRequest();
                }
        }

        return $result;
    }

    /**
     * Returns json response for generic bad request.
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function badRequest(
        $message = 'Bad request',
        $statusCode = Response::HTTP_BAD_REQUEST
    ): JsonResponse {
        return $this->jsonResponse(['message' => $message], $statusCode);
    }

    /**
     * Returns json response for Eloquent model not found exception.
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function modelNotFound(
        $message = 'Record not found',
        $statusCode = Response::HTTP_NOT_FOUND
    ): JsonResponse {
        return $this->jsonResponse(['message' => $message], $statusCode);
    }

    /**
     * Validation Errors
     *
     * @param MessageBag $messageBag
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function validationErrors(
        MessageBag $messageBag,
        $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY
    ): JsonResponse {
        $errors = [];
        foreach ($messageBag->getMessages() as $field => $messages) {
            $errors[] = [
                'field' => $field,
                'message' => join($messages, ' ')
            ];
        }
        return $this->jsonResponse(['errors' => $errors], $statusCode);
    }

    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function jsonResponse(
        array $payload = null,
        $statusCode = Response::HTTP_NOT_FOUND
    ): JsonResponse {
        $response = $payload ?: [];
        $response['success'] = false;

        return response()->json($response, $statusCode);
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Throwable $e
     * @return bool
     */
    protected function isModelNotFoundException(Throwable $e)
    {
        return $e instanceof ModelNotFoundException;
    }

    /**
     * Determines if the given exception is a Validation exception
     *
     * @param Throwable $e
     * @return bool
     */
    protected function isValidationException(Throwable $e): bool
    {
        return $e instanceof ValidationException;
    }
}
