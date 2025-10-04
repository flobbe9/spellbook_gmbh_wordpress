<?php


/**
 * Class defining a response object returned by a custom http request.
 * 
 * @since 0.0.1
 */
class CustomResponseFormat {

    private int $status;

    private string | null $error;

    private string $message;

    private string $path;


    public function __construct(int $status, string | null $error, string $message, string $path) {

        $this->status = $status;
        $this->error = $error;
        $this->message = $message;
        $this->path = $path;
    }


    public static function getArrayInstance(int $status, string | null $error, string $message, string $path): array {

        return [
            "status"=> $status,
            "error"=> $error,
            "message"=> $message,
            "path"=> $path
        ];
    }


    /**
     * Use this for rest endpoints.
     */
    public static function asRestResponse(int $status, string | null $error, string $message, string $path): WP_REST_Response {

        return new WP_REST_Response(
                    CustomResponseFormat::getArrayInstance($status, $error, $message, $path),
                    $status);
    }


    public function getStatus(): int {

        return $this->status;
    }


    public function setStatus(int $status): void {

        $this->status = $status;
    }
}