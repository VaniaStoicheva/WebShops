<?php

namespace BookShopBundle\Controller;

use BookShopBundle\Entity\Cart;
use BookShopBundle\Entity\CartProduct;
use BookShopBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * @Route("/cart/add",name="card_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
       $em=$this->getDoctrine()->getManager();
       $session=$this->get('session');
       $cart_id=$session->get('cart_id',false);

     $user=$this->getUser();

     /** @var User  $user */
     $user_id=$user->getId();


       if(!$cart_id){

           $cart=new Cart();
           $cart->setUserId($user_id);
           $cart->setDateCreated(new \DateTime());
           $cart->setDateUpdated(new \DateTime());

           $em->persist($cart);
           $em->flush();

           $session->set('cart_id',$cart->getId());
       }

       $cart=$this->getDoctrine()->getRepository('BookShopBundle:Cart')
           ->find($session->get('cart_id',false));
       if(!$cart){
           $this->addFlash('error',"No cart in session!");
       }
       //validation id->integet id E na products

        $products=$request->get('products');

       foreach ($products as $product_id){
           $product=$this->getDoctrine()->getRepository('BookShopBundle:Product')
               ->find($product_id);

           if($product){
               $cart_product=$this->getDoctrine()->getRepository('BookShopBundle:CartProduct')->findOneBy([
                   'cart'=>$cart,
                   'product'=>$product
               ]);

               if(!$cart_product){
                   $cart_product=new CartProduct();
                   $cart_product->setCart($cart);
                   $cart_product->setProduct($product);
                   $cart_product->setQuantity($product->getQuantity());
               }else{
                   $cart_product->setQuantity($product->getQuantity()+$product->getQuantity());
               }

               $em->persist($cart_product);
           }
       }
       $cart->setDateUpdated(new \DateTime());
        $em->persist($cart);
        $em->flush();

        return $this->redirectToRoute('cart_list');

    }

    /**
     * @Route("/cart/list",name="cart_list")
     * @return Response
     */
    public function listAction()
    {

        return new JsonResponse([]);
    }
}
