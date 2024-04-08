<?php
namespace Src\Classes;

class Tracker {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function retrieveAll($query) {
        $startDate = isset($query['startDate']) ? date('Y-m-d H:i:s', strtotime($query['startDate']. ' 00:00:00')) : NULL;
        $endDate = isset($query['endDate']) ? date('Y-m-d H:i:s', strtotime($query['endDate'] . '23:59:59')) : NULL;
        
        try {
            $statement = "SELECT page_url, COUNT(DISTINCT ip_address) AS unique_visits FROM visits GROUP BY page_url";
    
            if (isset($startDate) && isset($endDate)) {
                $statement = "SELECT page_url, COUNT(DISTINCT ip_address) AS unique_visits FROM visits WHERE visit_time BETWEEN :start_date AND :end_date GROUP BY page_url";
            }
            
            $statement = $this->db->prepare($statement);
            if (isset($startDate) && isset($endDate)) {
                $statement->bindParam(':start_date', $startDate);
                $statement->bindParam(':end_date', $endDate);
            }
            $statement->execute();
            $response = $statement->fetchAll(\PDO::FETCH_ASSOC);

            if ($response) {
                return [
                    'status' => 'success',
                    'data' => $response
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'No data found'
                ];
            }
        } catch (\PDOException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function create($data)
    {
        $url = $data['url'];
        $ip = $data['ip_address'];
        $platform = isset($data['platform']) ? $data['platform'] : NULL;
        $timestamp = date('Y-m-d H:i:s');

        $statement = "INSERT INTO visits (page_url, ip_address, platform, visit_time) VALUES (:page_url, :ip_address, :platform, :visit_time)";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':page_url', $url);
            $statement->bindParam(':ip_address', $ip);
            $statement->bindParam(':platform', $platform);
            $statement->bindParam(':visit_time', $timestamp);
            $statement->execute();

            return [
                'status' => 'success',
                'message' => 'Record added successfully',
                'data' => null
            ];
        } catch (\PDOException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}