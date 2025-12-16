<?php

namespace yoanbmps\batchChecker\Services;

use yoanbmps\batchChecker\Models\Batch;
use yoanbmps\batchChecker\Models\Website;

class BatchRepository
{
    private string $dataFile;

    public function __construct(string $dataFile = __DIR__ . '/../../data/batches.json')
    {
        $this->dataFile = $dataFile;
        $this->ensureDataFileExists();
    }

    private function ensureDataFileExists(): void
    {
        if (!file_exists($this->dataFile)) {
            $dir = dirname($this->dataFile);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($this->dataFile, json_encode([]));
        }
    }

    private function readData(): array
    {
        $content = file_get_contents($this->dataFile);
        $data = json_decode($content, true);
        return is_array($data) ? $data : [];
    }

    private function writeData(array $data): void
    {
        file_put_contents($this->dataFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function findAll(): array
    {
        $data = $this->readData();
        $batches = [];
        foreach ($data as $batchData) {
            $batches[] = Batch::fromArray($batchData);
        }
        return $batches;
    }

    public function findById(int $id): ?Batch
    {
        $data = $this->readData();
        foreach ($data as $batchData) {
            if ($batchData['id'] === $id) {
                return Batch::fromArray($batchData);
            }
        }
        return null;
    }

    public function save(Batch $batch): void
    {
        $data = $this->readData();
        $found = false;

        foreach ($data as $key => $batchData) {
            if ($batchData['id'] === $batch->id) {
                $data[$key] = $batch->toArray();
                $found = true;
                break;
            }
        }

        if (!$found) {
            $data[] = $batch->toArray();
        }

        $this->writeData($data);
    }

    public function delete(int $id): bool
    {
        $data = $this->readData();
        $initialCount = count($data);

        $data = array_filter($data, fn($batch) => $batch['id'] !== $id);

        if (count($data) < $initialCount) {
            $this->writeData(array_values($data));
            return true;
        }

        return false;
    }

    public function getNextId(): int
    {
        $data = $this->readData();
        if (empty($data)) {
            return 1;
        }

        $maxId = 0;
        foreach ($data as $batch) {
            if ($batch['id'] > $maxId) {
                $maxId = $batch['id'];
            }
        }

        return $maxId + 1;
    }

    public function getNextWebsiteId(int $batchId): int
    {
        $batch = $this->findById($batchId);
        if (!$batch) {
            return 1;
        }

        $websites = $batch->getAllWebsites();
        if (empty($websites)) {
            return 1;
        }

        $maxId = 0;
        foreach ($websites as $website) {
            if ($website->id > $maxId) {
                $maxId = $website->id;
            }
        }

        return $maxId + 1;
    }
}
