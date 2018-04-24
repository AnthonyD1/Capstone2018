<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class GamesTable extends Table {
    public function initialize(array $config) {
        $this->hasMany('GameCards')->setDependent(true);
        $this->hasMany('PileCards')->setDependent(true);
        $this->hasMany('MainDeckCards')->setDependent(true);
    }
}