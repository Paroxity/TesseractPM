<?php

namespace paroxity\tesseract;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;

class EventListener implements Listener
{
    /** @var Tesseract */
    private $plugin;

    public function __construct(Tesseract $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onPlayerPreLogin(PlayerLoginEvent $event): void
    {
        $player = $event->getPlayer();
        $session = $player->getNetworkSession();

        $ip = $this->plugin->getProxyAddress();
        $port = $this->plugin->getProxyPort();
        if ($session->getIp() !== $ip) {
            $player->transfer($this->plugin->getProxyAddress(), $this->plugin->getProxyPort());
            $event->setCancelled();
        }
    }
}