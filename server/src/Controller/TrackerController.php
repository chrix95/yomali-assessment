<?php
namespace Src\Controller;

use Src\Classes\Tracker;

class TrackerController {
    private $requestMethod;
    private $data;
    private $query;

    private $trackerGateway;

    public function __construct($db, $requestMethod, $data = [], $query = null)
    {
        $this->requestMethod = $requestMethod;
        $this->data = $data;
        $this->query = $query;

        $this->trackerGateway = new Tracker($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->createTracker($this->data);
                break;
            case 'GET':
                $response = $this->getTrackings($this->query);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }

    private function getTrackings($query)
    {
        $result = $this->trackerGateway->retrieveAll($query);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createTracker($data) 
    {
        if (!$this->validateTrackerData($data)) {
            return [
                'status_code_header' => 'HTTP/1.1 422 UNPROCESSABLE ENTITY',
                'body' => json_encode([
                    'status' => 'error',
                    'message' => 'url and ip_address data not provided'
                ])

            ];
        }

        $result = $this->trackerGateway->create($data);
        if (! $result) {
            return $this->notFoundResponse();
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function validateTrackerData($data) {
        if (isset($data['url']) && isset($data['ip_address'])) {
            return true;
        }
        return false;
    }
}