<?php

namespace App\Repositories\Interface;

interface ProductSpecificationRepositoryInterface
{
 public function index();
 public function show($models);
 public function store($request);
 public function getKeysByCategoryId($categoryId);
 public function delete($id);
 public function getOnlyPublicKey();
 public function updatestatus($id);
 public function updateIsPublic($id);
 public function updateposition($request,$id);
 public function indexview($data);
 public function getTypeById($id);

// Types
 public function typesindex();
 public function typesshow($models);
 public function typesstore($request);
 public function typesdelete($id);
 public function typesupdatestatus($id);
 public function typesfilterstatus($id);
 public function typesupdateposition($request,$id);
 public function typesindexview($data);


//  Attribbutes
public function attributeindex();
public function attributeshow($models);
public function attributesstore($request);
public function attributedelete($id);
public function attributeupdatestatus($id);
public function attributeupdate($request,$id);
public function attributeindexview($data);
public function getAttributesById($id);

//Get Datas
public function keys($id);
public function types($id);
public function attributes($id);
public function allKeysIncludingParent($ids);

}