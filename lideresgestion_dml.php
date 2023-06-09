<?php

// Data functions (insert, update, delete, form) for table lideresgestion

// This script and data application were generated by AppGini 23.11
// Download AppGini for free from https://bigprof.com/appgini/download/

function lideresgestion_insert(&$error_message = '') {
	global $Translation;

	// mm: can member insert record?
	$arrPerm = getTablePermissions('lideresgestion');
	if(!$arrPerm['insert']) return false;

	$data = [
		'HORA' => parseCode('<%%creationDateTime%%>', true, true),
		'CODGESTION' => Request::lookup('CODGESTION', ''),
		'CEDULA' => Request::lookup('CEDULA', ''),
		'CELULAR' => Request::lookup('CELULAR', ''),
		'OBSERVACIONES' => br2nl(Request::val('OBSERVACIONES', '')),
	];

	if($data['CODGESTION'] === '') {
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'CODGESTION': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">' . $Translation['< back'] . '</a></div>';
		exit;
	}

	// hook: lideresgestion_before_insert
	if(function_exists('lideresgestion_before_insert')) {
		$args = [];
		if(!lideresgestion_before_insert($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$error = '';
	// set empty fields to NULL
	$data = array_map(function($v) { return ($v === '' ? NULL : $v); }, $data);
	insert('lideresgestion', backtick_keys_once($data), $error);
	if($error) {
		$error_message = $error;
		return false;
	}

	$recID = db_insert_id(db_link());

	update_calc_fields('lideresgestion', $recID, calculated_fields()['lideresgestion']);

	// hook: lideresgestion_after_insert
	if(function_exists('lideresgestion_after_insert')) {
		$res = sql("SELECT * FROM `lideresgestion` WHERE `LLAVE`='" . makeSafe($recID, false) . "' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)) {
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args = [];
		if(!lideresgestion_after_insert($data, getMemberInfo(), $args)) { return $recID; }
	}

	// mm: save ownership data
	set_record_owner('lideresgestion', $recID, getLoggedMemberID());

	// if this record is a copy of another record, copy children if applicable
	if(strlen(Request::val('SelectedID'))) lideresgestion_copy_children($recID, Request::val('SelectedID'));

	return $recID;
}

function lideresgestion_copy_children($destination_id, $source_id) {
	global $Translation;
	$requests = []; // array of curl handlers for launching insert requests
	$eo = ['silentErrors' => true];
	$safe_sid = makeSafe($source_id);

	// launch requests, asynchronously
	curl_batch($requests);
}

function lideresgestion_delete($selected_id, $AllowDeleteOfParents = false, $skipChecks = false) {
	// insure referential integrity ...
	global $Translation;
	$selected_id = makeSafe($selected_id);

	// mm: can member delete record?
	if(!check_record_permission('lideresgestion', $selected_id, 'delete')) {
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: lideresgestion_before_delete
	if(function_exists('lideresgestion_before_delete')) {
		$args = [];
		if(!lideresgestion_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'] . (
				!empty($args['error_message']) ?
					'<div class="text-bold">' . strip_tags($args['error_message']) . '</div>'
					: '' 
			);
	}

	sql("DELETE FROM `lideresgestion` WHERE `LLAVE`='{$selected_id}'", $eo);

	// hook: lideresgestion_after_delete
	if(function_exists('lideresgestion_after_delete')) {
		$args = [];
		lideresgestion_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("DELETE FROM `membership_userrecords` WHERE `tableName`='lideresgestion' AND `pkValue`='{$selected_id}'", $eo);
}

function lideresgestion_update(&$selected_id, &$error_message = '') {
	global $Translation;

	// mm: can member edit record?
	if(!check_record_permission('lideresgestion', $selected_id, 'edit')) return false;

	$data = [
		'CODGESTION' => Request::lookup('CODGESTION', ''),
		'CEDULA' => Request::lookup('CEDULA', ''),
		'CELULAR' => Request::lookup('CELULAR', ''),
		'OBSERVACIONES' => br2nl(Request::val('OBSERVACIONES', '')),
	];

	if($data['CODGESTION'] === '') {
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'CODGESTION': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">' . $Translation['< back'] . '</a></div>';
		exit;
	}
	// get existing values
	$old_data = getRecord('lideresgestion', $selected_id);
	if(is_array($old_data)) {
		$old_data = array_map('makeSafe', $old_data);
		$old_data['selectedID'] = makeSafe($selected_id);
	}

	$data['selectedID'] = makeSafe($selected_id);

	// hook: lideresgestion_before_update
	if(function_exists('lideresgestion_before_update')) {
		$args = ['old_data' => $old_data];
		if(!lideresgestion_before_update($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$set = $data; unset($set['selectedID']);
	foreach ($set as $field => $value) {
		$set[$field] = ($value !== '' && $value !== NULL) ? $value : NULL;
	}

	if(!update(
		'lideresgestion', 
		backtick_keys_once($set), 
		['`LLAVE`' => $selected_id], 
		$error_message
	)) {
		echo $error_message;
		echo '<a href="lideresgestion_view.php?SelectedID=' . urlencode($selected_id) . "\">{$Translation['< back']}</a>";
		exit;
	}


	$eo = ['silentErrors' => true];

	update_calc_fields('lideresgestion', $data['selectedID'], calculated_fields()['lideresgestion']);

	// hook: lideresgestion_after_update
	if(function_exists('lideresgestion_after_update')) {
		$res = sql("SELECT * FROM `lideresgestion` WHERE `LLAVE`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)) $data = array_map('makeSafe', $row);

		$data['selectedID'] = $data['LLAVE'];
		$args = ['old_data' => $old_data];
		if(!lideresgestion_after_update($data, getMemberInfo(), $args)) return;
	}

	// mm: update ownership data
	sql("UPDATE `membership_userrecords` SET `dateUpdated`='" . time() . "' WHERE `tableName`='lideresgestion' AND `pkValue`='" . makeSafe($selected_id) . "'", $eo);
}

function lideresgestion_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $separateDV = 0, $TemplateDV = '', $TemplateDVP = '') {
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;
	$eo = ['silentErrors' => true];
	$noUploads = null;
	$row = $urow = $jsReadOnly = $jsEditable = $lookups = null;

	$noSaveAsCopy = false;

	// mm: get table permissions
	$arrPerm = getTablePermissions('lideresgestion');
	if(!$arrPerm['insert'] && $selected_id == '')
		// no insert permission and no record selected
		// so show access denied error unless TVDV
		return $separateDV ? $Translation['tableAccessDenied'] : '';
	$AllowInsert = ($arrPerm['insert'] ? true : false);
	// print preview?
	$dvprint = false;
	if(strlen($selected_id) && Request::val('dvprint_x') != '') {
		$dvprint = true;
	}

	$filterer_CODGESTION = Request::val('filterer_CODGESTION');
	$filterer_CEDULA = Request::val('filterer_CEDULA');
	$filterer_CELULAR = Request::val('filterer_CELULAR');

	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: CODGESTION
	$combo_CODGESTION = new DataCombo;
	// combobox: CEDULA
	$combo_CEDULA = new DataCombo;
	// combobox: CELULAR
	$combo_CELULAR = new DataCombo;
	// combobox: ESTADO
	$combo_ESTADO = new Combo;
	$combo_ESTADO->ListType = 0;
	$combo_ESTADO->MultipleSeparator = ', ';
	$combo_ESTADO->ListBoxHeight = 10;
	$combo_ESTADO->RadiosPerLine = 1;
	if(is_file(__DIR__ . '/hooks/lideresgestion.ESTADO.csv')) {
		$ESTADO_data = addslashes(implode('', @file(__DIR__ . '/hooks/lideresgestion.ESTADO.csv')));
		$combo_ESTADO->ListItem = array_trim(explode('||', entitiesToUTF8(convertLegacyOptions($ESTADO_data))));
		$combo_ESTADO->ListData = $combo_ESTADO->ListItem;
	} else {
		$combo_ESTADO->ListItem = array_trim(explode('||', entitiesToUTF8(convertLegacyOptions("NO CONTESTA;;CONTACTADO;;ACTIVO;;REPORTA PROBLEMAS;;CIERRE"))));
		$combo_ESTADO->ListData = $combo_ESTADO->ListItem;
	}
	$combo_ESTADO->SelectName = 'ESTADO';

	if($selected_id) {
		if(!check_record_permission('lideresgestion', $selected_id, 'view'))
			return $Translation['tableAccessDenied'];

		// can edit?
		$AllowUpdate = check_record_permission('lideresgestion', $selected_id, 'edit');

		// can delete?
		$AllowDelete = check_record_permission('lideresgestion', $selected_id, 'delete');

		$res = sql("SELECT * FROM `lideresgestion` WHERE `LLAVE`='" . makeSafe($selected_id) . "'", $eo);
		if(!($row = db_fetch_array($res))) {
			return error_message($Translation['No records found'], 'lideresgestion_view.php', false);
		}
		$combo_CODGESTION->SelectedData = $row['CODGESTION'];
		$combo_CEDULA->SelectedData = $row['CEDULA'];
		$combo_CELULAR->SelectedData = $row['CELULAR'];
		$combo_ESTADO->SelectedData = $row['ESTADO'];
		$urow = $row; /* unsanitized data */
		$row = array_map('safe_html', $row);
	} else {
		$filterField = Request::val('FilterField');
		$filterOperator = Request::val('FilterOperator');
		$filterValue = Request::val('FilterValue');
		$combo_CODGESTION->SelectedData = $filterer_CODGESTION;
		$combo_CEDULA->SelectedData = $filterer_CEDULA;
		$combo_CELULAR->SelectedData = $filterer_CELULAR;
		$combo_ESTADO->SelectedText = (isset($filterField[1]) && $filterField[1] == '7' && $filterOperator[1] == '<=>' ? $filterValue[1] : 'INGRESADO');
	}
	$combo_CODGESTION->HTML = '<span id="CODGESTION-container' . $rnd1 . '"></span><input type="hidden" name="CODGESTION" id="CODGESTION' . $rnd1 . '" value="' . html_attr($combo_CODGESTION->SelectedData) . '">';
	$combo_CODGESTION->MatchText = '<span id="CODGESTION-container-readonly' . $rnd1 . '"></span><input type="hidden" name="CODGESTION" id="CODGESTION' . $rnd1 . '" value="' . html_attr($combo_CODGESTION->SelectedData) . '">';
	$combo_CEDULA->HTML = '<span id="CEDULA-container' . $rnd1 . '"></span><input type="hidden" name="CEDULA" id="CEDULA' . $rnd1 . '" value="' . html_attr($combo_CEDULA->SelectedData) . '">';
	$combo_CEDULA->MatchText = '<span id="CEDULA-container-readonly' . $rnd1 . '"></span><input type="hidden" name="CEDULA" id="CEDULA' . $rnd1 . '" value="' . html_attr($combo_CEDULA->SelectedData) . '">';
	$combo_CELULAR->HTML = '<span id="CELULAR-container' . $rnd1 . '"></span><input type="hidden" name="CELULAR" id="CELULAR' . $rnd1 . '" value="' . html_attr($combo_CELULAR->SelectedData) . '">';
	$combo_CELULAR->MatchText = '<span id="CELULAR-container-readonly' . $rnd1 . '"></span><input type="hidden" name="CELULAR" id="CELULAR' . $rnd1 . '" value="' . html_attr($combo_CELULAR->SelectedData) . '">';
	$combo_ESTADO->Render();

	ob_start();
	?>

	<script>
		// initial lookup values
		AppGini.current_CODGESTION__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['CODGESTION'] : htmlspecialchars($filterer_CODGESTION, ENT_QUOTES)); ?>"};
		AppGini.current_CEDULA__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['CEDULA'] : htmlspecialchars($filterer_CEDULA, ENT_QUOTES)); ?>"};
		AppGini.current_CELULAR__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['CELULAR'] : htmlspecialchars($filterer_CELULAR, ENT_QUOTES)); ?>"};

		jQuery(function() {
			setTimeout(function() {
				if(typeof(CODGESTION_reload__RAND__) == 'function') CODGESTION_reload__RAND__();
				if(typeof(CEDULA_reload__RAND__) == 'function') CEDULA_reload__RAND__();
				if(typeof(CELULAR_reload__RAND__) == 'function') CELULAR_reload__RAND__();
			}, 50); /* we need to slightly delay client-side execution of the above code to allow AppGini.ajaxCache to work */
		});
		function CODGESTION_reload__RAND__() {
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint) { ?>

			$j("#CODGESTION-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c) {
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_CODGESTION__RAND__.value, t: 'lideresgestion', f: 'CODGESTION' },
						success: function(resp) {
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="CODGESTION"]').val(resp.results[0].id);
							$j('[id=CODGESTION-container-readonly__RAND__]').html('<span class="match-text" id="CODGESTION-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=gestion_view_parent]').hide(); } else { $j('.btn[id=gestion_view_parent]').show(); }


							if(typeof(CODGESTION_update_autofills__RAND__) == 'function') CODGESTION_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term) { return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) { return { s: term, p: page, t: 'lideresgestion', f: 'CODGESTION' }; },
					results: function(resp, page) { return resp; }
				},
				escapeMarkup: function(str) { return str; }
			}).on('change', function(e) {
				AppGini.current_CODGESTION__RAND__.value = e.added.id;
				AppGini.current_CODGESTION__RAND__.text = e.added.text;
				$j('[name="CODGESTION"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=gestion_view_parent]').hide(); } else { $j('.btn[id=gestion_view_parent]').show(); }


				if(typeof(CODGESTION_update_autofills__RAND__) == 'function') CODGESTION_update_autofills__RAND__();
			});

			if(!$j("#CODGESTION-container__RAND__").length) {
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_CODGESTION__RAND__.value, t: 'lideresgestion', f: 'CODGESTION' },
					success: function(resp) {
						$j('[name="CODGESTION"]').val(resp.results[0].id);
						$j('[id=CODGESTION-container-readonly__RAND__]').html('<span class="match-text" id="CODGESTION-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=gestion_view_parent]').hide(); } else { $j('.btn[id=gestion_view_parent]').show(); }

						if(typeof(CODGESTION_update_autofills__RAND__) == 'function') CODGESTION_update_autofills__RAND__();
					}
				});
			}

		<?php } else { ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_CODGESTION__RAND__.value, t: 'lideresgestion', f: 'CODGESTION' },
				success: function(resp) {
					$j('[id=CODGESTION-container__RAND__], [id=CODGESTION-container-readonly__RAND__]').html('<span class="match-text" id="CODGESTION-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=gestion_view_parent]').hide(); } else { $j('.btn[id=gestion_view_parent]').show(); }

					if(typeof(CODGESTION_update_autofills__RAND__) == 'function') CODGESTION_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function CEDULA_reload__RAND__() {
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint) { ?>

			$j("#CEDULA-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c) {
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_CEDULA__RAND__.value, t: 'lideresgestion', f: 'CEDULA' },
						success: function(resp) {
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="CEDULA"]').val(resp.results[0].id);
							$j('[id=CEDULA-container-readonly__RAND__]').html('<span class="match-text" id="CEDULA-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=lideres_view_parent]').hide(); } else { $j('.btn[id=lideres_view_parent]').show(); }


							if(typeof(CEDULA_update_autofills__RAND__) == 'function') CEDULA_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term) { return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) { return { s: term, p: page, t: 'lideresgestion', f: 'CEDULA' }; },
					results: function(resp, page) { return resp; }
				},
				escapeMarkup: function(str) { return str; }
			}).on('change', function(e) {
				AppGini.current_CEDULA__RAND__.value = e.added.id;
				AppGini.current_CEDULA__RAND__.text = e.added.text;
				$j('[name="CEDULA"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=lideres_view_parent]').hide(); } else { $j('.btn[id=lideres_view_parent]').show(); }


				if(typeof(CEDULA_update_autofills__RAND__) == 'function') CEDULA_update_autofills__RAND__();
			});

			if(!$j("#CEDULA-container__RAND__").length) {
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_CEDULA__RAND__.value, t: 'lideresgestion', f: 'CEDULA' },
					success: function(resp) {
						$j('[name="CEDULA"]').val(resp.results[0].id);
						$j('[id=CEDULA-container-readonly__RAND__]').html('<span class="match-text" id="CEDULA-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=lideres_view_parent]').hide(); } else { $j('.btn[id=lideres_view_parent]').show(); }

						if(typeof(CEDULA_update_autofills__RAND__) == 'function') CEDULA_update_autofills__RAND__();
					}
				});
			}

		<?php } else { ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_CEDULA__RAND__.value, t: 'lideresgestion', f: 'CEDULA' },
				success: function(resp) {
					$j('[id=CEDULA-container__RAND__], [id=CEDULA-container-readonly__RAND__]').html('<span class="match-text" id="CEDULA-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=lideres_view_parent]').hide(); } else { $j('.btn[id=lideres_view_parent]').show(); }

					if(typeof(CEDULA_update_autofills__RAND__) == 'function') CEDULA_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function CELULAR_reload__RAND__() {
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint) { ?>

			$j("#CELULAR-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c) {
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_CELULAR__RAND__.value, t: 'lideresgestion', f: 'CELULAR' },
						success: function(resp) {
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="CELULAR"]').val(resp.results[0].id);
							$j('[id=CELULAR-container-readonly__RAND__]').html('<span class="match-text" id="CELULAR-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=lideres_view_parent]').hide(); } else { $j('.btn[id=lideres_view_parent]').show(); }


							if(typeof(CELULAR_update_autofills__RAND__) == 'function') CELULAR_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term) { return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) { return { s: term, p: page, t: 'lideresgestion', f: 'CELULAR' }; },
					results: function(resp, page) { return resp; }
				},
				escapeMarkup: function(str) { return str; }
			}).on('change', function(e) {
				AppGini.current_CELULAR__RAND__.value = e.added.id;
				AppGini.current_CELULAR__RAND__.text = e.added.text;
				$j('[name="CELULAR"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=lideres_view_parent]').hide(); } else { $j('.btn[id=lideres_view_parent]').show(); }


				if(typeof(CELULAR_update_autofills__RAND__) == 'function') CELULAR_update_autofills__RAND__();
			});

			if(!$j("#CELULAR-container__RAND__").length) {
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_CELULAR__RAND__.value, t: 'lideresgestion', f: 'CELULAR' },
					success: function(resp) {
						$j('[name="CELULAR"]').val(resp.results[0].id);
						$j('[id=CELULAR-container-readonly__RAND__]').html('<span class="match-text" id="CELULAR-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=lideres_view_parent]').hide(); } else { $j('.btn[id=lideres_view_parent]').show(); }

						if(typeof(CELULAR_update_autofills__RAND__) == 'function') CELULAR_update_autofills__RAND__();
					}
				});
			}

		<?php } else { ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_CELULAR__RAND__.value, t: 'lideresgestion', f: 'CELULAR' },
				success: function(resp) {
					$j('[id=CELULAR-container__RAND__], [id=CELULAR-container-readonly__RAND__]').html('<span class="match-text" id="CELULAR-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=lideres_view_parent]').hide(); } else { $j('.btn[id=lideres_view_parent]').show(); }

					if(typeof(CELULAR_update_autofills__RAND__) == 'function') CELULAR_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_clean());


	// code for template based detail view forms

	// open the detail view template
	if($dvprint) {
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/lideresgestion_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	} else {
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/lideresgestion_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'DATOS BASICOS DEL LIDER', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', (Request::val('Embedded') ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($AllowInsert) {
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return lideresgestion_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return lideresgestion_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
	} else {
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '', $templateCode);
	}

	// 'Back' button action
	if(Request::val('Embedded')) {
		$backAction = 'AppGini.closeParentModal(); return false;';
	} else {
		$backAction = '$j(\'form\').eq(0).attr(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;';
	}

	if($selected_id) {
		if(!Request::val('Embedded')) $templateCode = str_replace('<%%DVPRINT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="dvprint" name="dvprint_x" value="1" onclick="$j(\'form\').eq(0).prop(\'novalidate\', true); document.myform.reset(); return true;" title="' . html_attr($Translation['Print Preview']) . '"><i class="glyphicon glyphicon-print"></i> ' . $Translation['Print Preview'] . '</button>', $templateCode);
		if($AllowUpdate)
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return lideresgestion_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
		else
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);

		if($AllowDelete)
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '<button type="submit" class="btn btn-danger" id="delete" name="delete_x" value="1" title="' . html_attr($Translation['Delete']) . '"><i class="glyphicon glyphicon-trash"></i> ' . $Translation['Delete'] . '</button>', $templateCode);
		else
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);

		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>', $templateCode);
	} else {
		$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);

		// if not in embedded mode and user has insert only but no view/update/delete,
		// remove 'back' button
		if(
			$arrPerm['insert']
			&& !$arrPerm['update'] && !$arrPerm['delete'] && !$arrPerm['view']
			&& !Request::val('Embedded')
		)
			$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '', $templateCode);
		elseif($separateDV)
			$templateCode = str_replace(
				'<%%DESELECT_BUTTON%%>', 
				'<button
					type="submit" 
					class="btn btn-default" 
					id="deselect" 
					name="deselect_x" 
					value="1" 
					onclick="' . $backAction . '" 
					title="' . html_attr($Translation['Back']) . '">
						<i class="glyphicon glyphicon-chevron-left"></i> ' .
						$Translation['Back'] .
				'</button>',
				$templateCode
			);
		else
			$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '', $templateCode);
	}

	// set records to read only if user can't insert new records and can't edit current record
	if(($selected_id && !$AllowUpdate && !$AllowInsert) || (!$selected_id && !$AllowInsert)) {
		$jsReadOnly = '';
		$jsReadOnly .= "\tjQuery('#CODGESTION').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#CODGESTION_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#CEDULA').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#CEDULA_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#CELULAR').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#CELULAR_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#OBSERVACIONES').replaceWith('<div class=\"form-control-static\" id=\"OBSERVACIONES\">' + (jQuery('#OBSERVACIONES').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	} elseif($AllowInsert) {
		$jsEditable = "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode = str_replace('<%%COMBO(CODGESTION)%%>', $combo_CODGESTION->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(CODGESTION)%%>', $combo_CODGESTION->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(CODGESTION)%%>', urlencode($combo_CODGESTION->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(CEDULA)%%>', $combo_CEDULA->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(CEDULA)%%>', $combo_CEDULA->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(CEDULA)%%>', urlencode($combo_CEDULA->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(CELULAR)%%>', $combo_CELULAR->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(CELULAR)%%>', $combo_CELULAR->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(CELULAR)%%>', urlencode($combo_CELULAR->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(ESTADO)%%>', $combo_ESTADO->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(ESTADO)%%>', $combo_ESTADO->SelectedData, $templateCode);

	/* lookup fields array: 'lookup field name' => ['parent table name', 'lookup field caption'] */
	$lookup_fields = ['CODGESTION' => ['gestion', 'CODGESTION'], 'CEDULA' => ['lideres', 'LIDER'], 'CELULAR' => ['lideres', 'CELULAR'], ];
	foreach($lookup_fields as $luf => $ptfc) {
		$pt_perm = getTablePermissions($ptfc[0]);

		// process foreign key links
		if($pt_perm['view'] || $pt_perm['edit']) {
			$templateCode = str_replace("<%%PLINK({$luf})%%>", '<button type="button" class="btn btn-default view_parent" id="' . $ptfc[0] . '_view_parent" title="' . html_attr($Translation['View'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-eye-open"></i></button>', $templateCode);
		}

		// if user has insert permission to parent table of a lookup field, put an add new button
		if($pt_perm['insert'] /* && !Request::val('Embedded')*/) {
			$templateCode = str_replace("<%%ADDNEW({$ptfc[0]})%%>", '<button type="button" class="btn btn-default add_new_parent" id="' . $ptfc[0] . '_add_new" title="' . html_attr($Translation['Add New'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-plus text-success"></i></button>', $templateCode);
		}
	}

	// process images
	$templateCode = str_replace('<%%UPLOADFILE(LLAVE)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(HORA)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(CODGESTION)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(CEDULA)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(CELULAR)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(OBSERVACIONES)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(ESTADO)%%>', '', $templateCode);

	// process values
	if($selected_id) {
		if( $dvprint) $templateCode = str_replace('<%%VALUE(LLAVE)%%>', safe_html($urow['LLAVE']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(LLAVE)%%>', html_attr($row['LLAVE']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(LLAVE)%%>', urlencode($urow['LLAVE']), $templateCode);
		$templateCode = str_replace('<%%VALUE(HORA)%%>', app_datetime($row['HORA'], 'dt'), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(HORA)%%>', urlencode(app_datetime($urow['HORA'], 'dt')), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(CODGESTION)%%>', safe_html($urow['CODGESTION']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(CODGESTION)%%>', html_attr($row['CODGESTION']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(CODGESTION)%%>', urlencode($urow['CODGESTION']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(CEDULA)%%>', safe_html($urow['CEDULA']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(CEDULA)%%>', html_attr($row['CEDULA']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(CEDULA)%%>', urlencode($urow['CEDULA']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(CELULAR)%%>', safe_html($urow['CELULAR']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(CELULAR)%%>', html_attr($row['CELULAR']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(CELULAR)%%>', urlencode($urow['CELULAR']), $templateCode);
		if($dvprint || (!$AllowUpdate && !$AllowInsert)) {
			$templateCode = str_replace('<%%VALUE(OBSERVACIONES)%%>', safe_html($urow['OBSERVACIONES']), $templateCode);
		} else {
			$templateCode = str_replace('<%%VALUE(OBSERVACIONES)%%>', safe_html($urow['OBSERVACIONES'], true), $templateCode);
		}
		$templateCode = str_replace('<%%URLVALUE(OBSERVACIONES)%%>', urlencode($urow['OBSERVACIONES']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(ESTADO)%%>', safe_html($urow['ESTADO']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(ESTADO)%%>', html_attr($row['ESTADO']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(ESTADO)%%>', urlencode($urow['ESTADO']), $templateCode);
	} else {
		$templateCode = str_replace('<%%VALUE(LLAVE)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(LLAVE)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(HORA)%%>', '<%%creationDateTime%%>', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(HORA)%%>', urlencode('<%%creationDateTime%%>'), $templateCode);
		$templateCode = str_replace('<%%VALUE(CODGESTION)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(CODGESTION)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(CEDULA)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(CEDULA)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(CELULAR)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(CELULAR)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(OBSERVACIONES)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(OBSERVACIONES)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(ESTADO)%%>', 'INGRESADO', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(ESTADO)%%>', urlencode('INGRESADO'), $templateCode);
	}

	// process translations
	$templateCode = parseTemplate($templateCode);

	// clear scrap
	$templateCode = str_replace('<%%', '<!-- ', $templateCode);
	$templateCode = str_replace('%%>', ' -->', $templateCode);

	// hide links to inaccessible tables
	if(Request::val('dvprint_x') == '') {
		$templateCode .= "\n\n<script>\$j(function() {\n";
		$arrTables = getTableList();
		foreach($arrTables as $name => $caption) {
			$templateCode .= "\t\$j('#{$name}_link').removeClass('hidden');\n";
			$templateCode .= "\t\$j('#xs_{$name}_link').removeClass('hidden');\n";
		}

		$templateCode .= $jsReadOnly;
		$templateCode .= $jsEditable;

		if(!$selected_id) {
		}

		$templateCode.="\n});</script>\n";
	}

	// ajaxed auto-fill fields
	$templateCode .= '<script>';
	$templateCode .= '$j(function() {';


	$templateCode.="});";
	$templateCode.="</script>";
	$templateCode .= $lookups;

	// handle enforced parent values for read-only lookup fields
	$filterField = Request::val('FilterField');
	$filterOperator = Request::val('FilterOperator');
	$filterValue = Request::val('FilterValue');

	// don't include blank images in lightbox gallery
	$templateCode = preg_replace('/blank.gif" data-lightbox=".*?"/', 'blank.gif"', $templateCode);

	// don't display empty email links
	$templateCode=preg_replace('/<a .*?href="mailto:".*?<\/a>/', '', $templateCode);

	/* default field values */
	$rdata = $jdata = get_defaults('lideresgestion');
	if($selected_id) {
		$jdata = get_joined_record('lideresgestion', $selected_id);
		if($jdata === false) $jdata = get_defaults('lideresgestion');
		$rdata = $row;
	}
	$templateCode .= loadView('lideresgestion-ajax-cache', ['rdata' => $rdata, 'jdata' => $jdata]);

	// hook: lideresgestion_dv
	if(function_exists('lideresgestion_dv')) {
		$args = [];
		lideresgestion_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}