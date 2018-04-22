<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 4/1/18
 * Time: 5:37 PM
 */

namespace App\Model\Entity;

use Cake\ORM\Entity;


class Game extends entity {
    public function initialize($config) {
        $this->hasMany('GameCards')->setDependent(true)->foreignKey('game_id');
    }

    protected $_accessible = [
        '*' => false
    ];
}