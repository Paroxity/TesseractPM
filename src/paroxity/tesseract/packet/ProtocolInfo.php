<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

class ProtocolInfo
{
    public const PROXY_AUTH_REQUEST_PACKET = 0xc8;
    public const PROXY_AUTH_RESPONSE_PACKET = 0xc9;
    public const PROXY_BLOCKED_CHAT_PACKET = 0xca;
    public const PROXY_TRANSFER_REQUEST_PACKET = 0xcb;
    public const PROXY_TRANSFER_RESPONSE_PACKET = 0xcc;
    public const PROXY_SEND_MESSAGE_PACKET = 0xcd;
    public const PROXY_RECEIVE_MESSAGE_PACKET = 0xce;
}