<?php

/**
 * Copyright (c) 2020 PJZ9n.
 *
 * This file is part of JoinCommands.
 *
 * JoinCommands is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * JoinCommands is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with JoinCommands.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace PJZ9n\JoinCommands;

require_once __DIR__ . "/../../../vendor/autoload.php";

use Particle\Validator\Validator;
use pocketmine\plugin\PluginBase;

class JoinCommands extends PluginBase
{
    
    public function onEnable(): void
    {
        $this->saveConfig();
        $this->saveDefaultConfig();
        //Validate
        $validator = new Validator();
        //TODO 配列の中身も検証
        $validator->required("join-player-commands")->isArray();
        $validator->required("join-console-commands")->isArray();
        $validateResult = $validator->validate($this->getConfig()->getAll());
        if ($validateResult->isNotValid()) {
            $errorMessages = [];
            foreach ($validateResult->getFailures() as $failure) {
                $errorMessages[] = $failure->format();
            }
            $this->getLogger()->error("Configファイルの検証に失敗しました: " . implode(" | ", $errorMessages));
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        $this->getConfig()->setAll($validateResult->getValues());
    }
    
}