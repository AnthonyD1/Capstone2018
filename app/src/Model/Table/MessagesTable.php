<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;

class MessagesTable extends Table {
    public function initialize(array $config) {
        $this->addBehavior('Timestamp');
    }

    //TODO: Need to make slug generation better
    public function beforeSave($event, $entity, $options) {
        if($entity->isNew() && !$entity->slug) {
            $sluggedBody = Text::slug($entity->body);
            //trim to max length for db
            $entity->slug = substr($sluggedBody, 0, 191);
        }
    }
}