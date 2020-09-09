<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

class ProtocolInfo
{
    public const PROXY_AUTH_REQUEST_PACKET = 0xC8;
    public const PROXY_AUTH_RESPONSE_PACKET = 0xC9;
    public const PROXY_BLOCKED_CHAT_PACKET = 0xCA;
}