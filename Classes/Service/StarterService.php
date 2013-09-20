<?php
namespace TYPO3\CMS\VidiStarter\Service;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Fabien Udriot <fabien.udriot@typo3.org>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Service for kick starting an extension.
 */
class StarterService {

	/**
	 * The extension name to be created.
	 *
	 * @var string
	 */
	protected $extensionName;

	/**
	 * The data types for which a new BE module will be created.
	 *
	 * @var string
	 */
	protected $dataTypes;

	/**
	 * The source directory.
	 *
	 * @var string
	 */
	protected $source;

	/**
	 * The target directory.
	 *
	 * @var string
	 */
	protected $target;

	/**
	 * @var array
	 */
	protected $textFiles = array(
		array(
			'filePath' => 'Resources/Private/Language',
			'fileName' => 'tx_foo',
			'fileExtension' => 'xlf',
		),
		array(
			'filePath' => 'Resources/Public/StyleSheets/Backend',
			'fileName' => 'tx_foo',
			'fileExtension' => 'css',
		),
		array(
			'filePath' => 'Resources/Public/JavaScript/Backend',
			'fileName' => 'tx_foo',
			'fileExtension' => 'js',
		),
		array(
			'filePath' => 'Configuration/TCA',
			'fileName' => 'tx_foo',
			'fileExtension' => 'php',
		),
	);

	/**
	 * @var array
	 */
	protected $binaryFiles = array(
		array(
			'filePath' => 'Resources/Public/Images',
			'fileName' => 'tx_foo',
			'fileExtension' => 'png',
		),
	);

	/**
	 * @var array
	 */
	protected $singleFiles = array(
		'ext_emconf.php',
		'ext_tables.php',
	);

	/**
	 * Constructor
	 *
	 * @param string $extensionName
	 * @param array $dataTypes
	 * @return \TYPO3\CMS\VidiStarter\Service\StarterService
	 */
	public function __construct($extensionName, array $dataTypes) {
		$this->extensionName = $extensionName;
		$this->dataTypes = $dataTypes;
		$this->source = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:vidi_starter/Resources/Private/ExtensionSkeleton');
		$this->target = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('typo3conf/ext/' . $this->extensionName);
	}

	/**
	 * Kick Start a new BE module.
	 *
	 * @return void
	 */
	public function kickStart(){


		# Write dummy extension.
		$this->writeFileStructure();

		# Create files that makes sense to create
		foreach ($this->dataTypes as $dataType) {
			$markers = $this->prepareMarkers($dataType);
			$this->processTestFiles($dataType, $markers);
			$this->processBinaryFiles($dataType);
		}

		$markers = $this->prepareMarkers();
		$this->processSingleFiles($markers);

		# Remove obsolete files
		$this->cleanUp();
		exit();
	}

	/**
	 * Write dummy extension.
	 *
	 * @return void
	 */
	protected function writeFileStructure() {
		\TYPO3\CMS\VidiStarter\Utility\FileUtility::recursiveCopy($this->source, $this->target);
	}

	/**
	 * Prepare markers.
	 *
	 * @param string $dataType
	 * @return array
	 */
	protected function prepareMarkers($dataType = '') {

		$markers = array(
			'DATA_TYPES_CSV' => implode(',', $this->dataTypes),
			'EXTENSION_NAME' => $this->extensionName,
			'DATA_TYPES_ARRAY' => '$dataTypes = array(\'' . implode("','", $this->dataTypes) . '\')',
		);

		if (!empty($dataType)) {

			$tcaTableService = \TYPO3\CMS\Vidi\Tca\TcaServiceFactory::getTableService($dataType);

			$markers['DATA_TYPE'] = $dataType;

			$markers['CONFIGURATION_GRID_TABLE_LABEL'] = <<<EOF
			'{$tcaTableService->getLabelField()}' => array(
				'label' => 'LLL:EXT:{$this->extensionName}/Resources/Private/Language/{$dataType}.xlf:{$tcaTableService->getLabelField()}',
			),
EOF;
			$markers['LANGUAGE_TABLE_LABEL'] = <<<EOF
			<trans-unit id="{$tcaTableService->getLabel()}" xml:space="preserve">
				<source>{$tcaTableService->getLabelField()}</source>
			</trans-unit>
EOF;

		}

		return $markers;
	}

	/**
	 * Copy text files for a given data type. The method also searches and replaces markers.
	 *
	 * @param string $dataType
	 * @param array $markers
	 * @return void
	 */
	protected function processTestFiles($dataType, $markers) {


		foreach ($this->textFiles as $fileInfo) {
			$sourceFileNameAndPath = sprintf('%s/%s/%s.%s', $this->target, $fileInfo['filePath'], $fileInfo['fileName'], $fileInfo['fileExtension']);
			$targetFileNameAndPath = sprintf('%s/%s/%s.%s', $this->target, $fileInfo['filePath'], $dataType, $fileInfo['fileExtension']);

			$content = file_get_contents($sourceFileNameAndPath);

			foreach ($markers as $marker => $value) {
				$content = str_replace('###' . $marker . '###', $value, $content);
			}

			file_put_contents($targetFileNameAndPath, $content);
		}
	}

	/**
	 * Copy binary files for a given data type.
	 *
	 * @param string $dataType
	 * @return void
	 */
	protected function processBinaryFiles($dataType) {

		foreach ($this->binaryFiles as $fileInfo) {
			$sourceFileNameAndPath = sprintf('%s/%s/%s.%s', $this->target, $fileInfo['filePath'], $fileInfo['fileName'], $fileInfo['fileExtension']);
			$targetFileNameAndPath = sprintf('%s/%s/%s.%s', $this->target, $fileInfo['filePath'], $dataType, $fileInfo['fileExtension']);

			copy($sourceFileNameAndPath, $targetFileNameAndPath);
		}
	}

	/**
	 * Process single files by searching and replacing markers.
	 *
	 * @param array $markers
	 * @return void
	 */
	protected function processSingleFiles($markers) {


		foreach ($this->singleFiles as $file) {
			$fileNameAndPath = sprintf('%s/%s', $this->target, $file);

			$content = file_get_contents($fileNameAndPath);

			foreach ($markers as $marker => $value) {
				$content = str_replace('###' . $marker . '###', $value, $content);
			}

			file_put_contents($fileNameAndPath, $content);
		}
	}

	/**
	 * Clean up obsolete files
	 *
	 * @return void
	 */
	protected function cleanUp() {
		$files = array_merge($this->binaryFiles, $this->textFiles);
		foreach ($files as $fileInfo) {
			$fileNameAndPath = sprintf('%s/%s/%s.%s', $this->target, $fileInfo['filePath'], $fileInfo['fileName'], $fileInfo['fileExtension']);
			if (file_exists($fileNameAndPath)) {
				unlink($fileNameAndPath);
			}
		}
	}
}
?>
