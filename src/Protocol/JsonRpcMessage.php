<?php
declare(strict_types=1);

namespace PriyankJivani\McpServer\Protocol;

class JsonRpcMessage
{
    public const VERSION = '2.0';

    public static function createResponse(int|string|null $id, array $result): array
    {
        return [
            'jsonrpc' => self::VERSION,
            'id' => $id,
            'result' => $result
        ];
    }

    public static function createError(int|string|null $id, int $code, string $message, mixed $data = null): array
    {
        $payload = [
            'jsonrpc' => self::VERSION,
            'id' => $id,
            'error' => [
                'code' => $code,
                'message' => $message
            ]
        ];
        if ($data !== null) {
            $payload['error']['data'] = $data;
        }
        return $payload;
    }
}
