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
        //variable in case the user makes a new message
        $newMessage = $this->Messages->newEntity();

        //POST handler in case the client submits the form w/o JS support
        if($this->request->is('post')) {
            $message = $this->Messages->patchEntity($newMessage, $this->request->getData());

            if($this->Messages->save($message)) {
                $this->Flash->success("Your message has been saved!");
                return $this->redirect(['action'=>'index']);
            }
            //else by return
            $this->Flash->error(__('Unable to add your message.'));
        }

        //Render the list of messages
        $messages = $this->Paginator->paginate($this->Messages->find());
        $this->set(compact('messages'));

        //Render form for inserting new messages
        $this->set(compact('newMessage'));
    }

    public function messagesJson() {
        //variable in case the user makes a new message
        $newMessage = $this->Messages->newEntity();

        $this->autoRender = false;
        $this->request->allowMethod('ajax');

        $data = array(
            'content' => $newMessage,
            'error' => null
        );

        $this->set(compact($data));
        $this->set('_serialize', 'data');

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