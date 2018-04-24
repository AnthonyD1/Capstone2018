<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;


class GameCard extends entity {
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}