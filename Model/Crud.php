<?php
class Crud extends AppModel
{

    public $actsAs = array('Containable');

    public $belongsTo = array(
        'CrudStatus' => array(
            'foreignKey' => 'crudStatusId'
        ),
        'CrudCondition' => array(
            'foreignKey'  => 'crudConditionId'
        )
    );

    public $hasMany = array(
        'CrudUpload',
        'CrudBeneficiary'
        );

    // public function paginate()
    
}
