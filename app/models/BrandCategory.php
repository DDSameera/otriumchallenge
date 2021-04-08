<?php

class BrandCategory
{

    private $db;

    /* DB Coonection initialize */
    public function __construct()
    {
        $this->db = new Database;
    }


    /* Fetch All Brands */
    public function getAll()
    {
        $sql = "SELECT *  FROM brands";

        $this->db->query($sql);
        return $results = $this->db->resultSet();
    }

}