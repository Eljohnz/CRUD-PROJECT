<?php
class CrudCondition extends AppModel {

    public $actsAs = array('Containable');

    public $hasOne = array(
        'Crud' => array(
            'foreignKey' => 'crudConditionId'
        )
    );
}