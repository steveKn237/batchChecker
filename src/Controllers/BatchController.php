<?php

namespace yoanbmps\batchChecker\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use yoanbmps\batchChecker\Models\BatchModel;
use Slim\Views\PhpRenderer;
    
class BatchController
{

    public BatchModel $model;
    private PhpRenderer $phpView;

    public function __construct()
    {
        $this->model = new BatchModel();
        $dataLayout = ['title' => 'Galerie Photo'];
        $this->phpView = new PhpRenderer('../views', $dataLayout);
        $this->phpView->setLayout("layout.php");
    }

    public function show(Request $request, Response $response, array $args)
    {
        $dataLayout = ['title' => 'Galerie Photo | Galerie'];
        $phpView = new PhpRenderer('../views', $dataLayout);
        $phpView->setLayout("layout.php");
        $galleries = $this->model->ReadAllGalleries();
        $galleriesWithCounts = [];
        foreach ($galleries as $gallery) {
            $gallery->imageCount = $this->model->countImagesInGallery($gallery->idGalerie);
            $galleriesWithCounts[] = $gallery;
        }
        $dataDetail = ['content' => 'Bonjour', 'galleries' => $galleriesWithCounts];
        return $phpView->render($response, 'gallery/galleries.php', $dataDetail);
    }

    public function showGallery(Request $request, Response $response, array $args)
    {
        $id = $args["id"];
        $dataLayout = ['title' => 'Galerie Photo | Galerie'];
        $phpView = new PhpRenderer('../views', $dataLayout);
        $phpView->setLayout("layout.php");

        $gallery = $this->model->ReadOneGallery($id);
        $images = $this->model->ReadAllImages($id);

        $dataDetail = [
            'content' => 'Bonjour',
            'gallery' => $gallery,
            'images' => $images
        ];

        return $phpView->render($response, 'gallery/gallery.php', $dataDetail);
    }

    public function showPhoto(Request $request, Response $response, array $args)
    {
        $id = $args["id"];
        $photo = $this->model->ReadOneImage($id);
        $prevPhoto = $this->model->ReadPrevImage($id, $photo->idGalerie);
        $nextPhoto = $this->model->ReadNextImage($id, $photo->idGalerie);

        $dataDetail = [
            'photo' => $photo,
            'prevPhoto' => $prevPhoto,
            'nextPhoto' => $nextPhoto,
        ];

        return $this->phpView->render($response, 'gallery/photo.php', $dataDetail);
    }

    public function showAddGalleryForm(Request $request, Response $response, array $args)
    {
        return $this->phpView->render($response, 'form/addGallery.php');
    }

    public function showAddImageForm(Request $request, Response $response, array $args)
    {
        $idGalerie = $args['id'];
        $dataDetail = ['idGalerie' => $idGalerie];
        return $this->phpView->render($response, 'form/addImage.php', $dataDetail);
    }

    public function addGallery(Request $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();
        $image = $uploadedFiles['image'];
        $nom = filter_var($data['nom'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($image->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile('../public/img', $image);
            $cheminImage = '/img/' . $filename;
            $this->model->InsertOneGallery(null, $nom, $cheminImage);
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        $response->getBody()->write('Erreur lors de l\'upload de l\'image.');
        return $response->withStatus(500);
    }

    private function moveUploadedFile($directory, $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    public function deleteGallery(Request $request, Response $response, array $args)
    {
        $id = $args["id"];

        $this->model->DeleteOneGallery($id);

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function addImage(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();
        $image = $uploadedFiles['image'];
        $nom = filter_var($data['nom'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $idGalerie = filter_var($data['idGalerie'], FILTER_VALIDATE_INT);
    
        if ($image->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile('../public/img', $image);
            $originalPath = '/img/' . $filename;
            $this->model->InsertOneImage(null, $nom, $originalPath, "", "", $idGalerie, 0);
            return $response->withHeader('Location', "/gallery/$idGalerie")->withStatus(302);
        }
    
        $response->getBody()->write('Erreur lors de l\'upload de l\'image.');
        return $response->withStatus(500);
    }
    

    public function deleteImage(Request $request, Response $response, array $args)
    {
        $id = $args["id"];

        $this->model->DeleteOneImage($id);

        return $response->withHeader('Location', '/')->withStatus(302);
    }
}


?>