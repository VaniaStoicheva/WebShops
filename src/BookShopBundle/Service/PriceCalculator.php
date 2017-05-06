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
     * @var EntityManager
     */
    protected $emanager;
    protected $promotion;
    protected $category_promotions=[];

    /**
     * PriceCalculator constructor.
     * @param EntityManager $emanager
     */
    public function __construct(EntityManager $emanager)
    {
        $this->emanager = $emanager;

    }

    /**
     * @param Product $product
     * @return float
     */
    public function calculate($product)
    {
        $promotion=$this->emanager->getRepository('BookShopBundle:Promotion')
            ->fetchBiggestPromotion($product->getCategories());
        if($promotion===0 && $this->promotion===null){
            $promotion=$this->emanager->getRepository('BookShopBundle:Promotion')
                ->fetchBiggestPromotion();
        }

        return $product->getPrice()-$product->getPrice()*($promotion/100);

    }

    private function initMaxPromotion()
    {
        $this->promotion=$this->emanager->getRepository('BookShopBundle:Promotion')->fetchBiggestPromotion();
    }

}