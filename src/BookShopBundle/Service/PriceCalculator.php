<?php
/**
 * Created by PhpStorm.
 * User: 1234
 * Date: 4.5.2017 г.
 * Time: 14:00 ч.
 */

namespace BookShopBundle\Service;


use BookShopBundle\Entity\Product;
use BookShopBundle\Entity\Category;
use Doctrine\ORM\EntityManager;
use BookShopBundle\Entity\Promotion;

class PriceCalculator
{
    /**
     * @var PromotionManager
     */
    protected $manager;

    /**
     * PriceCalculator constructor.
     * @param PromotionManager $manager
     */
    public function __construct(PromotionManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Product $product
     * @return float
     */
    public function calculate($product)
    {

        $category=$product->getCategory();

        $promotion=$this->manager->getGeneralPromotion();

        if($this->manager->hasCategoryPromotion($category)){
            $promotion=$this->manager->getCategoryPromotion($category);
        }
        return $product->getPrice()-$product->getPrice()*($promotion/100);
    }


    /**
     * @param Product $product
     * @return int
     */
    public  function percent($product)
    {
        $category=$product->getCategory();
        $promotion=$this->manager->getCategoryPromotion($category);
        return $promotion;
    }
}