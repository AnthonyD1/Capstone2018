<?php
// src/Controller/GamesController.php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\MethodNotAllowedException;
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

        $this->LoadComponent('RequestHandler');
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

        $this->set('game', ['game_id' => $game->unique_id]);
        $this->set('_serialize', 'game');

        //TODO: Populate game_cards table for this game

        //TODO: Populate piles table for this game
    }

    /**
     * @param $unique_id The public-facing unique ID for the game
     * Check if the game exists from its unique id
     */
    public function checkExistence($unique_id) {
        $result = (count($this->Games->find('all')->where(['unique_id =' => $unique_id])->toArray()) > 0);

        $this->set('result', ['id' => $unique_id, 'exists' => $result]);
        $this->set('_serialize', 'result');
    }

    private function getInternalId($unique_id) {
        $this->getEntityFromUniqueId($unique_id)->toArray()['id'];
    }

    private function getEntityFromUniqueId($unique_id) {
        return $this->Games->find('all')->where(['unique_id =' => $unique_id])->first();
    }

    public function destroyGame($unique_id) {
        //TODO: Remove data for this game from all tables
        if(!$this->checkExistence($unique_id)) {
            $this->RequestHandler->renderAs($this, 'json');
            throw new MethodNotAllowedException('invalid id 😂');
        }

        $res = $this->Games->delete($this->getEntityFromUniqueId($unique_id));

        $this->set('result', ['success' => $res]);
        $this->set('_serialize', 'result');
    }

    public function index(){

        echo $this->game_table->find();


    }



}