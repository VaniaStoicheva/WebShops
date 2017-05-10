<?php

namespace BookShopBundle\Repository;
use BookShopBundle\BookShopBundle;
use BookShopBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * PromotionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PromotionRepository extends EntityRepository
{
    /**
     * @param Category $category
     * @return int
     */
    public function fetchBiggestPromotion($category=null)
    {
        $db=$this->createQueryBuilder('p');
        $today=new \DateTime();

         $db->select('p.percent')
            ->where($db->expr()->lte('p.startDate',':today'))
            ->andWhere($db->expr()->gte('p.endDate',':today'))
            ->setParameter(':today',$today->format('Y-m-d'))
            ->orderBy('p.percent','DESC')
            ->setMaxResults(1);

         if($category!==null){
             $db->andWhere($db->expr()->eq('p.category',':category'));
             $db->setParameter(':category',$category);
         }
         /*else{
             $db->andWhere($db->expr()->isNull('p.category'));
         }*/
         $query=$db->getQuery();

         if($query->getOneOrNullResult()!==null){
             return $query->getSingleScalarResult();
         }
         return 0;
    }

    public   function fetchCategoriesPromotions()
    {
        $db=$this->createQueryBuilder('p');
        $today=new \DateTime();

        $db->select(['MAX(p.percent) as percent','c.id'])
            ->join('p.category','c')
            ->where($db->expr()->lte('p.startDate',':today'))
            ->andWhere($db->expr()->gte('p.endDate',':today'))
            ->setParameter(':today',$today->format('Y-m-d'))

            ->groupBy('c')
            ->orderBy('p.percent','DESC');

        $results=$db->getQuery()->getResult();

        $promotions=[];
        foreach ($results as $promotion){
            $promotions[(int)$promotion['id']]=(int)$promotion['percent'];
        }
        return $promotions;
    }



}
