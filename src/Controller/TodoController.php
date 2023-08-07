<?php
namespace Src\Controller;

use Src\TableGateways\TodoGateway;

class TodoController {

    private $db;
    private $requestMethod;
    private $todoId;

    private $todoGateway;

    public function __construct($db, $requestMethod, $todoId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->todoId = $todoId;

        $this->todoGateway = new TodoGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->todoId) {
                    $response = $this->getTodo($this->todoId);
                } else {
                    $response = $this->getAllTodos();
                };
                break;
            case 'POST':
                $response = $this->createTodo();
                break;
            case 'PUT':
                $response = $this->updateTodo($this->todoId);
                break;
            case 'DELETE':
                $response = $this->deleteTodo($this->todoId);
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

    private function getAllTodos()
    {
        $result = $this->todoGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getTodo($id)
    {
        $result = $this->todoGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createTodo()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateTodo($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->todoGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateTodo($id)
    {
        $result = $this->todoGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateTodo($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->todoGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteTodo($id)
    {
        $result = $this->todoGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->todoGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateTodo($input)
    {
        if (! isset($input['title'])) {
            return false;
        }
        if (! isset($input['descriptions'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}