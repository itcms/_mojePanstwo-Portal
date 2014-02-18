<?php

class PowiadomieniaController extends PowiadomieniaAppController
{
    public $components = array(
        'RequestHandler', 'Paginator'
    );

    public $uses = array('Powiadomienia.Dataobject');

    public $paginate = array(
        'limit' => 20,
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        if (!$this->Auth->loggedIn() && $this->params->action != 'permissions') {
            $this->redirect(array('action' => 'permissions'));
        }
    }

    public function permissions()
    {

    }

    public function index()
    {

        if (@$this->request->params['ext'] == 'json') {

            // FETCHING OBJECTS

            $this->Paginator->settings = array(
                'conditions' => array(
                    'keyword' => isset($this->request->query['keyword']) ? $this->request->query['keyword'] : false,
                    'mode' => isset($this->request->query['mode']) ? $this->request->query['mode'] : false,
                ),
                'limit' => 20,
                'paramType' => 'querystring',
            );

            $objects = $this->Paginator->paginate('Dataobject');
            $pagination = @$this->request->params['paging']['Dataobject'];

            $view = new View($this, false);
            $view->set(compact('objects')); // set variables


            $html = '';
            if (!empty($objects)) {
                $view = new View($this, false);
                $html = $view->element('objects', array(
                    'objects' => $objects,
                ));
            }

            $this->set('html', $html);
            $this->set('pagination', $pagination);
            $this->set('_serialize', array('html', 'pagination'));


        } else {

            // FETCHING PHRASES

            $phrases = $this->API->getPhrases();
            $this->set('phrases', $phrases);


            // FETCHING OBJECTS

            $this->Paginator->settings = array(
                'conditions' => array(
                    'keyword' => isset($this->request->query['keyword']) ? $this->request->query['keyword'] : false,
                    'mode' => isset($this->request->query['mode']) ? $this->request->query['mode'] : false,
                ),
                'limit' => 20,
                'paramType' => 'querystring',
            );

            $objects = $this->Paginator->paginate('Dataobject');
            $this->set('objects', $objects);

        }

    }

    public function flagObjects()
    {

        $object_id = (int)@$this->request->query['id'];

        if ($object_id && $this->Session->read('Auth.User.id'))
            $this->API->Powiadomienia()->flagObject($object_id);

        $this->set('status', 'OK');
        $this->set('_serialize', array('status'));

    }

}