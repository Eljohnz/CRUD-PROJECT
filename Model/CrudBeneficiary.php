<?php
class CrudBeneficiary extends AppModel {

    public $actsAs = array('Containable');

    public $belongsTo = ['Crud'];
} 