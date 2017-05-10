<?php
/**
 * Created by PhpStorm.
 * User: 1234
 * Date: 7.5.2017 г.
 * Time: 09:12 ч.
 */

namespace BookShopBundle\Service;


use BookShopBundle\Entity\Category;
use BookShopBundle\Repository\PromotionRepository;

class PromotionManager
{

    protected $general_promotion;
    protected $category_promotion;

    /**
     * PromotionManager constructor.
     * @param PromotionRepository $repo
     */
    public function __construct(PromotionRepository $repo)
    {
        $this->general_promotion = $repo->fetchBiggestPromotion();
        $this->category_promotion = $repo->fetchCategoriesPromotions();
    }


    /**
     * @return int
     */
    public function getGeneralPromotion()
    {
        return $this->general_promotion ?? 0;
    }

    /**
     * @param Category $category
     * @return bool
     */
    public function hasCategoryPromotion($category)
    {
        return array_key_exists($category->getId(),$this->category_promotion);
    }

    /**
     * @param Category $category
     * @return int
     */
    public function getCategoryPromotion($category)
    {
        return $this->category_promotion[$category->getId()];
    }

    /**
     * @param int $general_promotion
     */
    public function setGeneralPromotion(int $general_promotion)
    {
        $this->general_promotion = $general_promotion;
    }

    /**
     * @param array $category_promotion
     */
    public function setCategoryPromotion(array $category_promotion)
    {
        $this->category_promotion = $category_promotion;
    }




}