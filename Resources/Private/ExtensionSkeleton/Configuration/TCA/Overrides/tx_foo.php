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
				'renderer' => new \TYPO3\CMS\Vidi\Grid\CheckBoxComponent(),
			),
			'uid' => array(
				'visible' => FALSE,
				'label' => 'Id',
				'width' => '5px',
			),
###CONFIGURATION_GRID_TABLE_LABEL###
			'__buttons' => array(
				'renderer' => new \TYPO3\CMS\Vidi\Grid\ButtonGroupComponent(),
			),
		)
	)
);

if (!empty($GLOBALS['TCA']['###DATA_TYPE###'])) {
	\TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($GLOBALS['TCA']['###DATA_TYPE###'], $tca);
}