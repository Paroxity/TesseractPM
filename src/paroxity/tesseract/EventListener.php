<?php

namespace paroxity\tesseract;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use function preg_match;

class EventListener implements Listener
{
    /** @var Tesseract */
    private $plugin;

    public function __construct(Tesseract $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onPlayerPreLogin(PlayerPreLoginEvent $event): void
    {
        $ip = $this->plugin->getProxyAddress();
        if ($event->getIp() !== $ip && !preg_match("/^(127|172)/", $event->getIp())) {
            $event->setKickReason(PlayerPreLoginEvent::KICK_REASON_PLUGIN, "You must join via the proxy");
        }
    }
}