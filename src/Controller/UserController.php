<?php

namespace App\Controller;

use App\Form\User\UserProfileImageType;
use App\Form\User\UserProfileInfoType;
use App\Form\User\UserProfilePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_user_profile')]
    public function index(): Response
    {
        $profilePictureForm = $this->createForm(UserProfileImageType::class, null, [
            'action' => $this->generateUrl('app_user_profile_edit_image'),
        ]);
        $passwordForm = $this->createForm(UserProfilePasswordType::class, null, [
            'action' => $this->generateUrl('app_user_profile_edit_password'),
        ]);
        $infosForm = $this->createForm(UserProfileInfoType::class, $this->getUser(), [
            'action' => $this->generateUrl('app_user_profile_edit_infos'),
        ]);

        return $this->render('user_profile/show.html.twig', [
            'profilePictureForm' => $profilePictureForm->createView(),
            'passwordForm' => $passwordForm->createView(),
            'infosForm' => $infosForm->createView(),
        ]);
    }

    #[Route('/profile/edit/image', name: 'app_user_profile_edit_image', methods: ['POST'])]
    public function editImage(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $profilePictureForm = $this->createForm(UserProfileImageType::class);
        $profilePictureForm->handleRequest($request);

        if ($profilePictureForm->isSubmitted() && $profilePictureForm->isValid()) {
            $imageFile = $profilePictureForm['imageLink']->getData();

            if ($imageFile) {
                $randomHash = md5(random_bytes(10));
                $newFilename = $randomHash . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('image_upload_directory'),
                        $newFilename
                    );
                } catch (FileException) {
                    $this->addFlash('danger', 'Error while uploading image');
                    return $this->redirectToRoute('app_user_profile');
                }

                $user->setImageLink($newFilename);
                $em->flush();
                $this->addFlash('success', 'Profile picture updated');
                return $this->redirectToRoute('app_user_profile');
            }
        }

        $this->addFlash('info', 'Not implemented yet');
        return $this->redirectToRoute('app_user_profile');
    }

    #[Route('/profile/edit/password', name: 'app_user_profile_edit_password', methods: ['POST'])]
    public function editPassword(Request $request, EntityManagerInterface $em): Response
    {
        // TODO: implement password change
        $this->addFlash('info', 'Not implemented yet');
        return $this->redirectToRoute('app_user_profile');
    }

    #[Route('/profile/edit/infos', name: 'app_user_profile_edit_infos', methods: ['POST'])]
    public function editInfos(Request $request, EntityManagerInterface $em): Response
    {
        $infosForm = $this->createForm(UserProfileInfoType::class, $this->getUser());
        $infosForm->handleRequest($request);

        if ($infosForm->isSubmitted() && $infosForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Profile updated');
            return $this->redirectToRoute('app_user_profile');
        }
        $this->addFlash('danger', 'Invalid form');
        return $this->redirectToRoute('app_user_profile');
    }

    #[Route('/profile/delete', name: 'app_user_profile_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'Account deleted');
        return $this->redirectToRoute('app_homepage');
    }

}
