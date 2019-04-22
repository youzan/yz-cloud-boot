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

        echo json_encode($_SERVER);
    }

}

(new EchoServer())->process();