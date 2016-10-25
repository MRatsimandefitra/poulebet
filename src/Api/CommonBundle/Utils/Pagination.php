<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 25/10/2016
 * Time: 11:27
 */

namespace Api\CommonBundle\Utils;


class Pagination {
    private $paginator;
    private $query;
    private $perPage = 10;


    public function paginate($page){
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->query, /* query NOT result */
          //  $request->query->getInt('page', 1)/*page number*/,
            $page,
            $this->perPage() /*limit per page*/
        );
    }

    /**
     * @return mixed
     */
    public function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * @param mixed $paginator
     */
    public function setPaginator($paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }

}