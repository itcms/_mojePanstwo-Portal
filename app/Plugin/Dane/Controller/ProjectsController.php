<?php

class ProjectsController extends AppController {

    public $uses = array('Dane.Dataobject');
    public $components = array('RequestHandler');

    public function index() {
        $dzialania = $this->getResponse('GET');
        $this->setResponse('dzialania', $dzialania);
    }

    public function add() {
        $success = $this->getResponse('POST');
        $this->setResponse('success', $success);
    }

    public function view() {
        $dzialanie = $this->getResponse('GET');
        $this->setResponse('dzialanie', $dzialanie);
    }

    public function edit() {
        $success = $this->getResponse('PUT');
        $this->setResponse('success', $success);
    }

    public function delete() {
        $success = $this->getResponse('DELETE');
        $this->setResponse('success', $success);
    }

    /**
     * @desc Autocomplete `tematy`.`q`
     */
    public function tematy() {
        $response = $this->Dataobject->getDatasource()->request(
            'dane/tematy.json',
            array(
                'method' => 'GET',
                'data' => $this->request->query
            )
        );

        $values = array();
        foreach($response as $temat)
            $values[] = (object) array(
                'value' => $temat['id'],
                'label' => $temat['q']
            );

        $this->autoRender = false;

        return json_encode($values);
    }

    private function getResponse($method) {
        return $this->Dataobject->getDatasource()->request(
            'dane/' . $this->request['dataset'] . '/' . $this->request['object_id'] . '/pages/dzialania' .
            ( isset($this->request['id']) ? '/' . $this->request['id'] : '' ) .
            '.json',
            array(
                'method' => $method,
                'data' => $this->request->data,
            )
        );
    }

    private function setResponse($name, $value) {
        $this->set($name, $value);
        $this->set('_serialize', array($name));
    }

}