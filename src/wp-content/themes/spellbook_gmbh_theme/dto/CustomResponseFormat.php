<?php
namespace SpellbookGmbhTheme\Dto;

use SpellbookGmbhTheme\Helpers\Utils;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Class defining a response object returned by a custom http request.
 * 
 * @since 0.0.1
 */
class CustomResponseFormat {

    public int $status;

    public string $timestamp;

    public string $message;

    public string $path;


    public function __construct(int $status, string $message, string $path) {
        $this->status = $status;
        $this->timestamp = Utils::getTimeStamp();
        $this->message = $message;
        $this->path = $path;
    }

    /**
     * Use this for rest endpoints.
     */
    public static function asRestResponse(int $status, string $message, WP_REST_Request $request): WP_REST_Response {
        return new WP_REST_Response(
            new CustomResponseFormat($status, $message, CustomResponseFormat::getRestRequestPath($request)),
            $status);
    }

    private static function getRestRequestPath(WP_REST_Request $request): string {
        return "/wp-json" . $request->get_route();
    }

    public function getStatus(): int {
        return $this->status;
    }

    public function setStatus(int $status): void {
        $this->status = $status;
    }
}