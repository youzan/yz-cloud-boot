<?php

namespace YouzanCloudBootTests\Stub;

class FakeBean
{
}


class Response
{
    private $code;
    private $msg;

    private $data;

    function dataType(): string {
        return FakeBean::class;
    }
}

