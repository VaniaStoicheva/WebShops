<?php

namespace BookShopBundle\Controller;

use BookShopBundle\Entity\Promotion;
use BookShopBundle\Service\PriceCalculator;
use BookShopBundle\Entity\Category;
use BookShopBundle\Entity\Product;
use BookShopBundle\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();


        $products = $em->getRepository('BookShopBundle:Product')->findAll();
        $categories=$em->getRepository('BookShopBundle:Category')->findAll();

        $max_promotion=$this->getDoctrine()->getRepository('BookShopBundle:Promotion')
            ->fetchBiggestPromotion();
        $this->get('session')->getFlashBag()->add('info', "Max promotion active today: ".$max_promotion);

        $calc=$this->get('price_calculator');


        return $this->render('product/index.html.twig', array(
            'categories'=>$categories,
            'product' => $products,
            'max_promotion'=>$max_promotion,
            'calc'=>$calc,
            'user'=>$this->getUser(),
        ));
    }

    /**
     * @Route("/{id}/products_list",name="products_list")
     * @param Product $product
     * @Method({"GET","POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productsListByCategory(Product $product)
    {
        $category_id=$product->getCategory();



       $products=$this->getDoctrine()->getRepository('BookShopBundle:Product')->findBy([
           'category'=>$category_id
       ]);


        return $this->render('product/products_list.html.twig',[
            'category'=>$category_id,
           'products'=>$products
        ]);
    }
    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $product = new Product();


        $categories=$this->getDoctrine()->getRepository(Category::class)->findAll();
        $form = $this->createForm('BookShopBundle\Form\ProductType', $product);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUser($this->getUser());

            /** @var UploadedFile $file */
            $file = $product->getImageForm();

            if (!$file) {
                $form->get('image_form')->addError(new FormError('Image is required'));
            } else {
                $file = $product->getImageForm();
                $filename = md5($product->getName() . '' . $product->getPrice());
                $file->move(
                    $this->get('kernel')->getRootDir() . '/../web/images/product/',
                    $filename
                );

                $product->setImage($filename);

                $em = $this->getDoctrine()->getManager();

                $em->persist($product);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', "The book ".strtoupper($product->getName())." is created successfully!");
                return $this->redirectToRoute('product_show', array('id' => $product->getId()));
            }
        }
        return $this->render('product/new.html.twig', array(

'categories'=>$categories,
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Product $product)
    {
        if($product->getUser()->getId()!=$this->getUser()->getId() &&
            !$this->isGranted('ROLE_ADMIN', $this->getUser())){
            $this->get('session')->getFlashBag()->add('error', 'You are not the owner of this project');
            return $this->redirectToRoute('product_index');
        }
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('BookShopBundle\Form\ProductType', $product);

        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {

            if ($product->getImageForm() instanceof UploadedFile) {
                /** @var UploadedFile $file */
                $file = $product->getImageForm();

                $filename = md5($product->getName() . '' . $product->getPrice());

                $file->move(
                    $this->get('kernel')->getRootDir() . '/../web/images/product/',
                    $filename
                );

                $product->setImage($filename);
            }


            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', "The book ".strtoupper($product->getName())." is edited successfully!");
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product)
    {
        if($product->getUser()->getId()!=$this->getUser()->getId()){
            $this->get('session')->getFlashBag()->add('error', 'You are not the owner of this project');
            return $this->redirectToRoute('product_index');
        }
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }
        $this->get('session')->getFlashBag()->add('delete', "The book ".strtoupper($product->getName())." is deleted successfully!");
        return $this->redirectToRoute('product_index');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("/manage/products", name="admin_manage_products")
     */
    public function manageProductsAction()
    {

        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('BookShopBundle:Product')->findAll();

        return $this->render('product/manage.html.twig', array(
            'product'  => $products,
            'user'      => $this->getUser(),


        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/manage/products/{id}/delete", name="admin_product_delete")
     * @Method("GET")
     */
    public function manageDeleteAction(Request $request, Product $product)
    {


        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();


        return $this->redirectToRoute('admin_manage_products');
    }


}
