<?php

class Collection extends AppModel {

    public $useDbConfig = 'mpAPI';
	
	public function load($id) {
        
        $res = $this->getDataSource()->request('collections/collections/' . $id, array(
            'method' => 'GET',
        ));
        
        if( $res ) {
		
			require_once(APPLIBS . 'Collection.php');
			return new MP\Lib\Collection($res);		
		
		} else return false;

	}
	
    public function get($id) {
        return $this->getDataSource()->request('collections/collections/get/' . $id, array(
            'method' => 'GET'
        ));
    }

    public function publish($id) {
        return $this->getDataSource()->request('collections/collections/publish/' . $id, array(
            'method' => 'GET'
        ));
    }

    public function unpublish($id) {
        return $this->getDataSource()->request('collections/collections/unpublish/' . $id, array(
            'method' => 'GET'
        ));
    }

    public function create($data) {
        return $this->getDataSource()->request('collections/collections/create', array(
            'data' => $data,
            'method' => 'POST'
        ));
    }

    public function edit($id, $data) {
        return $this->getDataSource()->request('collections/collections/edit/' . $id, array(
            'data' => $data,
            'method' => 'POST'
        ));
    }

    public function addObject($id, $object_id) {
        return $this->getDataSource()->request('collections/collections/addObject/' . $id . '/' . $object_id, array(
            'method' => 'GET'
        ));
    }

    public function addObjectData($data) {
        return $this->getDataSource()->request('collections/collections/addObjectData', array(
            'data' => $data,
            'method' => 'POST'
        ));
    }
    
    public function editObject($id, $object_id, $data) {
        return $this->getDataSource()->request('collections/collections/editObject/' . $id . '/' . $object_id, array(
            'method' => 'POST',
            'data' => $data,
        ));
    }

    public function removeObject($id, $object_id) {
        return $this->getDataSource()->request('collections/collections/removeObject/' . $id . '/' . $object_id, array(
            'method' => 'GET'
        ));
    }

    public function removeObjects($id, $data) {
        return $this->getDataSource()->request('collections/collections/removeObjects/' . $id , array(
            'data' => $data,
            'method' => 'POST'
        ));
    }

    public function delete($id) {
        return $this->getDataSource()->request('collections/collections/delete/' . $id, array(
            'method' => 'GET'
        ));
    }

}
