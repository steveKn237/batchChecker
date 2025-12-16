<?php

namespace yoanbmps\batchChecker\Models;

class Website
{
    public int $id;
    public string $url;
    public string $status;
    public ?string $lastChecked;

    public function __construct(int $id, string $url, string $status = WebsiteStatus::DOWN, ?string $lastChecked = null)
    {
        $this->id = $id;
        $this->url = $url;
        $this->status = $status;
        $this->lastChecked = $lastChecked;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'status' => $this->status,
            'lastChecked' => $this->lastChecked,
        ];
    }

    public static function fromArray(array $data): Website
    {
        return new Website(
            (int) ($data['id'] ?? 0),
            (string) ($data['url'] ?? ''),
            $data['status'] ?? WebsiteStatus::DOWN,
            $data['lastChecked'] ?? null
        );
    }
}
