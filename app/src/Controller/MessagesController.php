<?php
// src/Controller/ArticlesController.php

namespace App\Controller;

class MessagesController extends AppController {
    public function initialize() {
        parent::initialize();

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash');
    }

    public function index() {
        $messages = $this->Paginator->paginate($this->Messages->find());
        $this->set(compact('messages'));
    }

    public function add() {
        $message = $this->Messages->newEntity();

        if($this->request->is('post')) {
            $message = $this->Messages->patchEntity($message, $this->request->getData());

            if($this->Messages->save($message)) {
                $this->Flash->success("Your message has been saved!");
                return $this->redirect(['action'=>'index']);
            }
            //else by return
            $this->Flash->error(__('Unable to add your message.'));
        }

        //Render the view
        $this->set('message', $message);
    }
}