<?php

namespace yoanbmps\batchChecker\Services;

use yoanbmps\batchChecker\Models\Website;
use yoanbmps\batchChecker\Models\WebsiteStatus;
use Psr\Log\LoggerInterface;

class WebsiteChecker
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function checkWebsite(Website $website): void
    {
        try {
            // Validate URL
            if (!filter_var($website->url, FILTER_VALIDATE_URL)) {
                $this->logger->error("Invalid URL", ['url' => $website->url]);
                $website->status = WebsiteStatus::DOWN;
                $website->lastChecked = date('Y-m-d H:i:s');
                return;
            }

            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $website->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request
            curl_setopt($ch, CURLOPT_HEADER, true);

            // Execute request
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            // Check for cURL errors
            if ($response === false || !empty($error)) {
                $this->logger->error("Network error checking website", [
                    'url' => $website->url,
                    'error' => $error
                ]);
                $website->status = WebsiteStatus::DOWN;
                $website->lastChecked = date('Y-m-d H:i:s');
                return;
            }

            // Check HTTP status code
            if ($httpCode >= 200 && $httpCode <= 399) {
                $website->status = WebsiteStatus::UP;
                $this->logger->info("Website is UP", [
                    'url' => $website->url,
                    'http_code' => $httpCode
                ]);
            } else {
                $website->status = WebsiteStatus::DOWN;
                $this->logger->warning("Website is DOWN", [
                    'url' => $website->url,
                    'http_code' => $httpCode
                ]);
            }

            $website->lastChecked = date('Y-m-d H:i:s');

        } catch (\Exception $e) {
            $this->logger->error("Exception checking website", [
                'url' => $website->url,
                'exception' => $e->getMessage()
            ]);
            $website->status = WebsiteStatus::DOWN;
            $website->lastChecked = date('Y-m-d H:i:s');
        }
    }

    public function checkMultipleWebsites(array $websites): void
    {
        foreach ($websites as $website) {
            $this->checkWebsite($website);
        }
    }
}
