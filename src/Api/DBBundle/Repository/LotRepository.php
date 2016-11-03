<?php

namespace Api\DBBundle\Repository;

/**
 * LotRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LotRepository extends \Doctrine\ORM\EntityRepository
{
    public function findCategoryLot($isGroup = null, $category = null, $prix = null){

        $dql = "SELECT l from ApiDBBundle:Lot l LEFT JOIN l.lotCategory lc ";
        $where = array();
        $params = array();
        if($category){
            $where[] = " lc.category LIKE :category ";
            $params['category'] = $category;
        }
        if(is_array($prix)){
           /* switch($prix){
                case:

            }*/
        }
        if($isGroup === true){
            $dql .= " GROUP BY lc.category";
        }

        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }
    
    /**
     * get Lots by Category
     *
     * @return mixed
     */
    public function getLotsByCategory($categoryId)
    {
        $qb = $this->createQueryBuilder('lot')
            ->select('lot')
            ->join('lot.lotCategory', 'lotCategory');
        if(!empty($categoryId)){
            $qb->andWhere('lotCategory.id = :categoryId')
                  ->setParameter('categoryId', $categoryId);            
        }
        $query = $qb->orderBy('lot.id', 'asc')
                    ->getQuery();

        return $results = $query->getResult();
    }
    
    /**
     * get Lots 
     *
     * @return mixed
     */
    public function findAllOrderedByDate()
    {
        $query = $this->createQueryBuilder('lot')
            ->select('lot')
            ->join('lot.lotCategory', 'lotCategory')
            ->orderBy('lot.createdAt', 'desc')
            ->getQuery();

        return $query->getResult();
    }
}
