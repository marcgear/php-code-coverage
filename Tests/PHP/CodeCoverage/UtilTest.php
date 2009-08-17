<?php
/**
 * PHP_CodeCoverage
 *
 * Copyright (c) 2009, Sebastian Bergmann <sb@sebastian-bergmann.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   PHP
 * @package    CodeCoverage
 * @subpackage Tests
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2009 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://github.com/sebastianbergmann/php-code-coverage
 * @since      File available since Release 1.0.0
 */

require_once 'PHP/CodeCoverage/Util.php';

if (!defined('TEST_FILES_PATH')) {
    define(
      'TEST_FILES_PATH',
      dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR .
      '_files' . DIRECTORY_SEPARATOR
    );
}

require_once TEST_FILES_PATH . 'CoverageClassExtendedTest.php';
require_once TEST_FILES_PATH . 'CoverageClassTest.php';
require_once TEST_FILES_PATH . 'CoverageMethodTest.php';
require_once TEST_FILES_PATH . 'CoverageNotPrivateTest.php';
require_once TEST_FILES_PATH . 'CoverageNotProtectedTest.php';
require_once TEST_FILES_PATH . 'CoverageNotPublicTest.php';
require_once TEST_FILES_PATH . 'CoveragePrivateTest.php';
require_once TEST_FILES_PATH . 'CoverageProtectedTest.php';
require_once TEST_FILES_PATH . 'CoveragePublicTest.php';
require_once TEST_FILES_PATH . 'CoveredClass.php';
require_once TEST_FILES_PATH . 'NotExistingCoveredElementTest.php';

if (version_compare(PHP_VERSION, '5.3', '>')) {
    require_once TEST_FILES_PATH . 'NamespaceCoverageClassExtendedTest.php';
    require_once TEST_FILES_PATH . 'NamespaceCoverageClassTest.php';
    require_once TEST_FILES_PATH . 'NamespaceCoverageMethodTest.php';
    require_once TEST_FILES_PATH . 'NamespaceCoverageNotPrivateTest.php';
    require_once TEST_FILES_PATH . 'NamespaceCoverageNotProtectedTest.php';
    require_once TEST_FILES_PATH . 'NamespaceCoverageNotPublicTest.php';
    require_once TEST_FILES_PATH . 'NamespaceCoveragePrivateTest.php';
    require_once TEST_FILES_PATH . 'NamespaceCoverageProtectedTest.php';
    require_once TEST_FILES_PATH . 'NamespaceCoveragePublicTest.php';
    require_once TEST_FILES_PATH . 'NamespaceCoveredClass.php';
}

/**
 * Tests for the PHP_CodeCoverage_Util class.
 *
 * @category   PHP
 * @package    CodeCoverage
 * @subpackage Tests
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2009 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://github.com/sebastianbergmann/php-code-coverage
 * @since      Class available since Release 1.0.0
 */
class PHP_CodeCoverage_UtilTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers       PHP_CodeCoverage_Util::getLinesToBeCovered
     * @covers       PHP_CodeCoverage_Util::resolveCoversToReflectionObjects
     * @dataProvider getLinesToBeCoveredProvider
     */
    public function testGetLinesToBeCovered($test, $lines)
    {
        if (strpos($test, 'Namespace') === 0) {
            if (!version_compare(PHP_VERSION, '5.3', '>')) {
                $this->markTestSkipped('PHP 5.3 (or later) is required.');
            }

            $file = 'NamespaceCoveredClass.php';
        } else {
            $file = 'CoveredClass.php';
        }

        $this->assertEquals(
          array(
            TEST_FILES_PATH . $file => $lines
          ),
          PHP_CodeCoverage_Util::getLinesToBeCovered(
            $test, 'testPublicMethod'
          )
        );
    }

    /**
     * @covers            PHP_CodeCoverage_Util::getLinesToBeCovered
     * @covers            PHP_CodeCoverage_Util::resolveCoversToReflectionObjects
     * @expectedException RuntimeException
     */
    public function testGetLinesToBeCovered2()
    {
        PHP_CodeCoverage_Util::getLinesToBeCovered(
          'NotExistingCoveredElementTest', 'testOne'
        );
    }

    /**
     * @covers            PHP_CodeCoverage_Util::getLinesToBeCovered
     * @covers            PHP_CodeCoverage_Util::resolveCoversToReflectionObjects
     * @expectedException RuntimeException
     */
    public function testGetLinesToBeCovered3()
    {
        PHP_CodeCoverage_Util::getLinesToBeCovered(
          'NotExistingCoveredElementTest', 'testTwo'
        );
    }

    /**
     * @covers            PHP_CodeCoverage_Util::getLinesToBeCovered
     * @covers            PHP_CodeCoverage_Util::resolveCoversToReflectionObjects
     * @expectedException RuntimeException
     */
    public function testGetLinesToBeCovered4()
    {
        PHP_CodeCoverage_Util::getLinesToBeCovered(
          'NotExistingCoveredElementTest', 'testThree'
        );
    }

    /**
     * @covers PHP_CodeCoverage_Util::getLinesToBeIgnored
     */
    public function testGetLinesToBeIgnored()
    {
        $this->assertEquals(
          array(3, 4, 5),
          PHP_CodeCoverage_Util::getLinesToBeIgnored(
            TEST_FILES_PATH . 'source_with_ignore.php'
          )
        );
    }

    /**
     * @covers PHP_CodeCoverage_Util::getLinesToBeIgnored
     */
    public function testGetLinesToBeIgnored2()
    {
        $this->assertEquals(
          array(),
          PHP_CodeCoverage_Util::getLinesToBeIgnored(
            TEST_FILES_PATH . 'source_without_ignore.php'
          )
        );
    }

    public function getLinesToBeCoveredProvider()
    {
        return array(
          array(
            'CoverageClassExtendedTest',
            array_merge(range(19, 36), range(2, 17))
          ),
          array(
            'CoverageClassTest',
            range(19, 36)
          ),
          array(
            'CoverageMethodTest',
            range(31, 35)
          ),
          array(
            'CoverageNotPrivateTest',
            array_merge(range(25, 29), range(31, 35))
          ),
          array(
            'CoverageNotProtectedTest',
            array_merge(range(21, 23), range(31, 35))
          ),
          array(
            'CoverageNotPublicTest',
            array_merge(range(21, 23), range(25, 29))
          ),
          array(
            'CoveragePrivateTest',
            range(21, 23)
          ),
          array(
            'CoverageProtectedTest',
            range(25, 29)
          ),
          array(
            'CoveragePublicTest',
            range(31, 35)
          ),
          array(
            'NamespaceCoverageClassExtendedTest',
            array_merge(range(21, 38), range(4, 19))
          ),
          array(
            'NamespaceCoverageClassTest',
            range(21, 38)
          ),
          array(
            'NamespaceCoverageMethodTest',
            range(33, 37)
          ),
          array(
            'NamespaceCoverageNotPrivateTest',
            array_merge(range(27, 31), range(33, 37))
          ),
          array(
            'NamespaceCoverageNotProtectedTest',
            array_merge(range(23, 25), range(33, 37))
          ),
          array(
            'NamespaceCoverageNotPublicTest',
            array_merge(range(23, 25), range(27, 31))
          ),
          array(
            'NamespaceCoveragePrivateTest',
            range(23, 25)
          ),
          array(
            'NamespaceCoverageProtectedTest',
            range(27, 31)
          ),
          array(
            'NamespaceCoveragePublicTest',
            range(33, 37)
          )
        );
    }
}
?>
