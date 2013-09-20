<?php

if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// Check from Vidi configuration what default module should be loaded.
// Make sure the class exists to avoid a Runtime Error
if (TYPO3_MODE == 'BE' && class_exists('TYPO3\CMS\Vidi\ModuleLoader')) {

	/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
	$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');

	/** @var \TYPO3\CMS\Extensionmanager\Utility\ConfigurationUtility $configurationUtility */
	$configurationUtility = $objectManager->get('TYPO3\CMS\Extensionmanager\Utility\ConfigurationUtility');
	$configuration = $configurationUtility->getCurrentConfiguration('vidi');

	###DATA_TYPES_ARRAY###;

	// Loop around the data types and register them to be displayed within a BE module.
	foreach ($dataTypes as $dataType) {

		/** @var \TYPO3\CMS\Vidi\ModuleLoader $moduleLoader */
		$moduleLoader = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Vidi\ModuleLoader', $dataType);
		$moduleLoader->setIcon(sprintf('EXT:vidi/Resources/Public/Images/%s.png', $dataType))
			->setModuleLanguageFile(sprintf('LLL:EXT:vidi/Resources/Private/Language/%s.xlf', $dataType))
			->addJavaScriptFiles(array(sprintf('EXT:vidi/Resources/Public/JavaScript/%s.js', $dataType)))
			->addStyleSheetFiles(array(sprintf('EXT:vidi/Resources/Public/StyleSheets/%s.js', $dataType)))
			->setDefaultPid($configuration['default_pid']['value'])
			->register();
	}
}
?>