<?php
namespace Fab\VidiStarter\Utility;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Utility class related to file structure
 */
class FileUtility {

	/**
	 * Recursively copy source to target.
	 *
	 * @param $source
	 * @param $target
	 */
	static public function recursiveCopy($source, $target){
		if (!is_dir($target)) {
			GeneralUtility::mkdir($target);
		}

		foreach (
			$iterator = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
				\RecursiveIteratorIterator::SELF_FIRST) as $item
		) {
			/** @var \SplFileInfo $item */
			if ($item->isDir()) {
				$directory = $target . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
				if (!is_dir($directory)) {
					mkdir($directory);
				}
			} else {
				copy($item, $target . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
			}
		}
	}
}
?>
