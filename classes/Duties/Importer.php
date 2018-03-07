<?php

namespace SSCMod\Duties;

class Importer {

	private $filename;

	private $raw_content = array();

	private $safety_teams = array();

	public function __construct($filename){

		if(! file_exists( $filename)){
			throw new Exception( 'File '. (string) $filename .' does not exist');
		}

		$this->filename = $filename;

	}

	/**
	 * @throws Exception
	 */
	public function readContentFromFileToArray(){

		if(!empty($this->raw_content)) {
			return;
		}

		if(! $o = file($this->filename) ) {
			throw new Exception( 'Could not get file contents.');
		}

		$this->raw_content = $o;
	}


	/**
	 * @return array
	 * @throws Exception
	 */
	public function getRawData(){
		$this->readContentFromFileToArray();
		return $this->raw_content;

	}

	public function setTeamMembers(){

		$this->readContentFromFileToArray();

		$this->safety_teams = array();

		foreach($this->raw_content as $a ){
			print_r($a);
		}

	}

}