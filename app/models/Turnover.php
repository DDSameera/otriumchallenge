<?php

class Turnover
{

    private $db;

    /* DB Coonection initialize */
    public function __construct()
    {
        $this->db = new Database;
    }


    /* Get Turnover Results based up on Start Date,End Date,Brand Id */
    public function getTurnOvers($start_date = "", $end_date = "", $brand_id = "")
    {


        $sql = "SELECT *,brands.id as brand_id,brands.name as brand_name,
			    gmv.id as gmv_id,
				ROUND(gmv.turnover) AS turnover,
				DATE(gmv.date) AS date,
				DATE(brands.created) AS created
				FROM gmv
				INNER JOIN brands
				ON gmv.brand_id = brands.id";


        if (!empty($brand_id)) {
            $sql .= " WHERE gmv.brand_id='$brand_id'";

        }

        if (!empty($start_date) && !empty($end_date)) {
            $sql .= " AND (gmv.date BETWEEN '$start_date' AND '$end_date')";
        }


        $sql .= " ORDER BY date ASC";

        $this->db->query($sql);
        return $results = $this->db->resultSet();
    }


}