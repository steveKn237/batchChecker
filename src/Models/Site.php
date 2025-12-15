<?php

namespace yoanbmps\batchChecker\Models;


use Slim\Views\PhpRenderer;

class Site
{
    public int $id;
    public string $url;
    public string $status;
    public string $lastChecked;

    public function __construct(int $id, string $url, string $status = siteStatus::DOWN, string $lastChecked)
    {
        //Constructeur faux pour l'instant
        $this->id = $id;
        $this->url = $url;
        $this->status = $status;
        $this->lastChecked = $lastChecked;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'status' => $this->status,
            'lastChecked' => $this->lastChecked,
        ];
    }
    public static function fromArray(array $data): Site
    {
        return new Site(
            (int) ($data['id'] ?? 0),
            (string) ($data['url'] ?? ''),
            $data['status'] ?? siteStatus::DOWN,
            $data['lastchecked'] ?? null
        );
    }
}