<?php
	define('PREPEND_PATH', '');
	include_once(__DIR__ . '/lib.php');

	// accept a record as an assoc array, return transformed row ready to insert to table
	$transformFunctions = [
		'gestion' => function($data, $options = []) {

			return $data;
		},
		'lideresgestion' => function($data, $options = []) {
			if(isset($data['CODGESTION'])) $data['CODGESTION'] = pkGivenLookupText($data['CODGESTION'], 'lideresgestion', 'CODGESTION');
			if(isset($data['CEDULA'])) $data['CEDULA'] = pkGivenLookupText($data['CEDULA'], 'lideresgestion', 'CEDULA');

			return $data;
		},
		'lideres' => function($data, $options = []) {
			if(isset($data['PUESTO'])) $data['PUESTO'] = pkGivenLookupText($data['PUESTO'], 'lideres', 'PUESTO');

			return $data;
		},
		'amigos' => function($data, $options = []) {
			if(isset($data['LIDER'])) $data['LIDER'] = pkGivenLookupText($data['LIDER'], 'amigos', 'LIDER');
			if(isset($data['PUESTO'])) $data['PUESTO'] = pkGivenLookupText($data['PUESTO'], 'amigos', 'PUESTO');

			return $data;
		},
		'divpol2022' => function($data, $options = []) {

			return $data;
		},
		'municipios' => function($data, $options = []) {
			if(isset($data['dd'])) $data['dd'] = pkGivenLookupText($data['dd'], 'municipios', 'dd');

			return $data;
		},
		'departamentos' => function($data, $options = []) {

			return $data;
		},
	];

	// accept a record as an assoc array, return a boolean indicating whether to import or skip record
	$filterFunctions = [
		'gestion' => function($data, $options = []) { return true; },
		'lideresgestion' => function($data, $options = []) { return true; },
		'lideres' => function($data, $options = []) { return true; },
		'amigos' => function($data, $options = []) { return true; },
		'divpol2022' => function($data, $options = []) { return true; },
		'municipios' => function($data, $options = []) { return true; },
		'departamentos' => function($data, $options = []) { return true; },
	];

	/*
	Hook file for overwriting/amending $transformFunctions and $filterFunctions:
	hooks/import-csv.php
	If found, it's included below

	The way this works is by either completely overwriting any of the above 2 arrays,
	or, more commonly, overwriting a single function, for example:
		$transformFunctions['tablename'] = function($data, $options = []) {
			// new definition here
			// then you must return transformed data
			return $data;
		};

	Another scenario is transforming a specific field and leaving other fields to the default
	transformation. One possible way of doing this is to store the original transformation function
	in GLOBALS array, calling it inside the custom transformation function, then modifying the
	specific field:
		$GLOBALS['originalTransformationFunction'] = $transformFunctions['tablename'];
		$transformFunctions['tablename'] = function($data, $options = []) {
			$data = call_user_func_array($GLOBALS['originalTransformationFunction'], [$data, $options]);
			$data['fieldname'] = 'transformed value';
			return $data;
		};
	*/

	@include(__DIR__ . '/hooks/import-csv.php');

	$ui = new CSVImportUI($transformFunctions, $filterFunctions);
