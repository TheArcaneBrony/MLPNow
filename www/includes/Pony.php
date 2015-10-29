<?php

class Pony {
	private function getQuery($for){
		return "Select $for From `ponies` Order By `longName` ASC";
	}
	public function shortNames(){
		global $Database;
		$q = $Database->query($this->getQuery('shortName'));
		$internalArray = array();
		foreach ($q as $c) $internalArray[] = $c['shortName'];
		return $internalArray;
	}
	public function longNames(){
		global $Database;
		$q = $Database->query($this->getQuery('longName'));
		$internalArray = array();
		foreach ($q as $c) $internalArray[] = $c['longName'];
		return $internalArray;
	}
	public function colors(){
		global $Database;
		$q = $Database->query($this->getQuery('color'));
		$internalArray = array();
		foreach ($q as $c) $internalArray[] = $c['color'];
		return $internalArray;
	}
	public function get($arr){
		global $Database;
		foreach ($arr as $k => $v) $Database->where($k,$v);
		return $Database->get('ponies');
	}
	public function data(){
		global $Database;
		return $Database->query($this->getQuery('*'));
	}
}