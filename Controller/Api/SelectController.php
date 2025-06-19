<?php
class SelectController extends AppController
{

  public $layout = null;

  public $uses = array(

    'Session',

    'Role',

    'Permission',

    'CrudStatus',
    'CrudCondition'

  );

  public function beforeFilter()
  {
    parent::beforeFilter();
    $this->RequestHandler->ext = 'json';
  }

  public function index($arr = array())
  {

    $datas = array();

    $code  = null;

    if (isset($this->request->query['code']))
      $code = $this->request->query['code'];

    if ($code == 'session') {

      $datas = $this->Session->read('Auth.User');
    } elseif ($code == 'roles') {

      $sessionRoleId = $this->Session->read('Auth.User.roleId');

      $conditions = array();

      $conditions['Role.id !='] = 2;

      $tmp = $this->Role->find('all', array(

        'conditions' => $conditions,

        'conditions' => array('not' => array('Role.code' => array('superadmin'))),

        'order' => array(

          'Role.id' => 'ASC'

        )

      ));

      if (!empty($tmp)) {

        foreach ($tmp as $k => $data) {

          $datas[] = array(

            'id' => strval($data['Role']['id']),

            'value' => properCase($data['Role']['name'])

          );
        }
      }
    } else if ($code == 'permissions') {

      $conditions = array();

      $conditions['Permission.visible'] = true;

      $tmp = $this->Permission->find('all', array(

        'conditions' => $conditions,

        'order' => array(

          'Permission.id' => 'ASC'

        )

      ));

      if (!empty($tmp)) {

        foreach ($tmp as $k => $data) {

          $datas[] = array(

            'id'     => $data['Permission']['id'],

            'value'  => $data['Permission']['module'] . ' (' . $data['Permission']['action'] . ')'

          );
        }
      }

      // crud status
    } elseif ($code == 'crud-status') {
      $tmp = $this->CrudStatus->find('all', array(
        'order' => array(
          'CrudStatus.id' => 'ASC',
        )
      ));
      foreach ($tmp as $k => $data) {
        $datas[] = array(
          'id' => $data['CrudStatus']['id'],
          'value' => properCase($data['CrudStatus']['name']),
          'visible'=> $data['CrudStatus']['visible']
        );
      }
    } elseif ($code == 'crud-condition') {
      $tmp = $this->CrudCondition->find('all', array(
        'order' => array(
          'CrudCondition.name' => 'ASC',
        )
      ));
      foreach ($tmp as $k => $data) {
        $datas[] = array(
          'id' => $data['CrudCondition']['id'],
          'value' => properCase($data['CrudCondition']['name'])
        );
      }
    } else {

      $datas = array();
    }

    $response = array(

      'ok'          => true,

      'roleId'      => session('roleId'),

      'data'        => $datas

    );

    $this->set(array(

      'response'   => $response,

      '_serialize' => 'response',

    ));
  }
}
