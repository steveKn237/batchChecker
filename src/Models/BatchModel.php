<?php

namespace yoanbmps\batchChecker\Models;


use Slim\Views\PhpRenderer;

class BatchModel
{
    public int $id;
    public string $name;
    public string $type;
    public string $creeLe;

    public array $sites = [];

    public function __construct(int $id, string $name, string $type, string $creeLe = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->creeLe = date('Y-m-d H:i:s');
    }
    public function addSite(Site $site): void
    {
        $this->sites[] = $site;
    }

    public function getSites(int $id): Site
    {
        foreach ($this->sites as $site) {
            if ($site->id = $id) {
                return $site;
            }
        }
        return null ;
    }

    public function getAllSites(): array
    {
        return $this->sites;
    }
}