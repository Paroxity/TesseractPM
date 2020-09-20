<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

use paroxity\tesseract\exception\TesseractAuthException;
use paroxity\tesseract\Tesseract;

class ProxyAuthResponsePacket extends ProxyPacket
{
    public const NETWORK_ID = ProtocolInfo::PROXY_AUTH_RESPONSE_PACKET;

    public const RESPONSE_SUCCESS = 0;
    public const RESPONSE_FAIL = 1;

    /** @var int */
    private $response;
    /** @var string */
    private $reason;

    public static function create(int $response, string $reason): self
    {
        $result = new self;
        $result->response = $response;
        $result->reason = $reason;
        return $result;
    }

    public function getResponse(): int
    {
        return $this->response;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    protected function decodePayload(): void
    {
        $this->response = $this->getByte();
        $this->reason = $this->getString();
    }

    protected function encodePayload(): void
    {
        $this->putByte($this->response);
        $this->putString($this->reason);
    }

    public function proxyHandle(): void
    {
        if ($this->response === self::RESPONSE_FAIL) {
            throw new TesseractAuthException($this->reason);
        }
        Tesseract::getInstance()->getLogger()->info($this->reason);
    }
}