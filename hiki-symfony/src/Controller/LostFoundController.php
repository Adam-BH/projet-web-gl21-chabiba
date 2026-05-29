<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/lost-and-found', name: 'app_lost_found')]
class LostFoundController extends AbstractController
{
    #[Route('', name: '')]
    public function index(PostRepository $posts): Response
    {
        return $this->render('lost_found/index.html.twig', [
            'posts' => $posts->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/new', name: '_new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $error = null;

        if ($request->isMethod('POST')) {
            $item  = trim($request->request->get('item', ''));
            $place = trim($request->request->get('place', ''));

            if ($item === '') {
                $error = 'Item description is required.';
            } else {
                /** @var \App\Entity\User $user */
                $user = $this->getUser();

                $post = new Post();
                $post->setFinder($user)
                     ->setItem($item)
                     ->setPlace($place ?: null)
                     ->setPhone($user->getPhone());

                $file = $request->files->get('image');
                if ($file !== null) {
                    $mime    = $file->getMimeType();
                    $allowed = ['image/jpeg', 'image/png', 'image/webp'];

                    if (!in_array($mime, $allowed, true)) {
                        $error = 'Only JPG, PNG and WebP images are allowed.';
                    } elseif ($file->getSize() > 5 * 1024 * 1024) {
                        $error = 'Image must be 5 MB or smaller.';
                    } else {
                        $ext      = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime];
                        $filename = date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                        $file->move($this->getParameter('kernel.project_dir') . '/public/uploads/posts', $filename);
                        $post->setPicture('uploads/posts/' . $filename);
                    }
                }

                if ($error === null) {
                    $em->persist($post);
                    $em->flush();
                    return $this->redirectToRoute('app_lost_found');
                }
            }
        }

        return $this->render('lost_found/new.html.twig', ['error' => $error]);
    }

    #[Route('/delete/{id}', name: '_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Post $post, Request $request, EntityManagerInterface $em, CsrfTokenManagerInterface $csrf): Response
    {
        if (!$csrf->isTokenValid(new CsrfToken('delete-post-' . $post->getId(), $request->request->get('_token', '')))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if ($post->getFinder()->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException();
        }

        if ($post->getPicture()) {
            $path = $this->getParameter('kernel.project_dir') . '/public/' . $post->getPicture();
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('app_lost_found');
    }
}
