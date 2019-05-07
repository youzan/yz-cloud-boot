<?php


namespace YouzanCloudBoot\Http;


class HttpClientResponse
{

    /**
     * @var string
     */
    private $headers;

    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $body;

    public function __construct($code, $headers, $body)
    {
        $this->code = $code;
        $this->headers = trim($headers);
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getHeaders(): string
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getHeadersAsList(): ?array
    {
        return explode("\r\n", $this->headers);
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return array
     */
    public function getBodyAsJson(): ?array
    {
        return json_decode($this->body, true);
    }


}