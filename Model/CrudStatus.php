<?php
class CrudStatus extends AppModel {

    // public $actsAs = array('Containable');

    public $hasOne = array(
        'Crud' => array(
            'foreignKey' => 'crudStatusId'
        )
    );
}