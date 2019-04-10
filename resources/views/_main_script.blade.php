<?php

/**
 *
 * ========== Scripts Name ==========
 * datatables, datatables-rowreorder,
 * jquery-validation,
 * bootstrap-datepicker, bootstrap-daterangepicker, bootstrap-modal, bootstrap-select, bootstrap-timepicker,
 * iCheck, select2
 *
 */

function header_script(array $scripts = []) {
	foreach ($scripts as $script) {
		incHeaderScript($script);
	}
}

function footer_script(array $scripts = []) {
	foreach ($scripts as $script) {
		incFooterScript($script);
	}
}

function incHeaderScript($script) {

	switch ($script) {
		case 'datatables':
			//echo Html::style('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');
			//echo Html::style('bower_components/datatables.net-responsive-bs/css/responsive.bootstrap.min.css');
			break;

		case 'datatables-rowreorder':
			echo Html::style('bower_components/datatables.net-rowreorder-bs/css/rowReorder.bootstrap.min.css');
			break;

		case 'bootstrap-datepicker':
			echo Html::style('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css');
			$tmp =
				'<style>
					.dropdown-menu {
						z-index: 3002;
					}
				</style>';
			echo $tmp;
			break;

		case 'bootstrap-daterangepicker':
			echo Html::style('bower_components/bootstrap-daterangepicker/daterangepicker.css');
			$tmp =
				'<style>
					input.daterangepicker {
						top:  auto !important;
						left: auto !important;
					}
				</style>';
			echo $tmp;
			break;

		case 'bootstrap-fileupload':
			echo Html::style('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css');
			break;

		case 'bootstrap-modal':
			echo Html::style('bower_components/bootstrap-modal/css/bootstrap-modal.css');
			break;

		case 'bootstrap-select':
			echo Html::style('bower_components/bootstrap-select/dist/css/bootstrap-select.min.css');
			break;

		case 'bootstrap-touchspin':
			echo Html::style('bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css');
			break;

		case 'iCheck':
			echo Html::style('bower_components/iCheck/skins/all.css');
			break;

		case 'select2':
			//echo Html::style('bower_components/select2/dist/css/select2.min.css');
			break;

		case 'sweetalert':
			echo Html::style('bower_components/sweetalert/dist/sweetalert.css');
			break;

		case 'dropzone':
			echo Html::style('bower_components/dropzone/dist/min/dropzone.min.css');
			break;

		case 'datetimepicker':
			echo Html::style('bower_components/datetimepicker/build/jquery.datetimepicker.min.css');
			break;

		case 'nestable':
			echo Html::style('bower_components/jquery-nestable/nestable.min.css');
			break;

		case 'bootstrap-iconpicker':
			echo Html::style('bower_components/bootstrap-iconpicker/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css');
			break;

		case 'multi':
			echo Html::style('assets/js/plugins/multi/multi.css');
			break;

		case 'inputupload':
			echo Html::style('assets/css/bootstrap-fileupload.min.css');
			break;

		case 'bootstrap_multiselect':
			echo Html::style('assets/css/bootstrap-fileupload.min.css');
			break;
	}
}

function incFooterScript($script) {

	switch ($script) {
		case 'datatables':
			echo Html::script('bower_components/datatables.net/js/jquery.dataTables.min.js');
			echo Html::script('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js');
			echo Html::script('bower_components/datatables.net-responsive/js/dataTables.responsive.min.js');
			echo Html::script('bower_components/datatables.net-responsive-bs/js/responsive.bootstrap.js');
			break;

		case 'datatablesFixedColumns':
			echo Html::script('assets/js/plugins/tables/datatables/extensions/fixed_columns.min.js');
			break;

        case 'datatablesButtons':
            echo Html::script('assets/js/plugins/tables/datatables/extensions/buttons.min.js');
            break;

        case 'datatables-rowreorder':
			echo Html::script('bower_components/datatables.net-rowreorder/js/dataTables.rowReorder.min.js');
			break;

		case 'jquery-validation':
			echo Html::script('bower_components/jquery-validation/dist/jquery.validate.min.js');
			echo Html::script('bower_components/jquery-validation/dist/additional-methods.min.js');
			break;

		case 'jquery-nestable':
			echo Html::script('bower_components/jquery-nestable/jquery.nestable.js');
			break;

		case 'bootstrap-datepicker':
			echo Html::script('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');
			break;

		case 'bootstrap-daterangepicker':
			echo Html::script('bower_components/moment/moment.js');
			echo Html::script('bower_components/bootstrap-daterangepicker/daterangepicker.js');

		case 'bootstrap-fileupload':
			echo Html::script('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js');
			break;

		case 'bootstrap-modal':
			echo Html::script('bower_components/bootstrap-modal/js/bootstrap-modal.js');
			echo Html::script('bower_components/bootstrap-modal/js/bootstrap-modalmanager.js');
			break;

		case 'bootstrap-select':
			echo Html::script('bower_components/bootstrap-select/dist/js/bootstrap-select.min.js');
			break;

		case 'bootstrap-timepicker':
			echo Html::script('bower_components/bootstrap-timepicker/js/bootstrap-timepicker.js');
			break;

		case 'bootstrap-touchspin':
			echo Html::script('bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js');
			break;

		case 'iCheck':
			echo Html::script('bower_components/iCheck/icheck.min.js');
			break;

		case 'select2':
			echo Html::script('bower_components/select2/dist/js/select2.min.js');
			break;

		case 'sweetalert':
			echo Html::script('bower_components/sweetalert/dist/sweetalert.min.js');
			break;

		case 'dropzone':
			echo Html::script('bower_components/dropzone/dist/min/dropzone.min.js');
			break;

		case 'datetimepicker':
			echo Html::script('bower_components/datetimepicker/build/jquery.datetimepicker.full.min.js');
			break;

		case 'nestable':
			echo Html::script('bower_components/jquery-nestable/nestable.min.js');
			break;

		case 'bootstrap-iconpicker':
		    echo Html::script('bower_components/bootstrap-iconpicker/bootstrap-iconpicker/js/iconset/iconset-icomoon.js');
			echo Html::script('bower_components/bootstrap-iconpicker/bootstrap-iconpicker/js/bootstrap-iconpicker.js');
			break;

		case 'angular':
			echo Html::script('components/angular/angular.min.js');
			break;

		case 'multi':
			echo Html::script('assets/js/plugins/multi/multi.js');
			break;
		case 'inputupload':
			echo Html::script('assets/js/bootstrap-fileupload.min.js');
			break;

		case 'ckeditor':
			echo Html::script('bower_components/ckeditor/ckeditor.js');
			break;

		case 'to-markdown':
			echo Html::script('bower_components/to-markdown/dist/to-markdown.js');
			break;

		case 'showdown':
			echo Html::script('bower_components/showdown/dist/showdown.js');
			break;

		case 'sortable':
			echo Html::script('bower_components/Sortable/Sortable.min.js');
			break;

		case 'bootstrap_multiselect':
			echo Html::script('assets/js/plugins/forms/selects/bootstrap_multiselect.js');
			// echo Html::script('assets/js/pages/form_multiselect.js');
			break;

		case 'uniform':
			echo Html::script('assets/js/plugins/forms/styling/uniform.min.js');
			break;

		case 'switch':
			echo Html::script('assets/js/plugins/forms/styling/switchery.min.js');
			echo Html::script('assets/js/plugins/forms/styling/switch.min.js');
			break;
		case 'touchspin':
			echo Html::script('assets/js/plugins/forms/inputs/touchspin.min.js');
			break;

	}
}
