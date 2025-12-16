<?php

namespace yoanbmps\batchChecker\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use yoanbmps\batchChecker\Models\Batch;
use yoanbmps\batchChecker\Models\Website;
use yoanbmps\batchChecker\Services\BatchRepository;
use yoanbmps\batchChecker\Services\WebsiteChecker;
use yoanbmps\batchChecker\Services\Logger;
use Slim\Views\PhpRenderer;

class BatchController
{
    private BatchRepository $repository;
    private WebsiteChecker $checker;
    private PhpRenderer $view;
    private \Psr\Log\LoggerInterface $logger;

    public function __construct()
    {
        $this->repository = new BatchRepository();
        $this->logger = Logger::getInstance();
        $this->checker = new WebsiteChecker($this->logger);
        $this->view = new PhpRenderer(__DIR__ . '/../Views');
    }

    // List all batches (homepage)
    public function listBatches(Request $request, Response $response, array $args): Response
    {
        $batches = $this->repository->findAll();
        return $this->view->render($response, 'batches.php', ['batches' => $batches]);
    }

    // Show form to create new batch
    public function showCreateForm(Request $request, Response $response, array $args): Response
    {
        return $this->view->render($response, 'createBatch.php');
    }

    // Create new batch
    public function createBatch(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $name = filter_var($data['name'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $type = filter_var($data['type'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (empty($name) || empty($type)) {
            $response->getBody()->write('Name and type are required');
            return $response->withStatus(400);
        }

        $batch = new Batch($this->repository->getNextId(), $name, $type);
        $this->repository->save($batch);

        $this->logger->info("Batch created", ['id' => $batch->id, 'name' => $batch->name]);

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    // View batch details with websites
    public function viewBatch(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['id'];
        $batch = $this->repository->findById($id);

        if (!$batch) {
            $response->getBody()->write('Batch not found');
            return $response->withStatus(404);
        }

        return $this->view->render($response, 'batchDetail.php', ['batch' => $batch]);
    }

    // Delete batch
    public function deleteBatch(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['id'];
        $batch = $this->repository->findById($id);

        if ($batch) {
            $this->logger->info("Batch deleted", ['id' => $batch->id, 'name' => $batch->name]);
        }

        $this->repository->delete($id);
        return $response->withHeader('Location', '/')->withStatus(302);
    }

    // Show form to add website to batch
    public function showAddWebsiteForm(Request $request, Response $response, array $args): Response
    {
        $batchId = (int) $args['id'];
        $batch = $this->repository->findById($batchId);

        if (!$batch) {
            $response->getBody()->write('Batch not found');
            return $response->withStatus(404);
        }

        return $this->view->render($response, 'addWebsite.php', ['batch' => $batch]);
    }

    // Add website to batch
    public function addWebsite(Request $request, Response $response, array $args): Response
    {
        $batchId = (int) $args['id'];
        $batch = $this->repository->findById($batchId);

        if (!$batch) {
            $response->getBody()->write('Batch not found');
            return $response->withStatus(404);
        }

        $data = $request->getParsedBody();
        $url = filter_var($data['url'] ?? '', FILTER_SANITIZE_URL);

        if (empty($url)) {
            $response->getBody()->write('URL is required');
            return $response->withStatus(400);
        }

        $websiteId = $this->repository->getNextWebsiteId($batchId);
        $website = new Website($websiteId, $url);
        $batch->addWebsite($website);
        $this->repository->save($batch);

        $this->logger->info("Website added to batch", [
            'batch_id' => $batchId,
            'website_id' => $websiteId,
            'url' => $url
        ]);

        return $response->withHeader('Location', "/batch/{$batchId}")->withStatus(302);
    }

    // Show form to edit website
    public function showEditWebsiteForm(Request $request, Response $response, array $args): Response
    {
        $batchId = (int) $args['id'];
        $websiteId = (int) $args['websiteId'];
        $batch = $this->repository->findById($batchId);

        if (!$batch) {
            $response->getBody()->write('Batch not found');
            return $response->withStatus(404);
        }

        $website = $batch->getWebsite($websiteId);
        if (!$website) {
            $response->getBody()->write('Website not found');
            return $response->withStatus(404);
        }

        return $this->view->render($response, 'editWebsite.php', [
            'batch' => $batch,
            'website' => $website
        ]);
    }

    // Update website
    public function updateWebsite(Request $request, Response $response, array $args): Response
    {
        $batchId = (int) $args['id'];
        $websiteId = (int) $args['websiteId'];
        $batch = $this->repository->findById($batchId);

        if (!$batch) {
            $response->getBody()->write('Batch not found');
            return $response->withStatus(404);
        }

        $website = $batch->getWebsite($websiteId);
        if (!$website) {
            $response->getBody()->write('Website not found');
            return $response->withStatus(404);
        }

        $data = $request->getParsedBody();
        $url = filter_var($data['url'] ?? '', FILTER_SANITIZE_URL);

        if (empty($url)) {
            $response->getBody()->write('URL is required');
            return $response->withStatus(400);
        }

        $website->url = $url;
        $this->repository->save($batch);

        $this->logger->info("Website updated", [
            'batch_id' => $batchId,
            'website_id' => $websiteId,
            'url' => $url
        ]);

        return $response->withHeader('Location', "/batch/{$batchId}")->withStatus(302);
    }

    // Delete website from batch
    public function deleteWebsite(Request $request, Response $response, array $args): Response
    {
        $batchId = (int) $args['id'];
        $websiteId = (int) $args['websiteId'];
        $batch = $this->repository->findById($batchId);

        if (!$batch) {
            $response->getBody()->write('Batch not found');
            return $response->withStatus(404);
        }

        $website = $batch->getWebsite($websiteId);
        if ($website) {
            $this->logger->info("Website deleted from batch", [
                'batch_id' => $batchId,
                'website_id' => $websiteId,
                'url' => $website->url
            ]);
        }

        $batch->removeWebsite($websiteId);
        $this->repository->save($batch);

        return $response->withHeader('Location', "/batch/{$batchId}")->withStatus(302);
    }

    // Check all websites in batch
    public function checkAllWebsites(Request $request, Response $response, array $args): Response
    {
        $batchId = (int) $args['id'];
        $batch = $this->repository->findById($batchId);

        if (!$batch) {
            $response->getBody()->write('Batch not found');
            return $response->withStatus(404);
        }

        $websites = $batch->getAllWebsites();
        $this->checker->checkMultipleWebsites($websites);
        $this->repository->save($batch);

        return $response->withHeader('Location', "/batch/{$batchId}")->withStatus(302);
    }
}
