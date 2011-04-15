<?php
declare(ENCODING = 'utf-8');
namespace F3\Viewhelpertest\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Viewhelpertest".             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope prototype
 */
class HighlightViewHelper extends AbstractSubTemplateRenderingViewHelper {

	/**
	 * @param string $expected
	 * @param string $expectedRegex
	 * @return string
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function render($expected = NULL, $expectedRegex = NULL) {
		$source = trim($this->renderChildren());
		$renderedSource = $this->renderSource($source);

		$title = '';
		$className = '';
		if ($expected !== NULL) {
			$replacement = array(
				'\n' => "\n",
				'\t' => "\t"
			);
			$expected = strtr($expected, $replacement);
			$expected = html_entity_decode($expected);
		}
		if ($expectedRegex !== NULL) {
			$expectedRegex = html_entity_decode($expectedRegex);
		}

		if ($expected === NULL && $expectedRegex === NULL && $this->viewHelperVariableContainer->exists('F3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source')) {
			$isRegex = $this->viewHelperVariableContainer->get('F3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'regex');
			if ($isRegex) {
				$expectedRegex = $this->viewHelperVariableContainer->get('F3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source');
			} else {
				$expected = $this->viewHelperVariableContainer->get('F3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source');
			}

			$this->viewHelperVariableContainer->remove('F3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source');
			$this->viewHelperVariableContainer->remove('F3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'regex');
		}
		if ($expected !== NULL && trim($renderedSource) === $expected) {
			$title = 'successfully compared the rendered result with &quot;' . htmlspecialchars($expected) . '&quot;';
			$className = 'success';
		} elseif ($expectedRegex !== NULL && preg_match($expectedRegex, $renderedSource) === 1) {
			$title = 'successfully compared the rendered result with RegEx &quot;' . htmlspecialchars($expectedRegex) . '&quot;';
			$className = 'success';
		} elseif ($expected === NULL && $expectedRegex === NULL) {
			$className = 'default';
		} else {
			$className = 'failure';
			if ($expected !== NULL) {
				$title = 'expected &quot;' . htmlspecialchars($expected) . '&quot;';
			} else {
				$title = 'expected RegEx &quot;' . htmlspecialchars($expectedRegex) . '&quot;';
			}
		}
		return '<div class="testcase ' . $className . '">
			<h3>' . $title . '</h3>
			<div class="input">' . htmlspecialchars($source) . '</div>
			<div class="output">' . $renderedSource . '</div>
		</div>';
	}
}


?>
