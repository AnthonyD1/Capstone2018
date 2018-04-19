<?php
// src/Controller/GamesController.php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class GamesController extends AppController {
    private $game_table;

    /*
     * Overwrite the construct method in AppController and also run this one.
     *
     */
    public function initialize(){
        parent::initialize();

        $this->game_table = TableRegistry::get('Games');


    }

    /*
     * Generate a new game ID and populate tables.
     */
    public function newGame(){
        $game_id = uniqid();

        // generate a new gameID and add to Games table
        $game = $this->game_table->newEntity();
        $game->unique_id = $game_id;
        $this->game_table-> save($game);

        $this->set('game', $game->unique_id);
        $this->set('_serialize', 'game');

        // populate tables

    }



    public function index(){

        echo $this->game_table->find();


    }



}