<?php

if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// Register some Vidi modules.

/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');

/** @var \TYPO3\CMS\Extensionmanager\Utility\ConfigurationUtility $configurationUtility */
$configurationUtility = $objectManager->get('TYPO3\CMS\Extensionmanager\Utility\ConfigurationUtility');
$configuration = $configurationUtility->getCurrentConfiguration('vidi');

###DATA_TYPES_ARRAY###;

// Loop around the data types and register them to be displayed within a BE module.
foreach ($dataTypes as $dataType) {

	// Compute file path
	$iconFile = sprintf('EXT:###EXTENSION_NAME###/Resources/Public/Images/%s.png', $dataType);
	$languageFile = sprintf('LLL:EXT:###EXTENSION_NAME###/Resources/Private/Language/%s.xlf', $dataType);
	$javaScriptFile = sprintf('EXT:###EXTENSION_NAME###/Resources/Public/JavaScript/Backend/%s.js', $dataType);
	$styleSheetFile = sprintf('EXT:###EXTENSION_NAME###/Resources/Public/StyleSheets/Backend/%s.css', $dataType);

	/** @var \TYPO3\CMS\Vidi\ModuleLoader $moduleLoader */
	$moduleLoader = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Vidi\ModuleLoader', $dataType);
	$moduleLoader->setIcon($iconFile)
		->setModuleLanguageFile($languageFile)
		->addJavaScriptFiles(array($javaScriptFile))
		->addStyleSheetFiles(array($styleSheetFile))
		->setDefaultPid($configuration['default_pid']['value'])
		->register();

	// Trick for handling TCA which is not placed following TCA convention in CMS 6.2.
	if (empty($GLOBALS['TCA'][$dataType]['grid'])) {
		$fileNameAndPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('###EXTENSION_NAME###') . 'Configuration/TCA/' . $dataType . '.php';
		$GLOBALS['TCA'][$dataType] = require($fileNameAndPath);
	}
}