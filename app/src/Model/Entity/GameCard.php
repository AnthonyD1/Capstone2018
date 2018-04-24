<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;


class GameCard extends entity {
    /*
    public function initialize($config) {
        $this->belongsTo('Games')->setDependent(true)->setBindingKey('game_id')->setForeignKey('id');
    }
    */


    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}