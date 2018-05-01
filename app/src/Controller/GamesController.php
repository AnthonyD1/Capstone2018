<?php
// src/Controller/GamesController.php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

class GamesController extends AppController {
    private $game_table;
    private $game_card_table;
    private $pile_card_table;
    private $main_deck_card_table;
    private $win_pile_table;

    /*
     * Overwrite the construct method in AppController and also run this one.
     *
     */
    public function initialize(){
        parent::initialize();

        $this->game_table = TableRegistry::get('Games');
        $this->game_card_table = TableRegistry::get('game_cards');
        $this->pile_card_table = TableRegistry::get('pile_cards');
        $this->main_deck_card_table = TableRegistry::get('main_deck_cards');
        $this->win_pile_table = TableRegistry::get('win_piles');

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

        $internalId = $this->getInternalId($game_id);

        //Populate game_cards table for this game
        $this->generateCards($internalId);

        //Populate piles
        $gameCards = $this->game_card_table->find('all')->where(['game_id' => $internalId])->toArray();

        for($i = 0; $i < 7; $i++) {
            for($j = 0; $j <= $i; $j++) {
                //$i is the number of the pile that this card is in
                $newPileCard = $this->pile_card_table->newEntity();
                $curCardId = array_pop($gameCards)->id;
                $this->pile_card_table->patchEntity($newPileCard, ['game_id' => $internalId, 'pile_id' => $i, 'card_id' => $curCardId]);
                $this->pile_card_table->save($newPileCard);
            }
        }

        //Populate main_deck_cards
        foreach($gameCards as $card) {
            $newMainDeckCard = $this->main_deck_card_table->newEntity();
            $this->main_deck_card_table->patchEntity($newMainDeckCard, ['game_id' => $internalId, 'card_id' => $card->id]);
            $this->main_deck_card_table->save($newMainDeckCard);
        }
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

    public function moveToPile($game_id, $card_id, $destination) {
        /*
         * Source and destination types:
         * 0 = deck
         * 1 = pile
         * 2 = win pile
         */
        function doMove($source_type, $destination_type, $destination_id, $card_id, $game_id) {
            //From deck to deck OR from win pile to win pile
            if($source_type === $destination_type && $source_type !== 1) {
                throw new MethodNotAllowedException('Cannot move card to it\'s current location');
            }
            //From anywhere to deck
            if($destination_type === 0) {
                throw new MethodNotAllowedException('Cannot move card back into main deck');
            }
            //From win pile to anywhere
            if($source_type === 2) {
                throw new MethodNotAllowedException('Cannot remove card from win pile');
            }

            if($source_type === 0) {
                //Delete card from main deck table
                foreach($this->main_deck_card_table->find('all')->where(['card_id' => $card_id])->toArray() as $card) {
                    $card->delete();
                }
                //From deck to pile
                if($destination_type === 1) {
                    //TODO: Verify that it is a legal move
                    //add card to pile_cards table
                    $new_pile_card = $this->pile_card_table->newEntity();
                    $this->pile_card_table->patchEntity($new_pile_card, ['game_id' => $game_id, 'pile_id' => $destination_id, 'card_id' => $card_id]);
                    $this->pile_card_table->save($new_pile_card);
                } else { //From deck to win pile
                    //TODO: Verify that the card is the same suit as ones already in this pile and that it is 1 greater than the last
                    //update highest_card_id in win_piles table
                    $destination_win_pile = $this->win_pile_table->find('all')->where(['game_id' => $game_id, 'id' => $destination_id])->toArray()[0];
                    $this->win_pile_table->patchEntity($destination_win_pile, ['highest_card_id' => $card_id]);
                    $this->win_pile_table->save($destination_win_pile);
                }
            } else if($source_type === 1) {
                if($destination_type === 1) { //From pile to pile
                    //TODO: Verify that this is a valid move
                    //update pile_id in pile_cards
                    $pile_card = $this->pile_card_table->find('all')->where(['card_id' => $card_id]);
                    $this->pile_card_table->patchEntity($pile_card, ['pile_id' => $destination_id]);
                    $this->pile_card_table->save();
                } else if($destination_type === 2) { //From pile to win pile
                    //TODO: Verify that the card is the same suit as ones already in this pile and that it is 1 greater than the last
                    //Delete card in pile_cards
                    foreach($this->pile_card_table->find('all')->where(['card_id' => $card_id])->toArray() as $c) {
                        $c->delete();
                    }

                    //update highest_card_id in win_piles table
                    $destination_win_pile = $this->win_pile_table->find('all')->where(['game_id' => $game_id, 'id' => $destination_id])->toArray()[0];
                    $this->win_pile_table->patchEntity($destination_win_pile, ['highest_card_id' => $card_id]);
                    $this->win_pile_table->save($destination_win_pile);
                }
            }
        }

        $internalId = $this->getInternalId($game_id);
        $card = $this->game_card_table->find('all')->where(['game_id' => $internalId, 'card_id' => $card_id])->toArray()[0];

        $all_pile_cards = $this->pile_card_table->find('all')->where(['game_id' => $internalId])->toArray();
        $all_pile_card_ids = array();
        foreach($all_pile_cards as $a_card) {
            array_push($all_pile_card_ids, $a_card->id);
        }

        $all_main_deck_cards = $this->main_deck_card_table->find('all')->where(['game_id' => $internalId])->toArray();
        $all_main_deck_card_ids = array();
        foreach($all_main_deck_cards as $a_card) {
            array_push($all_main_deck_card_ids, $a_card->id);
        }

        //IF card is in a pile, make sure it has the lowest ID of all cards in that pile
        //e.g. if $card is in $pile_cards
        if(array_search($card->id, $all_pile_card_ids)) {
            //Find the pile ID of the relevant card
            $my_card_pile_id = $this->pile_card_table->find('all')->where(['game_id' => $internalId, 'card_id' => $card_id])->toArray()[0]->pile_id;

            //Get all the cards in the pile of the card of interest
            $single_pile_cards = $this->pile_card_table->find('all')->where(['game_id' => $internalId, 'pile_id' => $my_card_pile_id])->toArray();

            //Check if the card is on the top of the pile (card with the lowest ID is always on top). If it is not, disallow the move.
            foreach($single_pile_cards as $single_pile_card) {
                if($single_pile_card->id < $card->id) throw new MethodNotAllowedException('Card is not visible.');
                die();
            }

            doMove(, $destination_type, $destination_id, $card_id, $game_id);
        } else if(array_search($card->id, $all_main_deck_card_ids)) {
            //Move the card
        } else {
            throw new MethodNotAllowedException('Card has already been moved to a win pile.');
        }
    }

    public function moveToWinPile($game_id, $card_id, $destination) {

    }



    /**
     * @param $unique_id string The public-facing unique ID for the game
     * Check if the game exists from its unique id
     * @return boolean Returns true if the game exists, otherwise false
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

        $res = $this->Games->delete($this->getEntityFromUniqueId($unique_id));

        $this->set('result', ['success' => $res]);
        $this->set('_serialize', 'result');
    }

    public function index(){

        echo $this->game_table->find();


    }



}
