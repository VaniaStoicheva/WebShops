<?php

namespace BookShopBundle\Controller;

use BookShopBundle\Entity\User;
use BookShopBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login",name="user_login")
     * @Template()
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {

        $uat_utils=$this->get('security.authentication_utils');
        $error=$uat_utils->getLastAuthenticationError();
        $last_username=$uat_utils->getLastUsername();
        return [
            'error'=>$error,
            'last_username'=>$last_username
        ];
    }

    /**
     * @Route("/login_check", name="user_check")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function checkAction(Request $request)
    {
        return $this->redirectToRoute('user_login');
    }

    /**
     * @Route("/register",name="user_register")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function registerAction(Request $request)
    {

        $user=new User();
        $form=$this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()){
            $user->setRoles([ $user->getDefaultRole() ]);
            $encripted=$this->get('security.password_encoder');
            $user->setPassword(
                $encripted->encodePassword($user,$user->getPasswordRaw())
            );

            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }
        return [
            'form'=>$form->createView()
        ];
    }

    /**
     * @Route("/logout", name="user_logout")
     */
    public function logoutAction()
    {
    }
}
