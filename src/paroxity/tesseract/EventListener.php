<?php

namespace paroxity\tesseract;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener
{
    /** @var Tesseract */
    private $plugin;

    public function __construct(Tesseract $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onPlayerJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        $session = $player->getNetworkSession();

        $ip = $this->plugin->getProxyAddress();
        if ($session->getIp() !== $ip) {
            $player->transfer($this->plugin->getProxyAddress(), $this->plugin->getProxyPort());
        }
    }
}