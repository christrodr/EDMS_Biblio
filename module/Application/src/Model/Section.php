<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

class Section {

    public $id;
    public $nom;
    public $archiver;

    public function exchangeArray(array $data) {
	$this->id = !empty($data['id_Section']) ? $data['id_Section'] : null;
	$this->nom = !empty($data['nom']) ? $data['nom'] : null;
	$this->archiver = !empty($data['archiver']) ? $data['archiver'] : null;
    }

}
