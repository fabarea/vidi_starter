<?php
namespace TYPO3\CMS\VidiStarter\Utility;
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
			\TYPO3\CMS\Core\Utility\GeneralUtility::mkdir($target);
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
