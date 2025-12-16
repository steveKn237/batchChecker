<?php

use Slim\Factory\AppFactory;
use yoanbmps\batchChecker\Controllers\BatchController;

require __DIR__ . '/../vendor/autoload.php';

// Create Slim app
$app = AppFactory::create();

// Create controller instance
$controller = new BatchController();

// Routes
// Homepage - list all batches
$app->get('/', [$controller, 'listBatches']);

// Batch routes
$app->get('/batch/create', [$controller, 'showCreateForm']);
$app->post('/batch/create', [$controller, 'createBatch']);
$app->get('/batch/{id}', [$controller, 'viewBatch']);
$app->get('/batch/{id}/delete', [$controller, 'deleteBatch']);

// Website routes
$app->get('/batch/{id}/website/add', [$controller, 'showAddWebsiteForm']);
$app->post('/batch/{id}/website/add', [$controller, 'addWebsite']);
$app->get('/batch/{id}/website/{websiteId}/edit', [$controller, 'showEditWebsiteForm']);
$app->post('/batch/{id}/website/{websiteId}/edit', [$controller, 'updateWebsite']);
$app->get('/batch/{id}/website/{websiteId}/delete', [$controller, 'deleteWebsite']);

// Check all websites in a batch
$app->get('/batch/{id}/check', [$controller, 'checkAllWebsites']);

// Add body parsing middleware
$app->addBodyParsingMiddleware();

// Add routing middleware
$app->addRoutingMiddleware();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Run app
$app->run();
