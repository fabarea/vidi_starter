<?php

if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE == 'BE') {
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		$_EXTKEY,
		'tools', // Make media module a submodule of 'user'
		'm1',
		'bottom', // Position
		array(
			'Starter' => 'index, create',
		),
		array(
			'access' => 'admin',
			'icon' => 'EXT:vidi_starter/ext_icon.gif',
			'labels' => 'LLL:EXT:vidi_starter/Resources/Private/Language/locallang_module.xlf',
		)
	);
}

?>