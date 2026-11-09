<?php
declare(strict_types=1);

namespace PriyankJivani\McpServer\Config;

class ServerConfig
{
    private string $baseUrl;
    private string $accessToken;
    private bool $readOnly;
    private int $timeout;
    private string $storeCode;

    public function __construct(
        ?string $baseUrl = null,
        ?string $accessToken = null,
        ?bool $readOnly = null,
        ?int $timeout = null,
        ?string $storeCode = null
    ) {
        $this->baseUrl = rtrim($baseUrl ?? getenv('MAGENTO_BASE_URL') ?: 'http://127.0.0.1', '/');
        $this->accessToken = $accessToken ?? getenv('MAGENTO_ACCESS_TOKEN') ?: '';
        $this->readOnly = $readOnly ?? filter_var(getenv('MAGENTO_READ_ONLY') ?: 'true', FILTER_VALIDATE_BOOLEAN);
        $this->timeout = $timeout ?? (int)(getenv('MAGENTO_TIMEOUT') ?: 30);
        $this->storeCode = $storeCode ?? getenv('MAGENTO_STORE_CODE') ?: 'default';
    }

    public function getBaseUrl(): string { return $this->baseUrl; }
    public function getAccessToken(): string { return $this->accessToken; }
    public function isReadOnly(): bool { return $this->readOnly; }
    public function getTimeout(): int { return $this->timeout; }
    public function getStoreCode(): string { return $this->storeCode; }
}
