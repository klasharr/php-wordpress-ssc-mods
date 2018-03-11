<?php

namespace SSCMods\Fields;

interface FieldValidator {

	
	public function validate($value);
	
	public function getMessage();

}