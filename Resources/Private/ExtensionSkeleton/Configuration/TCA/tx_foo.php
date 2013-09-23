<?php
if (!defined('TYPO3_MODE')) die ('Access denied.');

$tca = array(
	'grid' => array(
		'facets' => array(
			'uid',
			'###DATA_TYPE_LABEL###',
		),
		'columns' => array(
			'__checkbox' => array(
				'width' => '5px',
				'sortable' => FALSE,
				'html' => '<input type="checkbox" class="checkbox-row-top"/>',
			),
			'uid' => array(
				'visible' => FALSE,
				'label' => 'LLL:EXT:vidi/Resources/Private/Language/fe_groups.xlf:uid',
				'width' => '5px',
			),
###CONFIGURATION_GRID_TABLE_LABEL###
			'__buttons' => array(
				'sortable' => FALSE,
				'width' => '70px',
			),
		)
	)
);

$result = NULL;
if (!empty($GLOBALS['TCA']['###DATA_TYPE###'])) {
	$result = \TYPO3\CMS\Core\Utility\GeneralUtility::array_merge_recursive_overrule($GLOBALS['TCA']['###DATA_TYPE###'], $tca);
}
return $result;
?>