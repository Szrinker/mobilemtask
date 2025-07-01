<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\Image;
use App\Form\AdvertisementType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdvertisementController extends AbstractController
{
    private FileUploader $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    #[Route('/', name: 'advertisement_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $advertisements = $em->getRepository(Advertisement::class)->findAll();

        return $this->render('advertisement/index.html.twig', [
            'advertisements' => $advertisements,
        ]);
    }

    #[Route('/advertisements/new', name: 'advertisement_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $advertisement = new Advertisement();
        $form = $this->createForm(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $images = $form->get('images')->getData();
            $hasImage = false;
            foreach ($images as $imageFile) {
                if ($imageFile) {
                    $hasImage = true;
                    break;
                }
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile[] $images */
            $images = $form->get('images')->getData();

            foreach ($images as $imageFile) {
                if ($imageFile) {
                    try {
                        $newFilename = $this->fileUploader->upload($imageFile);
                    } catch (FileException $e) {
                        // handle exception if something happens during file upload
                    }

                    $image = new Image();
                    $image->setFilename($newFilename);
                    $advertisement->addImage($image);
                }
            }

            $em->persist($advertisement);
            $em->flush();

            return $this->redirectToRoute('advertisement_index');
        }

        return $this->render('advertisement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/advertisements/{id}', name: 'advertisement_show', requirements: ['id' => '\\d+'])]
    public function show(Advertisement $advertisement): Response
    {
        return $this->render('advertisement/show.html.twig', [
            'advertisement' => $advertisement,
        ]);
    }
}
