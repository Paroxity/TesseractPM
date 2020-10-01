<?php

declare(strict_types=1);

namespace paroxity\tesseract;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use function preg_match;
use function var_dump;

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

    public function onPlayerJoin(PlayerJoinEvent $event): void{
        $player = $event->getPlayer();
        var_dump($this->plugin->getPlayerAddress($player->getUniqueId()));
        var_dump($this->plugin->getPlayerXuid($player->getUniqueId()));
    }
}