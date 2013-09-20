<?php
if (!defined('TYPO3_MODE')) die ('Access denied.');

$tca = array(
	'grid' => array(
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
			'title' => array(
				'visible' => TRUE,
				'label' => 'LLL:EXT:vidi/Resources/Private/Language/fe_groups.xlf:title',
			),
			'__buttons' => array(
				'sortable' => FALSE,
				'width' => '70px',
			),
		)
	)
);

return \TYPO3\CMS\Core\Utility\GeneralUtility::array_merge_recursive_overrule($GLOBALS['TCA']['###DATA_TYPE###'], $tca);
?>