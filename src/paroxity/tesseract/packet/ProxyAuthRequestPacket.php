<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

class ProxyAuthRequestPacket extends ProxyPacket
{
    public const NETWORK_ID = ProtocolInfo::PROXY_AUTH_REQUEST_PACKET;

    public const CONN_TYPE_SERVER = 0;
    public const CONN_TYPE_OTHER = 1;

    /** @var string */
    public $secret;
    /** @var string */
    public $name;
    /** @var int */
    public $type;
    /** @var string */
    public $address;

    protected function decodePayload(): void
    {
        $this->secret = $this->getString();
        $this->name = $this->getString();
        $this->type = $this->getByte();
        $this->address = $this->getString();
    }

    protected function encodePayload(): void
    {
        $this->putString($this->secret);
        $this->putString($this->name);
        $this->putByte($this->type);
        $this->putString($this->address);
    }

    public function proxyHandle(): void
    {
        // NOOP
    }
}