<?php
declare(strict_types=1);

namespace PriyankJivani\McpServer\Client;

use PriyankJivani\McpServer\Config\ServerConfig;

class MagentoApiClient
{
    private ServerConfig $config;

    public function __construct(ServerConfig $config)
    {
        $this->config = $config;
    }

    public function execute(string $action, array $params = [], bool $dryRun = false, bool $confirm = false): array
    {
        $url = $this->config->getBaseUrl() . '/rest/' . $this->config->getStoreCode() . '/V1/mcp/dispatch';
        $payload = [
            'request' => [
                'action' => $action,
                'params' => json_encode($params),
                'dry_run' => $dryRun,
                'confirm' => $confirm
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->config->getTimeout());
        
        // Support self-signed or direct IP HTTPS connections
        $sslVerify = filter_var(getenv('MAGENTO_SSL_VERIFY') ?: 'false', FILTER_VALIDATE_BOOLEAN);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $sslVerify);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $sslVerify ? 2 : 0);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->config->getAccessToken(),
            'X-MCP-Bridge-Token: ' . $this->config->getAccessToken()
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);

        if ($err) {
            throw new \RuntimeException("Magento API connection failed: $err");
        }

        $decoded = json_decode((string)$response, true);
        if ($httpCode >= 400) {
            $msg = $decoded['message'] ?? "HTTP $httpCode error from Magento bridge";
            throw new \RuntimeException("Magento Bridge Error ($httpCode): $msg");
        }

        if (is_array($decoded) && isset($decoded['result']) && is_string($decoded['result'])) {
            $parsed = json_decode($decoded['result'], true);
            if ($parsed !== null) {
                $decoded['result'] = $parsed;
            }
        }

        return is_array($decoded) ? $decoded : ['result' => $response];
    }
}
