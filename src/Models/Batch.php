<?php

namespace yoanbmps\batchChecker\Models;

class Batch
{
    public int $id;
    public string $name;
    public string $type;
    public string $createdAt;
    public array $websites = [];

    public function __construct(int $id, string $name, string $type, ?string $createdAt = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s');
    }

    public function addWebsite(Website $website): void
    {
        $this->websites[] = $website;
    }

    public function getWebsite(int $id): ?Website
    {
        foreach ($this->websites as $website) {
            if ($website->id === $id) {
                return $website;
            }
        }
        return null;
    }

    public function getAllWebsites(): array
    {
        return $this->websites;
    }

    public function removeWebsite(int $id): bool
    {
        foreach ($this->websites as $key => $website) {
            if ($website->id === $id) {
                unset($this->websites[$key]);
                $this->websites = array_values($this->websites);
                return true;
            }
        }
        return false;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'createdAt' => $this->createdAt,
            'websites' => array_map(fn($w) => $w->toArray(), $this->websites)
        ];
    }

    public static function fromArray(array $data): Batch
    {
        $batch = new Batch(
            (int) ($data['id'] ?? 0),
            (string) ($data['name'] ?? ''),
            (string) ($data['type'] ?? ''),
            $data['createdAt'] ?? null
        );
        
        if (isset($data['websites']) && is_array($data['websites'])) {
            foreach ($data['websites'] as $websiteData) {
                $batch->addWebsite(Website::fromArray($websiteData));
            }
        }
        
        return $batch;
    }
}
