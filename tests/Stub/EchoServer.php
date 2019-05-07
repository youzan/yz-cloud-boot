<?php

namespace YouzanCloudBootTests\Stub;

class EchoServer
{

    public function process()
    {
        header('Content-Type: application/json');
        $headers = [];
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        if (isset($_SERVER['CONTENT_TYPE'])
            and(
                strpos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') !== false
                || strpos($_SERVER['CONTENT_TYPE'], 'application/x-www-form-urlencoded') !== false
            )
        ) {
            $body = $_POST;
        } else {
            $body = file_get_contents('php://input');
        }

        $result = ['headers' => $headers, 'body' => $body, 'server' => $_SERVER];

        if ($_FILES) {
            $files = [];
            foreach ($_FILES as $key => $tempFile) {
                $item = [];
                $item['uploadFileName'] = $tempFile['name'];
                $item['content'] = file_get_contents($tempFile['tmp_name']);
                $files[$key] = $item;
            }
            $result['files'] = $files;
        }

        echo json_encode($result);
    }

}

(new EchoServer())->process();