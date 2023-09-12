<?php

namespace Http\Response;

class JsonResponse implements IResponse
{
    const STATUS_OK = 200;
    const STATUS_ERROR = 500;
    const KEY_ITEMS = 'data';

    public $content;

    public function input($content)
    {
        $this->content = $content;
    }

    public function out()
    {
        $this->emit_one([
            'test' => $this->content,
        ]);
    }

    public function emit_one(array $data = null)
    {
        header('Content-Type: application/json; charset=utf-8');

        $responseData = [
            'status' => self::STATUS_OK,
        ];

        if ($data) {
            $responseData[self::KEY_ITEMS] = $data;
        }

        echo json_encode($responseData);
        exit();
    }

    public function emit_create()
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => self::STATUS_OK
        ]);
        exit();
    }

    public function emit($data)
    {
        header('Content-Type: text/html; charset=utf-8');
        echo $data;
        exit();
    }

    public function emit_all(int $status, array $data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            array(
                'status' => $status,
                self::KEY_ITEMS => $data
            )
        );
        exit();
    }

    public function badRequest(string $message)
    {
        header('Content-Type: application/json; charset=utf-8');
        header('HTTP/1.0 400 Bad Request');
        echo json_encode(array(
            'error' => 'Bad Request',
            'message' => $message,
        ));
        exit();
    }

    public function errorRequest(string $message)
    {
        header('Content-Type: application/json; charset=utf-8');
        header('HTTP/1.0 500 Bad Request');
        echo json_encode(array(
            'error' => 'Error Request',
            'message' => $message,
        ));
        exit();
    }
}