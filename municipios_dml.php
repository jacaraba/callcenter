<?php

// Data functions (insert, update, delete, form) for table municipios

// This script and data application were generated by AppGini 23.11
// Download AppGini for free from https://bigprof.com/appgini/download/

function municipios_insert(&$error_message = '') {
	global $Translation;

	// mm: can member insert record?
	$arrPerm = getTablePermissions('municipios');
	if(!$arrPerm['insert']) return false;

	$data = [
		'ddmm' => Request::val('ddmm', ''),
		'dd' => Request::lookup('dd', ''),
		'mm' => Request::val('mm', ''),
		'municipio' => Request::val('municipio', ''),
	];

	if($data['ddmm'] === '') {
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'Ddmm': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">' . $Translation['< back'] . '</a></div>';
		exit;
	}

	// hook: municipios_before_insert
	if(function_exists('municipios_before_insert')) {
		$args = [];
		if(!municipios_before_insert($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$error = '';
	// set empty fields to NULL
	$data = array_map(function($v) { return ($v === '' ? NULL : $v); }, $data);
	insert('municipios', backtick_keys_once($data), $error);
	if($error) {
		$error_message = $error;
		return false;
	}

	$recID = $data['ddmm'];

	update_calc_fields('municipios', $recID, calculated_fields()['municipios']);

	// hook: municipios_after_insert
	if(function_exists('municipios_after_insert')) {
		$res = sql("SELECT * FROM `municipios` WHERE `ddmm`='" . makeSafe($recID, false) . "' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)) {
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args = [];
		if(!municipios_after_insert($data, getMemberInfo(), $args)) { return $recID; }
	}

	// mm: save ownership data
	set_record_owner('municipios', $recID, getLoggedMemberID());

	// if this record is a copy of another record, copy children if applicable
	if(strlen(Request::val('SelectedID'))) municipios_copy_children($recID, Request::val('SelectedID'));

	return $recID;
}

function municipios_copy_children($destination_id, $source_id) {
	global $Translation;
	$requests = []; // array of curl handlers for launching insert requests
	$eo = ['silentErrors' => true];
	$safe_sid = makeSafe($source_id);

	// launch requests, asynchronously
	curl_batch($requests);
}

function municipios_delete($selected_id, $AllowDeleteOfParents = false, $skipChecks = false) {
	// insure referential integrity ...
	global $Translation;
	$selected_id = makeSafe($selected_id);

	// mm: can member delete record?
	if(!check_record_permission('municipios', $selected_id, 'delete')) {
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: municipios_before_delete
	if(function_exists('municipios_before_delete')) {
		$args = [];
		if(!municipios_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'] . (
				!empty($args['error_message']) ?
					'<div class="text-bold">' . strip_tags($args['error_message']) . '</div>'
					: '' 
			);
	}

	sql("DELETE FROM `municipios` WHERE `ddmm`='{$selected_id}'", $eo);

	// hook: municipios_after_delete
	if(function_exists('municipios_after_delete')) {
		$args = [];
		municipios_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("DELETE FROM `membership_userrecords` WHERE `tableName`='municipios' AND `pkValue`='{$selected_id}'", $eo);
}

function municipios_update(&$selected_id, &$error_message = '') {
	global $Translation;

	// mm: can member edit record?
	if(!check_record_permission('municipios', $selected_id, 'edit')) return false;

	$data = [
		'ddmm' => Request::val('ddmm', ''),
		'dd' => Request::lookup('dd', ''),
		'mm' => Request::val('mm', ''),
		'municipio' => Request::val('municipio', ''),
	];

	if($data['ddmm'] === '') {
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'Ddmm': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">' . $Translation['< back'] . '</a></div>';
		exit;
	}
	// get existing values
	$old_data = getRecord('municipios', $selected_id);
	if(is_array($old_data)) {
		$old_data = array_map('makeSafe', $old_data);
		$old_data['selectedID'] = makeSafe($selected_id);
	}

	$data['selectedID'] = makeSafe($selected_id);

	// hook: municipios_before_update
	if(function_exists('municipios_before_update')) {
		$args = ['old_data' => $old_data];
		if(!municipios_before_update($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$set = $data; unset($set['selectedID']);
	foreach ($set as $field => $value) {
		$set[$field] = ($value !== '' && $value !== NULL) ? $value : NULL;
	}

	if(!update(
		'municipios', 
		backtick_keys_once($set), 
		['`ddmm`' => $selected_id], 
		$error_message
	)) {
		echo $error_message;
		echo '<a href="municipios_view.php?SelectedID=' . urlencode($selected_id) . "\">{$Translation['< back']}</a>";
		exit;
	}

	$data['selectedID'] = $data['ddmm'];
	$newID = $data['ddmm'];

	$eo = ['silentErrors' => true];

	update_calc_fields('municipios', $data['selectedID'], calculated_fields()['municipios']);

	// hook: municipios_after_update
	if(function_exists('municipios_after_update')) {
		$res = sql("SELECT * FROM `municipios` WHERE `ddmm`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)) $data = array_map('makeSafe', $row);

		$data['selectedID'] = $data['ddmm'];
		$args = ['old_data' => $old_data];
		if(!municipios_after_update($data, getMemberInfo(), $args)) return;
	}

	// mm: update ownership data
	sql("UPDATE `membership_userrecords` SET `dateUpdated`='" . time() . "', `pkValue`='{$data['ddmm']}' WHERE `tableName`='municipios' AND `pkValue`='" . makeSafe($selected_id) . "'", $eo);

	// if PK value changed, update $selected_id
	$selected_id = $newID;
}

function municipios_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $separateDV = 0, $TemplateDV = '', $TemplateDVP = '') {
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
	$arrPerm = getTablePermissions('municipios');
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

	$filterer_dd = Request::val('filterer_dd');

	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: dd
	$combo_dd = new DataCombo;

	if($selected_id) {
		if(!check_record_permission('municipios', $selected_id, 'view'))
			return $Translation['tableAccessDenied'];

		// can edit?
		$AllowUpdate = check_record_permission('municipios', $selected_id, 'edit');

		// can delete?
		$AllowDelete = check_record_permission('municipios', $selected_id, 'delete');

		$res = sql("SELECT * FROM `municipios` WHERE `ddmm`='" . makeSafe($selected_id) . "'", $eo);
		if(!($row = db_fetch_array($res))) {
			return error_message($Translation['No records found'], 'municipios_view.php', false);
		}
		$combo_dd->SelectedData = $row['dd'];
		$urow = $row; /* unsanitized data */
		$row = array_map('safe_html', $row);
	} else {
		$filterField = Request::val('FilterField');
		$filterOperator = Request::val('FilterOperator');
		$filterValue = Request::val('FilterValue');
		$combo_dd->SelectedData = $filterer_dd;
	}
	$combo_dd->HTML = '<span id="dd-container' . $rnd1 . '"></span><input type="hidden" name="dd" id="dd' . $rnd1 . '" value="' . html_attr($combo_dd->SelectedData) . '">';
	$combo_dd->MatchText = '<span id="dd-container-readonly' . $rnd1 . '"></span><input type="hidden" name="dd" id="dd' . $rnd1 . '" value="' . html_attr($combo_dd->SelectedData) . '">';

	ob_start();
	?>

	<script>
		// initial lookup values
		AppGini.current_dd__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['dd'] : htmlspecialchars($filterer_dd, ENT_QUOTES)); ?>"};

		jQuery(function() {
			setTimeout(function() {
				if(typeof(dd_reload__RAND__) == 'function') dd_reload__RAND__();
			}, 50); /* we need to slightly delay client-side execution of the above code to allow AppGini.ajaxCache to work */
		});
		function dd_reload__RAND__() {
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint) { ?>

			$j("#dd-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c) {
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_dd__RAND__.value, t: 'municipios', f: 'dd' },
						success: function(resp) {
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="dd"]').val(resp.results[0].id);
							$j('[id=dd-container-readonly__RAND__]').html('<span class="match-text" id="dd-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=departamentos_view_parent]').hide(); } else { $j('.btn[id=departamentos_view_parent]').show(); }


							if(typeof(dd_update_autofills__RAND__) == 'function') dd_update_autofills__RAND__();
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
					data: function(term, page) { return { s: term, p: page, t: 'municipios', f: 'dd' }; },
					results: function(resp, page) { return resp; }
				},
				escapeMarkup: function(str) { return str; }
			}).on('change', function(e) {
				AppGini.current_dd__RAND__.value = e.added.id;
				AppGini.current_dd__RAND__.text = e.added.text;
				$j('[name="dd"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=departamentos_view_parent]').hide(); } else { $j('.btn[id=departamentos_view_parent]').show(); }


				if(typeof(dd_update_autofills__RAND__) == 'function') dd_update_autofills__RAND__();
			});

			if(!$j("#dd-container__RAND__").length) {
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_dd__RAND__.value, t: 'municipios', f: 'dd' },
					success: function(resp) {
						$j('[name="dd"]').val(resp.results[0].id);
						$j('[id=dd-container-readonly__RAND__]').html('<span class="match-text" id="dd-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=departamentos_view_parent]').hide(); } else { $j('.btn[id=departamentos_view_parent]').show(); }

						if(typeof(dd_update_autofills__RAND__) == 'function') dd_update_autofills__RAND__();
					}
				});
			}

		<?php } else { ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_dd__RAND__.value, t: 'municipios', f: 'dd' },
				success: function(resp) {
					$j('[id=dd-container__RAND__], [id=dd-container-readonly__RAND__]').html('<span class="match-text" id="dd-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=departamentos_view_parent]').hide(); } else { $j('.btn[id=departamentos_view_parent]').show(); }

					if(typeof(dd_update_autofills__RAND__) == 'function') dd_update_autofills__RAND__();
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
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/municipios_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	} else {
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/municipios_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Detail View', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', (Request::val('Embedded') ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($AllowInsert) {
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return municipios_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return municipios_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
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
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return municipios_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
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
		$jsReadOnly .= "\tjQuery('#ddmm').replaceWith('<div class=\"form-control-static\" id=\"ddmm\">' + (jQuery('#ddmm').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#dd').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#dd_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#mm').replaceWith('<div class=\"form-control-static\" id=\"mm\">' + (jQuery('#mm').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#municipio').replaceWith('<div class=\"form-control-static\" id=\"municipio\">' + (jQuery('#municipio').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	} elseif($AllowInsert) {
		$jsEditable = "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode = str_replace('<%%COMBO(dd)%%>', $combo_dd->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(dd)%%>', $combo_dd->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(dd)%%>', urlencode($combo_dd->MatchText), $templateCode);

	/* lookup fields array: 'lookup field name' => ['parent table name', 'lookup field caption'] */
	$lookup_fields = ['dd' => ['departamentos', 'Dd'], ];
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
	$templateCode = str_replace('<%%UPLOADFILE(ddmm)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(dd)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(mm)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(municipio)%%>', '', $templateCode);

	// process values
	if($selected_id) {
		if( $dvprint) $templateCode = str_replace('<%%VALUE(ddmm)%%>', safe_html($urow['ddmm']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(ddmm)%%>', html_attr($row['ddmm']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(ddmm)%%>', urlencode($urow['ddmm']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(dd)%%>', safe_html($urow['dd']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(dd)%%>', html_attr($row['dd']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(dd)%%>', urlencode($urow['dd']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(mm)%%>', safe_html($urow['mm']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(mm)%%>', html_attr($row['mm']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(mm)%%>', urlencode($urow['mm']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(municipio)%%>', safe_html($urow['municipio']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(municipio)%%>', html_attr($row['municipio']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(municipio)%%>', urlencode($urow['municipio']), $templateCode);
	} else {
		$templateCode = str_replace('<%%VALUE(ddmm)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(ddmm)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(dd)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(dd)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(mm)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(mm)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(municipio)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(municipio)%%>', urlencode(''), $templateCode);
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
	$rdata = $jdata = get_defaults('municipios');
	if($selected_id) {
		$jdata = get_joined_record('municipios', $selected_id);
		if($jdata === false) $jdata = get_defaults('municipios');
		$rdata = $row;
	}
	$templateCode .= loadView('municipios-ajax-cache', ['rdata' => $rdata, 'jdata' => $jdata]);

	// hook: municipios_dv
	if(function_exists('municipios_dv')) {
		$args = [];
		municipios_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}