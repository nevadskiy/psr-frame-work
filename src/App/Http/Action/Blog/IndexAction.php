<?php

namespace App\Http\Action\Blog;

use Zend\Diactoros\Response\JsonResponse;

class IndexAction
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function __invoke()
    {
        $stmt = $this->db->query('SELECT * FROM posts ORDER BY id DESC');

        //return new JsonResponse([
        //    ['id' => 2, 'title' => 'The Second Post'],
        //    ['id' => 1, 'title' => 'The First Post'],
        //]);
        return new JsonResponse($stmt->fetchAll());
    }
}
