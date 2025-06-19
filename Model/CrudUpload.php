<?php
class CrudUpload extends AppModel {
    public $actsAs = array('Containable');

    public $belongsTo = 'Crud';

    public $useTable = 'crud_upload';
}
