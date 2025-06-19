<?php

App::uses('AppController', 'Controller');

class StatusController extends AppController
{
    public $components = array('Paginator', 'RequestHandler',);

    public function beforeFilter()
    {

        parent::beforeFilter();

        $this->RequestHandler->ext = 'json';
    }

    public $uses = ['CrudStatus'];

    public function index()
    {

        $page = isset($this->request->query['page']) ? $this->request->query['page'] : 1;
        $conditions = array();


        if (isset($this->request->query['search'])) {

            $search = $this->request->query['search'];

            $search = strtolower($search);

            $conditions['CrudStatus.name LIKE'] = '%' . $search .  '%';
            // $conditions['Crud.email LIKE'] = '%' . $search .  '%'; FIX
        }


        $conditions['CrudStatus.visible'] = true;

        $paginatorSettings = array(
            'conditions' => $conditions,
            'limit' => 5,
            'page' => $page,
            "order" => array(
                'CrudStatus.name' => 'ASC'
            ),
            'contain' => 'CrudStatus'
        );

        $modelName = "CrudStatus";
        $this->Paginator->settings = $paginatorSettings;
        $tmpData = $this->Paginator->paginate($modelName);
        $paginator = $this->request->params['paging'][$modelName];



        $cruds_ = array();
        foreach ($tmpData as $crud) {
            $cruds_[] = array(
                'id'            => $crud['CrudStatus']['id'],
                'name'          => properCase($crud['CrudStatus']['name']),
                'visible'       => $crud['CrudStatus']['visible'],
                'status'        => isset($crud['CrudStatus']['name']) ? $crud['CrudStatus']['name'] : 'Unknown',
            );
        }

        $response = array(
            'ok' => true,
            'msg' => 'index',
            'data' => $cruds_,
            'paginator' => $paginator,
        );

        $this->set(array(
            'response' => $response,
            '_serialize' => 'response'
        ));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            if ($this->CrudStatus->save($this->request->data)) {
                $response = array(
                    'ok' => true,
                    'msg' => 'Status saved!',
                    'data' => $this->CrudStatus->findById($this->CrudStatus->id)
                );
            } else {
                $response = array(
                    'ok' => false,
                    'msg' => 'Not Saved!',
                    'data' => $this->request->data,
                );
            }
            $this->set(array(
                'response' => $response,
                '_serialize' => 'response'
            ));
        }
    }

    public function view($id = null)
    {
        $data = $this->CrudStatus->find('first', array(
            'conditions' => array(
                'CrudStatus.id' => $id,
                'CrudStatus.visible' => true
            ),
        ));

        $response = array(
            'ok' => true,
            'msg' => 'Viewed!',
            'data' => $data,
        );

        $this->set(array(
            'response' => $response,
            '_serialize' => 'response'
        ));
    }

    public function edit($id = null)
    {

        $data = $this->request->data;

        // if (!isset($data['id']) && $id !== null) {
        //     $data['id'] = $id;
        // }

        if ($this->CrudStatus->save($data)) {
            $response = array(
                'ok' => true,
                'msg' => 'Updated!',
                'data' => $data,
            );
        } else {
            $response = array(
                'ok' => false,
                'msg' => 'Not Updated!',
                'data' => $data,
            );
        }

        $this->set(array(
            'response' => $response,
            '_serialize' => 'response'
        ));
    }

    public function delete($id = null)
    {

        if ($this->CrudStatus->hide($id)) {

            $response = array(

                'ok' => true,

                'msg' => 'deleted.',

                'data' => $this->request->data,

            );
        } else {

            $response = array(

                'ok' => false,

                'msg' => 'not deleted.',

                'data' => $this->request->data,

            );
        }

        $this->set(array(

            'response' => $response,

            '_serialize' => 'response'

        ));
    }
}
