<?php
// src/Controller/GamesController.php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\ORM\TableRegistry;

class GamesController extends AppController {
    private $game_table;
    private $game_card_table;

    /*
     * Overwrite the construct method in AppController and also run this one.
     *
     */
    public function initialize(){
        parent::initialize();

        $this->game_table = TableRegistry::get('Games');
        $this->game_card_table = TableRegistry::get('game_cards');

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

        //Populate game_cards table for this game
        $this->generateCards($this->getInternalId($game_id));

        //TODO: Populate piles table for this game
    }

    private function generateCards($id) {
        $newDeck = array();
        for($suit = 0; $suit < 4; $suit++) {
            for($value = 0; $value < 13; $value++) {
                $newCard = $this->game_card_table->newEntity();
                $this->game_card_table->patchEntity($newCard, ['game_id' => $id, 'number' => $value, 'suit' => $suit, 'face_down' => true]);
                array_push($newDeck, $newCard);
            }
        }

        shuffle($newDeck);

        foreach($newDeck as $card) {
            $this->game_card_table->save($card);
        }
    }

    /**
     * @param $unique_id The public-facing unique ID for the game
     * Check if the game exists from its unique id
     * @return Returns true if the game exists, otherwise false
     */
    private function checkExistence($unique_id) {
        return (count($this->Games->find('all')->where(['unique_id =' => $unique_id])->toArray()) > 0);
    }

    private function getInternalId($unique_id) {
        return $this->getEntityFromUniqueId($unique_id)->id;
    }

    private function getEntityFromUniqueId($unique_id) {
        return $this->Games->find('all')->where(['unique_id =' => $unique_id])->first();
    }

    public function destroyGame($unique_id) {
        //Remove data for this game from game table
        if(!$this->checkExistence($unique_id)) {
            $this->RequestHandler->renderAs($this, 'json');
            throw new MethodNotAllowedException('invalid id 😂');
        }

        //Remove cards for this game from game_cards table
        //$internal_id = $this->getInternalId($unique_id);
        //$this->game_card_table->deleteAll(['id' => $internal_id]);


        $res = $this->Games->delete($this->getEntityFromUniqueId($unique_id));

        $this->set('result', ['success' => $res]);
        $this->set('_serialize', 'result');
    }

    public function index(){

        echo $this->game_table->find();


    }



}