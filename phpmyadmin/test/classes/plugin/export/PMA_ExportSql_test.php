<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * tests for ExportSql class
 *
 * @package PhpMyAdmin-test
 */
require_once 'libraries/plugins/export/ExportSql.class.php';
require_once 'libraries/DatabaseInterface.class.php';
require_once 'libraries/export.lib.php';
require_once 'libraries/Util.class.php';
require_once 'libraries/Theme.class.php';
require_once 'libraries/Config.class.php';
require_once 'libraries/php-gettext/gettext.inc';
require_once 'libraries/config.default.php';
require_once 'libraries/mysql_charsets.lib.php';
require_once 'libraries/relation.lib.php';
require_once 'libraries/transformations.lib.php';
require_once 'libraries/Table.class.php';
require_once 'libraries/sqlparser.lib.php';
require_once 'libraries/charset_conversion.lib.php';
require_once 'export.php';
/**
 * tests for ExportSql class
 *
 * @package PhpMyAdmin-test
 * @group medium
 */
class PMA_ExportSql_Test extends PHPUnit_Framework_TestCase
{
    protected $object;

    /**
     * Configures global environment.
     *
     * @return void
     */
    function setup()
    {
        if (!defined("PMA_DRIZZLE")) {
            define("PMA_DRIZZLE", false);
        }

        $GLOBALS['server'] = 0;
        $GLOBALS['output_kanji_conversion'] = false;
        $GLOBALS['buffer_needed'] = false;
        $GLOBALS['asfile'] = false;
        $GLOBALS['save_on_server'] = false;
        $GLOBALS['plugin_param'] = array();
        $GLOBALS['plugin_param']['export_type'] = 'table';
        $GLOBALS['plugin_param']['single_table'] = false;
        $GLOBALS['cfgRelation']['relation'] = true;
        $GLOBALS['controllink'] = null;
        $this->object = new ExportSql();
    }

    /**
     * tearDown for test cases
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->object);
    }

    /**
     * Test for ExportSql::setProperties
     *
     * @return void
     * @group medium
     */
    public function testSetProperties()
    {
        $restoreDrizzle = $restoreMySQLIntVersion = 'PMANORESTORE';

        if (PMA_DRIZZLE) {
            if (!PMA_HAS_RUNKIT) {
                $this->markTestSkipped(
                    "Cannot redefine constant. Missing runkit extension"
                );
            } else {
                if (PMA_DRIZZLE) {
                    $restoreDrizzle = PMA_DRIZZLE;
                    runkit_constant_redefine('PMA_DRIZZLE', false);
                }
            }
        }
        // test with hide structure and hide sql as true
        $GLOBALS['plugin_param']['export_type'] = 'table';
        $GLOBALS['plugin_param']['single_table'] = false;
        $GLOBALS['cfgRelation']['mimework'] = true;

        $method = new ReflectionMethod('ExportSql', 'setProperties');
        $method->setAccessible(true);
        $method->invoke($this->object, null);

        $attrProperties = new ReflectionProperty('ExportSql', 'properties');
        $attrProperties->setAccessible(true);
        $properties = $attrProperties->getValue($this->object);

        $this->assertNull(
            $properties
        );

        // test with hide structure and hide sql as false
        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->once())
            ->method('getCompatibilities')
            ->will($this->returnValue(array('v1', 'v2')));

        $GLOBALS['dbi'] = $dbi;
        $GLOBALS['plugin_param']['export_type'] = 'server';
        $GLOBALS['plugin_param']['single_table'] = false;
        $GLOBALS['cfgRelation']['mimework'] = true;
        $GLOBALS['cfgRelation']['relation'] = true;

        $method->invoke($this->object, null);
        $properties = $attrProperties->getValue($this->object);

        $this->assertInstanceOf(
            'ExportPluginProperties',
            $properties
        );

        $this->assertEquals(
            'SQL',
            $properties->getText()
        );

        $options = $properties->getOptions();

        $this->assertInstanceOf(
            'OptionsPropertyRootGroup',
            $options
        );

        $generalOptionsArray = $options->getProperties();

        $generalOptions = array_shift($generalOptionsArray);

        $this->assertInstanceOf(
            'OptionsPropertyMainGroup',
            $generalOptions
        );

        $properties = $generalOptions->getProperties();

        $property = array_shift($properties);

        $this->assertInstanceOf(
            'OptionsPropertySubgroup',
            $property
        );

        $this->assertInstanceOf(
            'BoolPropertyItem',
            $property->getSubgroupHeader()
        );

        $leaves = $property->getProperties();

        $leaf = array_shift($leaves);
        $this->assertInstanceOf(
            'TextPropertyItem',
            $leaf
        );

        $leaf = array_shift($leaves);
        $this->assertInstanceOf(
            'BoolPropertyItem',
            $leaf
        );

        $leaf = array_shift($leaves);
        $this->assertInstanceOf(
            'BoolPropertyItem',
            $leaf
        );

        $leaf = array_shift($leaves);
        $this->assertInstanceOf(
            'BoolPropertyItem',
            $leaf
        );

        $property = array_shift($properties);
        $this->assertInstanceOf(
            'BoolPropertyItem',
            $property
        );

        $property = array_shift($properties);
        $this->assertInstanceOf(
            'BoolPropertyItem',
            $property
        );

        $property = array_shift($properties);
        $this->assertInstanceOf(
            'BoolPropertyItem',
            $property
        );

        $property = array_shift($properties);
        $this->assertInstanceOf(
            'SelectPropertyItem',
            $property
        );

        $this->assertEquals(
            array(
                'v1' => 'v1',
                'v2' => 'v2'
            ),
            $property->getValues()
        );

        $property = array_shift($properties);
        $this->assertInstanceOf(
            'BoolPropertyItem',
            $property
        );

        $property = array_shift($properties);
        $this->assertInstanceOf(
            'OptionsPropertySubgroup',
            $property
        );

        $this->assertInstanceOf(
            'RadioPropertyItem',
            $property->getSubgroupHeader()
        );

        $structureOptions = array_shift($generalOptionsArray);

        $this->assertInstanceOf(
            'OptionsPropertyMainGroup',
            $structureOptions
        );

        $properties = $structureOptions->getProperties();

        $property = array_shift($properties);

        $this->assertInstanceOf(
            'OptionsPropertySubgroup',
            $property
        );

        $this->assertInstanceOf(
            'MessageOnlyPropertyItem',
            $property->getSubgroupHeader()
        );

        $leaves = $property->getProperties();

        $leaf = array_shift($leaves);
        $this->assertInstanceOf(
            'BoolPropertyItem',
            $leaf
        );

        $leaf = array_shift($leaves);
        $this->assertInstanceOf(
            'BoolPropertyItem',
            $leaf
        );

        $this->assertEquals(
            'Add <code>DROP TABLE / VIEW / PROCEDURE / FUNCTION' .
            ' / EVENT</code><code> / TRIGGER</code> statement',
            $leaf->getText()
        );

        $leaf = array_shift($leaves);
        $this->assertInstanceOf(
            'BoolPropertyItem',
            $leaf
        );

        $leaf = array_shift($leaves);
        $this->assertInstanceOf(
            'BoolPropertyItem',
            $leaf
        );

        $leaf = array_shift($leaves);
        $this->assertInstanceOf(
            'BoolPropertyItem',
            $leaf
        );

        $leaf = array_shift($leaves);
        $this->assertInstanceOf(
            'BoolPropertyItem',
            $leaf
        );

        $leaf = array_shift($leaves);
        $this->assertInstanceOf(
            'OptionsPropertySubgroup',
            $leaf
        );

        $this->assertCount(
            2,
            $leaf->getProperties()
        );

        $this->assertInstanceOf(
            'BoolPropertyItem',
            $leaf->getSubgroupHeader()
        );

        $property = array_shift($properties);

        $this->assertInstanceOf(
            'BoolPropertyItem',
            $property
        );

        $dataOptions = array_shift($generalOptionsArray);
        $this->assertInstanceOf(
            'OptionsPropertyMainGroup',
            $dataOptions
        );

        $properties = $dataOptions->getProperties();

        $this->assertCount(
            7,
            $properties
        );

        $this->assertCount(
            2,
            $properties[1]->getProperties()
        );

        if ($restoreDrizzle !== 'PMANORESTORE') {
            runkit_constant_redefine('PMA_DRIZZLE', $restoreDrizzle);
        }

        if ($restoreMySQLIntVersion !== 'PMANORESTORE') {
            runkit_constant_redefine(
                'PMA_MYSQL_INT_VERSION', $restoreMySQLIntVersion
            );
        }
    }

    /**
     * Test for ExportSql::setProperties
     *
     * @return void
     */
    public function testSetPropertiesWithDrizzle()
    {
        $restoreDrizzle = $restoreMySQLIntVersion = 'PMANORESTORE';

        if (!PMA_DRIZZLE) {
            if (!PMA_HAS_RUNKIT) {
                $this->markTestSkipped(
                    "Cannot redefine constant. Missing runkit extension"
                );
            } else {
                if (!PMA_DRIZZLE) {
                    $restoreDrizzle = PMA_DRIZZLE;
                    runkit_constant_redefine('PMA_DRIZZLE', true);
                }
                $restoreMySQLIntVersion = PMA_MYSQL_INT_VERSION;
                runkit_constant_redefine('PMA_MYSQL_INT_VERSION', 50000);
            }
        }

        $GLOBALS['plugin_param']['export_type'] = 'tableNot';
        $GLOBALS['plugin_param']['single_table'] = true;
        $GLOBALS['cfgRelation']['mimework'] = false;
        $GLOBALS['cfgRelation']['relation'] = false;

        $method = new ReflectionMethod('ExportSql', 'setProperties');
        $method->setAccessible(true);
        $method->invoke($this->object, null);

        $attrProperties = new ReflectionProperty('ExportSql', 'properties');
        $attrProperties->setAccessible(true);
        $properties = $attrProperties->getValue($this->object);

        $options = $properties->getOptions();

        $generalOptionsArray = $options->getProperties();

        $this->assertCount(
            3,
            $generalOptionsArray
        );

        // generalOptions
        $option = array_shift($generalOptionsArray);

        $properties = $option->getProperties();

        $this->assertCount(
            5,
            $properties
        );

        $this->assertCount(
            2,
            $properties[0]->getProperties()
        );

        // structureOptions
        $option = array_shift($generalOptionsArray);
        $properties = $option->getProperties();

        $this->assertCount(
            2,
            $properties
        );

        $properties = $properties[0]->getProperties();

        $this->assertCount(
            6,
            $properties
        );

        $this->assertNotcontains(
            '<code> / EVENT </code>',
            $properties[1]->getText()
        );

        // dataOptions
        $option = array_shift($generalOptionsArray);
        $properties = $option->getProperties();

        $this->assertCount(
            6,
            $properties
        );

        $properties = $properties[1]->getProperties();

        $this->assertCount(
            1,
            $properties
        );

        // case 2 with export type as table
        $GLOBALS['plugin_param']['export_type'] = 'table';

        $method->invoke($this->object, null);
        $properties = $attrProperties->getValue($this->object);

        $options = $properties->getOptions();
        $generalOptionsArray = $options->getProperties();
        $structOption = $generalOptionsArray[1];
        $properties = $structOption->getProperties();
        $subgroupProps = $properties[0]->getProperties();

        $this->assertContains(
            '<code>DROP TABLE</code>',
            $subgroupProps[0]->getText()
        );

        if ($restoreDrizzle !== 'PMANORESTORE') {
            runkit_constant_redefine('PMA_DRIZZLE', $restoreDrizzle);
        }

        if ($restoreMySQLIntVersion !== 'PMANORESTORE') {
            runkit_constant_redefine(
                'PMA_MYSQL_INT_VERSION', $restoreMySQLIntVersion
            );
        }
    }

    /**
     * Test for ExportSql::exportRoutines
     *
     * @return void
     */
    public function testExportRoutines()
    {
        $GLOBALS['crlf'] = '##';
        $GLOBALS['sql_drop_table'] = true;

        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->at(0))
            ->method('getProceduresOrFunctions')
            ->with('db', 'PROCEDURE')
            ->will($this->returnValue(array('p1', 'p2')));

        $dbi->expects($this->at(1))
            ->method('getProceduresOrFunctions')
            ->with('db', 'FUNCTION')
            ->will($this->returnValue(array('f1')));

        $dbi->expects($this->at(2))
            ->method('getDefinition')
            ->with('db', 'PROCEDURE', 'p1')
            ->will($this->returnValue('testp1'));

        $dbi->expects($this->at(3))
            ->method('getDefinition')
            ->with('db', 'PROCEDURE', 'p2')
            ->will($this->returnValue('testp2'));

        $dbi->expects($this->at(4))
            ->method('getDefinition')
            ->with('db', 'FUNCTION', 'f1')
            ->will($this->returnValue('testf1'));

        $GLOBALS['dbi'] = $dbi;

        $this->expectOutputString(
            '##DELIMITER $$##DROP PROCEDURE IF EXISTS `p1`$$##testp1$$####' .
            'DROP PROCEDURE IF EXISTS `p2`$$##testp2$$####DROP FUNCTION IF' .
            ' EXISTS `f1`$$##testf1$$####DELIMITER ;##'
        );

        $this->object->exportRoutines('db');
    }

    /**
     * Test for ExportSql::_exportComment
     *
     * @return void
     */
    public function testExportComment()
    {
        $method = new ReflectionMethod('ExportSql', '_exportComment');
        $method->setAccessible(true);

        $GLOBALS['crlf'] = '##';
        $GLOBALS['sql_include_comments'] = true;

        $this->assertEquals(
            '--##',
            $method->invoke($this->object, '')
        );

        $this->assertEquals(
            '-- Comment##',
            $method->invoke($this->object, 'Comment')
        );

        $GLOBALS['sql_include_comments'] = false;

        $this->assertEquals(
            '',
            $method->invoke($this->object, 'Comment')
        );

        unset($GLOBALS['sql_include_comments']);

        $this->assertEquals(
            '',
            $method->invoke($this->object, 'Comment')
        );
    }

    /**
     * Test for ExportSql::_possibleCRLF
     *
     * @return void
     */
    public function testPossibleCRLF()
    {
        $method = new ReflectionMethod('ExportSql', '_possibleCRLF');
        $method->setAccessible(true);

        $GLOBALS['crlf'] = '##';
        $GLOBALS['sql_include_comments'] = true;

        $this->assertEquals(
            '##',
            $method->invoke($this->object, '')
        );

        $this->assertEquals(
            '##',
            $method->invoke($this->object, 'Comment')
        );

        $GLOBALS['sql_include_comments'] = false;

        $this->assertEquals(
            '',
            $method->invoke($this->object, 'Comment')
        );

        unset($GLOBALS['sql_include_comments']);

        $this->assertEquals(
            '',
            $method->invoke($this->object, 'Comment')
        );
    }

    /**
     * Test for ExportSql::exportFooter
     *
     * @return void
     */
    public function testExportFooter()
    {
        $restoreDrizzle = 'PMANORESTORE';

        if (PMA_DRIZZLE) {
            if (!PMA_HAS_RUNKIT) {
                $this->markTestSkipped(
                    "Cannot redefine constant. Missing runkit extension"
                );
            } else {
                $restoreDrizzle = PMA_DRIZZLE;
                runkit_constant_redefine('PMA_DRIZZLE', false);
            }
        }

        $GLOBALS['sql_disable_fk'] = true;
        $GLOBALS['sql_use_transaction'] = true;
        $GLOBALS['charset_of_file'] = 'utf-8';
        $GLOBALS['mysql_charset_map']['utf-8'] = true;
        $GLOBALS['sql_utc_time'] = true;
        $GLOBALS['old_tz'] = 'GMT';
        $GLOBALS['asfile'] = 'yes';
        $GLOBALS['output_charset_conversion'] = 'utf-8';

        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->once())
            ->method('query')
            ->with('SET time_zone = "GMT"');

        $GLOBALS['dbi'] = $dbi;

        $this->expectOutputString(
            'SET FOREIGN_KEY_CHECKS=1;COMMIT;/*!40101 SET CHARACTER_SET_CLIENT' .
            '=@OLD_CHARACTER_SET_CLIENT */;/*!40101 SET CHARACTER_SET_RESULTS=@' .
            'OLD_CHARACTER_SET_RESULTS */;/*!40101 SET COLLATION_CONNECTION=@OLD' .
            '_COLLATION_CONNECTION */;'
        );

        $this->assertTrue(
            $this->object->exportFooter()
        );

        if ($restoreDrizzle !== 'PMANORESTORE') {
            runkit_constant_redefine('PMA_DRIZZLE', $restoreDrizzle);
        }

    }

    /**
     * Test for ExportSql::exportHeader
     *
     * @return void
     */
    public function testExportHeader()
    {
        if (!defined("PMA_MYSQL_STR_VERSION")) {
            define("PMA_MYSQL_STR_VERSION", "5.0.0");
        }

        $restoreDrizzle = 'PMANORESTORE';

        if (PMA_DRIZZLE) {
            if (!PMA_HAS_RUNKIT) {
                $this->markTestSkipped(
                    "Cannot redefine constant. Missing runkit extension"
                );
            } else {
                $restoreDrizzle = PMA_DRIZZLE;
                runkit_constant_redefine('PMA_DRIZZLE', false);
            }
        }

        $GLOBALS['crlf'] = "\n";
        $GLOBALS['sql_compatibility'] = 'NONE';
        $GLOBALS['cfg']['Server']['host'] = 'localhost';
        $GLOBALS['cfg']['Server']['port'] = 80;
        $GLOBALS['sql_disable_fk'] = true;
        $GLOBALS['sql_use_transaction'] = true;
        $GLOBALS['sql_utc_time'] = true;
        $GLOBALS['old_tz'] = 'GMT';
        $GLOBALS['asfile'] = 'yes';
        $GLOBALS['output_charset_conversion'] = 'utf-8';
        $GLOBALS['sql_header_comment'] = "h1C\nh2C";
        $GLOBALS['sql_use_transaction'] = true;
        $GLOBALS['sql_include_comments'] = true;
        $GLOBALS['charset_of_file'] = 'utf-8';
        $GLOBALS['mysql_charset_map']['utf-8'] = true;

        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->once())
            ->method('tryQuery')
            ->with('SET SQL_MODE=""');

        $dbi->expects($this->once())
            ->method('fetchValue')
            ->with('SELECT @@session.time_zone')
            ->will($this->returnValue('old_tz'));

        $dbi->expects($this->once())
            ->method('query')
            ->with('SET time_zone = "+00:00"');

        $GLOBALS['dbi'] = $dbi;

        ob_start();
        $this->assertTrue(
            $this->object->exportHeader()
        );
        $result = ob_get_clean();

        $this->assertContains(
            'h1C',
            $result
        );

        $this->assertContains(
            'h2C',
            $result
        );

        $this->assertContains(
            "SET FOREIGN_KEY_CHECKS=0;\n",
            $result
        );

        $this->assertContains(
            "40101 SET",
            $result
        );

        $this->assertContains(
            "SET FOREIGN_KEY_CHECKS=0;\n" .
            "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n" .
            "SET AUTOCOMMIT = 0;\n" .
            "START TRANSACTION;\n" .
            "SET time_zone = \"+00:00\";\n",
            $result
        );

        if ($restoreDrizzle !== 'PMANORESTORE') {
            runkit_constant_redefine('PMA_DRIZZLE', $restoreDrizzle);
        }

    }

    /**
     * Test for ExportSql::exportDBCreate
     *
     * @return void
     */
    public function testExportDBCreate()
    {
        $GLOBALS['sql_compatibility'] = 'NONE';
        $GLOBALS['sql_drop_database'] = true;
        $GLOBALS['sql_backquotes'] = true;
        $GLOBALS['sql_create_database'] = true;
        $GLOBALS['sql_create_table'] = true;
        $GLOBALS['sql_create_view'] = true;
        $GLOBALS['crlf'] = "\n";

        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->once())
            ->method('isSystemSchema')
            ->with('db')
            ->will($this->returnValue(true));

        $GLOBALS['dbi'] = $dbi;

        ob_start();
        $this->assertTrue(
            $this->object->exportDBCreate('db')
        );
        $result = ob_get_clean();

        $this->assertContains(
            "DROP DATABASE `db`;\n",
            $result
        );

        $this->assertContains(
            'CREATE DATABASE IF NOT EXISTS `db` DEFAULT CHARACTER ' .
            'SET utf8 COLLATE utf8_general_ci;',
            $result
        );

        $this->assertContains(
            'USE `db`;',
            $result
        );

        // case2: no backquotes
        unset($GLOBALS['sql_compatibility']);
        $GLOBALS['cfg']['Server']['DisableIS'] = true;
        unset($GLOBALS['sql_backquotes']);

        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->once())
            ->method('fetchValue')
            ->with('SHOW VARIABLES LIKE \'collation_database\'', 0, 1)
            ->will($this->returnValue('testcollation'));

        $GLOBALS['dbi'] = $dbi;

        ob_start();
        $this->assertTrue(
            $this->object->exportDBCreate('db')
        );
        $result = ob_get_clean();

        $this->assertContains(
            "DROP DATABASE db;\n",
            $result
        );

        $this->assertContains(
            'CREATE DATABASE IF NOT EXISTS db DEFAULT CHARACTER SET testcollation;',
            $result
        );

        $this->assertContains(
            'USE db;',
            $result
        );
    }

    /**
     * Test for ExportSql::exportDBHeader
     *
     * @return void
     */
    public function testExportDBHeader()
    {
        $GLOBALS['sql_compatibility'] = 'MSSQL';
        $GLOBALS['sql_backquotes'] = true;
        $GLOBALS['sql_include_comments'] = true;
        $GLOBALS['crlf'] = "\n";

        ob_start();
        $this->assertTrue(
            $this->object->exportDBHeader('testDB')
        );
        $result = ob_get_clean();

        $this->assertContains(
            "&quot;testDB&quot;",
            $result
        );

        // case 2
        unset($GLOBALS['sql_compatibility']);
        unset($GLOBALS['sql_backquotes']);

        ob_start();
        $this->assertTrue(
            $this->object->exportDBHeader('testDB')
        );
        $result = ob_get_clean();

        $this->assertContains(
            "testDB",
            $result
        );
    }

    /**
     * Test for ExportSql::exportEvents
     *
     * @return void
     */
    public function testExportEventsWithNewerMySQLVersion()
    {
        $restoreMySQLVersion = "PMANORESTORE";

        if (! PMA_HAS_RUNKIT) {
            $this->markTestSkipped(
                'Cannot redefine constant. Missing runkit extension'
            );
        } else {
            $restoreMySQLVersion = PMA_MYSQL_INT_VERSION;
            runkit_constant_redefine('PMA_MYSQL_INT_VERSION', 50101);
        }

        $GLOBALS['crlf'] = "\n";
        $GLOBALS['sql_structure_or_data'] = 'structure';
        $GLOBALS['sql_procedure_function'] = true;

        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->once())
            ->method('fetchResult')
            ->with(
                'SELECT EVENT_NAME FROM information_schema.EVENTS WHERE'
                . ' EVENT_SCHEMA= \'db\';'
            )
            ->will($this->returnValue(array('f1', 'f2')));

        $dbi->expects($this->at(1))
            ->method('getDefinition')
            ->with('db', 'EVENT', 'f1')
            ->will($this->returnValue('f1event'));

        $dbi->expects($this->at(2))
            ->method('getDefinition')
            ->with('db', 'EVENT', 'f2')
            ->will($this->returnValue('f2event'));

        $GLOBALS['dbi'] = $dbi;

        ob_start();
        $this->assertTrue(
            $this->object->exportEvents('db', array())
        );
        $result = ob_get_clean();

        $this->assertContains(
            "DELIMITER $$\n",
            $result
        );

        $this->assertContains(
            "DELIMITER ;\n",
            $result
        );

        $this->assertContains(
            "f1event$$\n",
            $result
        );

        $this->assertContains(
            "f2event$$\n",
            $result
        );

        if ($restoreMySQLVersion !== "PMANORESTORE") {
            runkit_constant_redefine('PMA_MYSQL_INT_VERSION', $restoreMySQLVersion);
        }
    }

    /**
     * Test for ExportSql::exportDBFooter
     *
     * @return void
     */
    public function testExportDBFooterWithOlderMySQLVersion()
    {
        $restoreMySQLVersion = "PMANORESTORE";

        if (! PMA_HAS_RUNKIT) {
            $this->markTestSkipped(
                'Cannot redefine constant. Missing runkit extension'
            );
        } else {
            $restoreMySQLVersion = PMA_MYSQL_INT_VERSION;
            runkit_constant_redefine('PMA_MYSQL_INT_VERSION', 50100);
        }

        $GLOBALS['crlf'] = "\n";
        $GLOBALS['sql_constraints'] = "SqlConstraints";
        $GLOBALS['sql_structure_or_data'] = 'structure';
        $GLOBALS['sql_procedure_function'] = true;

        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $GLOBALS['dbi'] = $dbi;

        ob_start();
        $this->assertTrue(
            $this->object->exportDBFooter('db')
        );
        $result = ob_get_clean();

        $this->assertEquals(
            'SqlConstraints',
            $result
        );

        if ($restoreMySQLVersion !== "PMANORESTORE") {
            runkit_constant_redefine('PMA_MYSQL_INT_VERSION', $restoreMySQLVersion);
        }
    }

    /**
     * Test for ExportSql::getTableDefStandIn
     *
     * @return void
     */
    public function testGetTableDefStandIn()
    {
        $GLOBALS['sql_drop_table'] = true;
        $GLOBALS['sql_if_not_exists'] = true;

        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->once())
            ->method('getColumnsFull')
            ->with('db', 'view')
            ->will(
                $this->returnValue(
                    array('cname' => array('Type' => 'int'))
                )
            );

        $GLOBALS['dbi'] = $dbi;

        $result = $this->object->getTableDefStandIn('db', 'view', "");

        $this->assertContains(
            "DROP VIEW IF EXISTS `view`;",
            $result
        );

        $this->assertContains(
            "CREATE TABLE IF NOT EXISTS `view` (`cname` int);",
            $result
        );
    }

    /**
     * Test for ExportSql::_getTableDefForView
     *
     * @return void
     */
    public function testGetTableDefForView()
    {
        $GLOBALS['sql_drop_table'] = true;
        $GLOBALS['sql_if_not_exists'] = true;
        $GLOBALS['cfg']['LimitChars'] = 40;

        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->at(0))
            ->method('getColumns')
            ->with('db', 'view')
            ->will(
                $this->returnValue(
                    array(
                        'cname' => array(
                            'Type' => 'char',
                            'Collation' => 'utf-8',
                            'Null' => 'NO',
                            'Default' => 'a',
                            'Comment' => 'cmt',
                            'Field' => 'fname'
                        )
                    )
                )
            );

        $dbi->expects($this->at(1))
            ->method('getColumns')
            ->with('db', 'view')
            ->will(
                $this->returnValue(
                    array(
                        'cname' => array(
                            'Type' => 'char',
                            'Collation' => 'utf-8',
                            'Null' => 'YES',
                            'Comment' => 'cmt',
                            'Field' => 'fname'
                        )
                    )
                )
            );
        $GLOBALS['dbi'] = $dbi;
        $GLOBALS['sql_compatibility'] = 'MSSQL';

        $method = new ReflectionMethod('ExportSql', '_getTableDefForView');
        $method->setAccessible(true);
        $result = $method->invoke(
            $this->object, 'db', 'view', "\n"
        );

        $this->assertEquals(
            "CREATE TABLE `view`(\n" .
            "    `fname` char COLLATE utf-8 NOT NULL DEFAULT 'a' COMMENT 'cmt'\n" .
            ");\n",
            $result
        );

        // case 2
        unset($GLOBALS['sql_compatibility']);
        $result = $method->invoke(
            $this->object, 'db', 'view', "\n", false
        );

        $this->assertEquals(
            "CREATE TABLE IF NOT EXISTS `view`(\n" .
            "    `fname` char COLLATE utf-8 DEFAULT NULL COMMENT 'cmt'\n" .
            ")\n",
            $result
        );
    }


    /**
     * Test for ExportSql::getTableDef
     *
     * @return void
     * @group medium
     */
    public function testGetTableDefWithoutDrizzle()
    {
        $restoreDrizzle = 'PMANORESTORE';

        if (PMA_DRIZZLE) {
            if (!PMA_HAS_RUNKIT) {
                $this->markTestSkipped(
                    "Cannot redefine constant. Missing runkit extension"
                );
            } else {
                $restoreDrizzle = PMA_DRIZZLE;
                runkit_constant_redefine('PMA_DRIZZLE', false);
            }
        }

        $GLOBALS['sql_compatibility'] = 'MSSQL';
        $GLOBALS['sql_auto_increment'] = true;
        $GLOBALS['sql_drop_table'] = true;
        $GLOBALS['sql_backquotes'] = true;
        $GLOBALS['sql_if_not_exists']  = true;
        $GLOBALS['sql_include_comments']  = true;
        $GLOBALS['crlf'] = "\n";
        if (isset($GLOBALS['sql_constraints'])) {
            unset($GLOBALS['sql_constraints']);
        }

        if (isset($GLOBALS['no_constraints_comments'])) {
            unset($GLOBALS['no_constraints_comments']);
        }

        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->at(0))
            ->method('query')
            ->with(
                'SHOW TABLE STATUS FROM `db` WHERE Name = \'table\'', null,
                PMA_DatabaseInterface::QUERY_STORE
            )
            ->will($this->returnValue('res'));

        $dbi->expects($this->never())
            ->method('fetchSingleRow');

        $dbi->expects($this->once())
            ->method('numRows')
            ->with('res')
            ->will($this->returnValue(2));

        $dbi->expects($this->any())
            ->method('fetchValue')
            ->will($this->returnValue(false));

        $tmpres = array(
            'Auto_increment' => 1,
            'Create_time' => '2000-01-01 10:00:00',
            'Update_time' => '2000-01-02 12:00:00',
            'Check_time' => '2000-01-02 13:00:00',
        );

        $dbi->expects($this->once())
            ->method('fetchAssoc')
            ->with('res')
            ->will($this->returnValue($tmpres));

        $dbi->expects($this->at(5))
            ->method('query')
            ->with('SET SQL_QUOTE_SHOW_CREATE = 1')
            ->will($this->returnValue('res'));

        $dbi->expects($this->once())
            ->method('tryQuery')
            ->with('SHOW CREATE TABLE `db`.`table`')
            ->will($this->returnValue('res'));

        $row = array(
            '',
            "CREATE TABLE `db`.`table`,\n CONSTRAINT KEYS \nFOREIGN  KEY\n) " .
            "unsigned NOT NULL\n(\r\n"
        );

        $dbi->expects($this->once())
            ->method('fetchRow')
            ->with('res')
            ->will($this->returnValue($row));

        $GLOBALS['dbi'] = $dbi;
        $GLOBALS['cfg']['Server']['DisableIS'] = false;

        $result = $this->object->getTableDef(
            'db', 'table', "\n", "example.com/err", true, true, true
        );

        $this->assertContains(
            '-- Creation: Jan 01, 2000 at 10:00 AM',
            $result
        );

        $this->assertContains(
            '-- Last update: Jan 02, 2000 at 12:00 PM',
            $result
        );

        $this->assertContains(
            '-- Last check: Jan 02, 2000 at 01:00 PM',
            $result
        );

        $this->assertContains(
            'DROP TABLE IF EXISTS `table`;',
            $result
        );

        $this->assertContains(
            "CREATE TABLE `table`\n",
            $result
        );

        $this->assertContains(
            ") NOT NULL\n",
            $result
        );

        $this->assertContains(
            '-- Constraints for dumped tables',
            $GLOBALS['sql_constraints']
        );

        $this->assertContains(
            '-- Constraints for table "table"',
            $GLOBALS['sql_constraints']
        );

        $this->assertContains(
            'ALTER TABLE "table"' . "\n",
            $GLOBALS['sql_constraints']
        );

        $this->assertContains(
            'ADD CONSTRAINT KEYS ' . "\n",
            $GLOBALS['sql_constraints']
        );

        $this->assertContains(
            'ADD FOREIGN  KEY;',
            $GLOBALS['sql_constraints']
        );

        $this->assertContains(
            'ALTER TABLE "table"' . "\n",
            $GLOBALS['sql_constraints_query']
        );

        $this->assertContains(
            '  ADD CONSTRAINT KEYS   ADD FOREIGN  KEY;',
            $GLOBALS['sql_constraints_query']
        );

        $this->assertContains(
            'ALTER TABLE "db"."table"' . "\n",
            $GLOBALS['sql_drop_foreign_keys']
        );

        $this->assertContains(
            'DROP FOREIGN KEY KEYS',
            $GLOBALS['sql_drop_foreign_keys']
        );

        if ($restoreDrizzle !== "PMANORESTORE") {
            runkit_constant_redefine('PMA_DRIZZLE', $restoreDrizzle);
        }
    }

    /**
     * Test for ExportSql::getTableDef
     *
     * @return void
     */
    public function testGetTableDefWithDrizzle()
    {
        $restoreDrizzle = 'PMANORESTORE';

        if (!PMA_DRIZZLE) {
            if (!PMA_HAS_RUNKIT) {
                $this->markTestSkipped(
                    "Cannot redefine constant. Missing runkit extension"
                );
            } else {
                $restoreDrizzle = PMA_DRIZZLE;
                runkit_constant_redefine('PMA_DRIZZLE', true);
            }
        }

        $GLOBALS['sql_auto_increment'] = true;
        $GLOBALS['sql_drop_table'] = true;
        $GLOBALS['sql_backquotes'] = true;
        $GLOBALS['sql_if_not_exists']  = true;
        $GLOBALS['sql_include_comments']  = true;
        $GLOBALS['crlf'] = "\n";

        if (isset($GLOBALS['sql_compatibility'])) {
            unset($GLOBALS['sql_compatibility']);
        }

        if (isset($GLOBALS['sql_constraints'])) {
            unset($GLOBALS['sql_constraints']);
        }

        $GLOBALS['no_constraints_comments'] = true;

        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->at(0))
            ->method('query')
            ->with(
                'SHOW TABLE STATUS FROM `db` WHERE Name = \'table\'', null,
                PMA_DatabaseInterface::QUERY_STORE
            )
            ->will($this->returnValue('res'));

        $dbi->expects($this->once())
            ->method('numRows')
            ->with('res')
            ->will($this->returnValue(2));

        $dbi->expects($this->once())
            ->method('fetchSingleRow')
            ->will($this->returnValue(array()));

        $dbi->expects($this->any())
            ->method('fetchValue')
            ->will($this->returnValue(false));

        $tmpres = array(
            'Auto_increment' => 1,
            'Create_time' => '2000-01-01 10:00:00',
            'Update_time' => '2000-01-02 12:00:00',
            'Check_time' => '2000-01-02 13:00:00',
        );

        $dbi->expects($this->once())
            ->method('fetchAssoc')
            ->with('res')
            ->will($this->returnValue($tmpres));

        $dbi->expects($this->once())
            ->method('tryQuery')
            ->with('SHOW CREATE TABLE `db`.`table`')
            ->will($this->returnValue('res'));

        $row = array(
            '',
            "CREATE TABLE `db`.`table` ROW_FORMAT='row',\n CONSTRAINT "
             . "KEYS \nFOREIGN  KEY\n) unsigned NOT NULL\n(\r"
        );

        $dbi->expects($this->once())
            ->method('fetchRow')
            ->with('res')
            ->will($this->returnValue($row));

        $GLOBALS['dbi'] = $dbi;
        $GLOBALS['cfg']['Server']['DisableIS'] = false;

        $result = $this->object->getTableDef(
            'db', 'table', "\n", "example.com/err", true, true, true
        );

        $this->assertContains(
            "CREATE TABLE IF NOT EXISTS `table` ROW_FORMAT=row",
            $result
        );

        $this->assertContains(
            ") unsigned NOT NULL\n",
            $result
        );

        $this->assertNotContains(
            '-- Constraints for table',
            $GLOBALS['sql_constraints']
        );

        $this->assertNotContains(
            '-- Constraints for table "table"',
            $GLOBALS['sql_constraints']
        );

        if ($restoreDrizzle !== "PMANORESTORE") {
            runkit_constant_redefine('PMA_DRIZZLE', $restoreDrizzle);
        }
    }

    /**
     * Test for ExportSql::getTableDef
     *
     * @return void
     */
    public function testGetTableDefWithError()
    {
        $restoreDrizzle = 'PMANORESTORE';

        if (PMA_DRIZZLE) {
            if (!PMA_HAS_RUNKIT) {
                $this->markTestSkipped(
                    "Cannot redefine constant. Missing runkit extension"
                );
            } else {
                $restoreDrizzle = PMA_DRIZZLE;
                runkit_constant_redefine('PMA_DRIZZLE', false);
            }
        }

        $GLOBALS['sql_compatibility'] = '';
        $GLOBALS['sql_auto_increment'] = true;
        $GLOBALS['sql_drop_table'] = true;
        $GLOBALS['sql_backquotes'] = false;
        $GLOBALS['sql_if_not_exists']  = true;
        $GLOBALS['sql_include_comments']  = true;
        $GLOBALS['crlf'] = "\n";

        if (isset($GLOBALS['sql_constraints'])) {
            unset($GLOBALS['sql_constraints']);
        }

        if (isset($GLOBALS['no_constraints_comments'])) {
            unset($GLOBALS['no_constraints_comments']);
        }

        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->at(0))
            ->method('query')
            ->with(
                'SHOW TABLE STATUS FROM `db` WHERE Name = \'table\'', null,
                PMA_DatabaseInterface::QUERY_STORE
            )
            ->will($this->returnValue('res'));

        $dbi->expects($this->never())
            ->method('fetchSingleRow');

        $dbi->expects($this->once())
            ->method('numRows')
            ->with('res')
            ->will($this->returnValue(2));

        $dbi->expects($this->any())
            ->method('fetchValue')
            ->will($this->returnValue(false));

        $tmpres = array(
            'Auto_increment' => 1,
            'Create_time' => '2000-01-01 10:00:00',
            'Update_time' => '2000-01-02 12:00:00',
            'Check_time' => '2000-01-02 13:00:00',
        );

        $dbi->expects($this->once())
            ->method('fetchAssoc')
            ->with('res')
            ->will($this->returnValue($tmpres));

        $dbi->expects($this->at(5))
            ->method('query')
            ->with('SET SQL_QUOTE_SHOW_CREATE = 0')
            ->will($this->returnValue('res'));

        $dbi->expects($this->once())
            ->method('tryQuery')
            ->with('SHOW CREATE TABLE `db`.`table`')
            ->will($this->returnValue('res'));

        $dbi->expects($this->once())
            ->method('getError')
            ->will($this->returnValue('error occurred'));

        $GLOBALS['dbi'] = $dbi;
        $GLOBALS['cfg']['Server']['DisableIS'] = false;

        $result = $this->object->getTableDef(
            'db', 'table', "\n", "example.com/err", true, true, true
        );

        $this->assertContains(
            '-- in use(error occurred)',
            $result
        );

        if ($restoreDrizzle !== "PMANORESTORE") {
            runkit_constant_redefine('PMA_DRIZZLE', $restoreDrizzle);
        }
    }

    /**
     * Test for ExportSql::_getTableComments
     *
     * @return void
     */
    public function testGetTableComments()
    {
        $_SESSION['relation'][0] = array(
            'relwork' => true,
            'commwork' => true,
            'mimework' => true,
            'db' => 'database',
            'relation' => 'rel',
            'column_info' => 'col'
        );
        $GLOBALS['sql_include_comments'] = true;
        $GLOBALS['crlf'] = "\n";

        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->at(0))
            ->method('fetchResult')
            ->will(
                $this->returnValue(
                    array(
                        'foo' => array(
                            'foreign_table' => 'ftable',
                            'foreign_field' => 'ffield'
                        )
                    )
                )
            );

        $dbi->expects($this->at(1))
            ->method('fetchResult')
            ->will(
                $this->returnValue(
                    array(
                        'fieldname' => array(
                            'values' => 'test-',
                            'transformation' => 'testfoo',
                            'mimetype' => 'test<'
                        )
                    )
                )
            );
        $GLOBALS['dbi'] = $dbi;

        $method = new ReflectionMethod('ExportSql', '_getTableComments');
        $method->setAccessible(true);
        $result = $method->invoke(
            $this->object, 'db', '', "\n", true, true
        );

        $this->assertContains(
            "-- MIME TYPES FOR TABLE :\n" .
            "--   fieldname\n" .
            "--       Test<",
            $result
        );

        $this->assertContains(
            "-- RELATIONS FOR TABLE :\n" .
            "--   foo\n" .
            "--       ftable -> ffield",
            $result
        );
    }

    /**
     * Test for ExportSql::exportStructure
     *
     * @return void
     * @group medium
     */
    public function testExportStructure()
    {

        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->once())
            ->method('getTriggers')
            ->with('db', 't&bl')
            ->will(
                $this->returnValue(
                    array(
                        array('create' => 'bar', 'drop' => 'foo')
                    )
                )
            );

        $this->object = $this->getMockBuilder('ExportSql')
            ->setMethods(array('getTableDef', 'getTriggers', 'getTableDefStandIn'))
            ->getMock();

        $this->object->expects($this->at(0))
            ->method('getTableDef')
            ->with('db', 't&bl', "\n", "example.com", false)
            ->will($this->returnValue('dumpText1'));

        $this->object->expects($this->at(1))
            ->method('getTableDef')
            ->with(
                'db', 't&bl', "\n", "example.com", false
            )
            ->will($this->returnValue('dumpText3'));

        $this->object->expects($this->once())
            ->method('getTableDefStandIn')
            ->with('db', 't&bl', "\n")
            ->will($this->returnValue('dumpText4'));

        $GLOBALS['dbi'] = $dbi;
        $GLOBALS['sql_compatibility'] = 'MSSQL';
        $GLOBALS['sql_backquotes'] = true;
        $GLOBALS['sql_include_comments'] = true;
        $GLOBALS['crlf'] = "\n";

        // case 1
        ob_start();
        $this->assertTrue(
            $this->object->exportStructure(
                'db', 't&bl', "\n", "example.com", "create_table", "test"
            )
        );
        $result = ob_get_clean();

        $this->assertContains(
            '-- Table structure for table &quot;t&amp;bl&quot;',
            $result
        );

        $this->assertContains(
            'dumpText1',
            $result
        );

        // case 2
        unset($GLOBALS['sql_compatibility']);
        unset($GLOBALS['sql_backquotes']);

        $GLOBALS['sql_create_trigger'] = true;
        $GLOBALS['sql_drop_table'] = true;

        ob_start();
        $this->assertTrue(
            $this->object->exportStructure(
                'db', 't&bl', "\n", "example.com", "triggers", "test"
            )
        );
        $result = ob_get_clean();

        $this->assertContains(
            "-- Triggers t&amp;bl\n",
            $result
        );

        $this->assertContains(
            "foo;\nDELIMITER $$\nbarDELIMITER ;\n",
            $result
        );

        unset($GLOBALS['sql_create_trigger']);
        unset($GLOBALS['sql_drop_table']);

        // case 3
        $GLOBALS['sql_views_as_tables'] = false;

        ob_start();
        $this->assertTrue(
            $this->object->exportStructure(
                'db', 't&bl', "\n", "example.com", "create_view", "test"
            )
        );
        $result = ob_get_clean();

        $this->assertContains(
            "-- Structure for view t&amp;bl\n",
            $result
        );

        $this->assertContains(
            "DROP TABLE IF EXISTS `t&amp;bl`;\n" .
            "dumpText3",
            $result
        );

        // case 4
        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->once())
            ->method('getColumns')
            ->will(
                $this->returnValue(
                    array()
                )
            );
        $GLOBALS['dbi'] = $dbi;
        $GLOBALS['sql_views_as_tables'] = true;

        ob_start();
        $this->assertTrue(
            $this->object->exportStructure(
                'db', 't&bl', "\n", "example.com", "create_view", "test"
            )
        );
        $result = ob_get_clean();

        $this->assertContains(
            "CREATE TABLE`t&amp;bl`(\n\n);",
            $result
        );

        $this->assertContains(
            "DROP TABLE IF EXISTS `t&amp;bl`;\n",
            $result
        );

        // case 5

        ob_start();
        $this->assertTrue(
            $this->object->exportStructure(
                'db', 't&bl', "\n", "example.com", "stand_in", "test"
            )
        );
        $result = ob_get_clean();

        $this->assertContains(
            "dumpText4",
            $result
        );
    }

    /**
     * Test for ExportSql::exportData
     *
     * @return void
     * @group medium
     */
    public function testExportData()
    {
        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $flags = array();
        $a = new StdClass;
        $a->blob = false;
        $a->numeric = true;
        $a->type = 'ts';
        $a->name = 'name';
        $a->length = 2;
        $flags[] = $a;

        $a = new StdClass;
        $a->blob = false;
        $a->numeric = true;
        $a->type = 'ts';
        $a->name = 'name';
        $a->length = 2;
        $flags[] = $a;

        $a = new StdClass;
        $a->blob = true;
        $a->numeric = false;
        $a->type = 'ts';
        $a->name = 'name';
        $a->length = 2;
        $flags[] = $a;

        $a = new StdClass;
        $a->type = "bit";
        $a->blob = false;
        $a->numeric = false;
        $a->name = 'name';
        $a->length = 2;
        $flags[] = $a;

        $a = new StdClass;
        $a->blob = false;
        $a->numeric = true;
        $a->type = 'timestamp';
        $a->name = 'name';
        $a->length = 2;
        $flags[] = $a;

        $dbi->expects($this->once())
            ->method('getFieldsMeta')
            ->with('res')
            ->will($this->returnValue($flags));

        $dbi->expects($this->any())
            ->method('fieldFlags')
            ->will($this->returnValue('biNAry'));

        $dbi->expects($this->once())
            ->method('tryQuery')
            ->with(
                "SELECT a FROM b WHERE 1",
                null,
                PMA_DatabaseInterface::QUERY_UNBUFFERED
            )
            ->will($this->returnValue('res'));

        $dbi->expects($this->once())
            ->method('numFields')
            ->with('res')
            ->will($this->returnValue(5));

        $dbi->expects($this->at(10))
            ->method('fetchRow')
            ->with('res')
            ->will(
                $this->returnValue(
                    array(null, 'test', '10', '6', "\x00\x0a\x0d\x1a")
                )
            );

        $GLOBALS['dbi'] = $dbi;
        $GLOBALS['sql_compatibility'] = 'MSSQL';
        $GLOBALS['sql_backquotes'] = true;
        $GLOBALS['sql_views_as_tables'] = true;
        $GLOBALS['sql_type'] = 'INSERT';
        $GLOBALS['sql_delayed'] = ' DELAYED';
        $GLOBALS['sql_ignore'] = true;
        $GLOBALS['sql_truncate'] = true;
        $GLOBALS['sql_insert_syntax'] = 'both';
        $GLOBALS['sql_hex_for_binary'] = true;
        $GLOBALS['cfg']['Server']['DisableIS'] = false;

        ob_start();
        $this->object->exportData(
            'db', 'table', "\n", "example.com/err",
            "SELECT a FROM b WHERE 1"
        );
        $result = ob_get_clean();

        $this->assertContains(
            'TRUNCATE TABLE &quot;table&quot;;',
            $result
        );

        $this->assertContains(
            'SET IDENTITY_INSERT &quot;table&quot; ON ;',
            $result
        );

        $this->assertContains(
            'INSERT DELAYED IGNORE INTO &quot;table&quot; (&quot;name&quot;, ' .
            '&quot;name&quot;, &quot;name&quot;, &quot;name&quot;, ' .
            '&quot;name&quot;) VALUES',
            $result
        );

        $this->assertContains(
            '(NULL, test, 0x3130, 0x36, 0x000a0d1a);',
            $result
        );

        $this->assertContains(
            "SET IDENTITY_INSERT &quot;table&quot; OFF;",
            $result
        );

    }

    /**
     * Test for ExportSql::exportData
     *
     * @return void
     * @group medium
     */
    public function testExportDataWithUpdate()
    {
        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $flags = array();
        $a = new StdClass;
        $a->blob = false;
        $a->numeric = true;
        $a->type = 'real';
        $a->name = 'name';
        $a->length = 2;
        $a->table = 'tbl';
        $a->orgname = 'pma';
        $a->primary_key = 1;
        $flags[] = $a;

        $a = new StdClass;
        $a->blob = false;
        $a->numeric = true;
        $a->type = '';
        $a->name = 'name';
        $a->table = 'tbl';
        $a->orgname = 'pma';
        $a->length = 2;
        $a->primary_key = 0;
        $a->unique_key = 1;
        $flags[] = $a;

        $dbi->expects($this->once())
            ->method('getFieldsMeta')
            ->with('res')
            ->will($this->returnValue($flags));

        $dbi->expects($this->any())
            ->method('fieldFlags')
            ->will($this->returnValue('biNAry'));

        $dbi->expects($this->once())
            ->method('tryQuery')
            ->with(
                "SELECT a FROM b WHERE 1",
                null,
                PMA_DatabaseInterface::QUERY_UNBUFFERED
            )
            ->will($this->returnValue('res'));

        $dbi->expects($this->once())
            ->method('numFields')
            ->with('res')
            ->will($this->returnValue(2));

        $dbi->expects($this->at(7))
            ->method('fetchRow')
            ->with('res')
            ->will(
                $this->returnValue(
                    array(null, null)
                )
            );

        $GLOBALS['dbi'] = $dbi;
        $GLOBALS['sql_compatibility'] = 'MSSQL';
        $GLOBALS['sql_backquotes'] = true;
        $GLOBALS['sql_views_as_tables'] = true;
        $GLOBALS['sql_type'] = 'UPDATE';
        $GLOBALS['sql_delayed'] = ' DELAYED';
        $GLOBALS['sql_ignore'] = true;
        $GLOBALS['sql_truncate'] = true;
        $GLOBALS['sql_insert_syntax'] = 'both';
        $GLOBALS['sql_hex_for_binary'] = true;
        $GLOBALS['cfg']['Server']['DisableIS'] = false;

        ob_start();
        $this->object->exportData(
            'db', 'table', "\n", "example.com/err",
            "SELECT a FROM b WHERE 1"
        );
        $result = ob_get_clean();

        $this->assertContains(
            'UPDATE IGNORE &quot;table&quot; SET &quot;name&quot; = NULL,' .
            '&quot;name&quot; = NULL WHERE CONCAT(`tbl`.`pma`) IS NULL;',
            $result
        );

    }

    /**
     * Test for ExportSql::exportData
     *
     * @return void
    */
    public function testExportDataWithIsView()
    {
        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->once())
            ->method('fetchResult')
            ->will($this->returnValue(true));

        $GLOBALS['dbi'] = $dbi;
        $GLOBALS['cfg']['Server']['DisableIS'] = false;
        $GLOBALS['sql_views_as_tables'] = false;
        $GLOBALS['sql_include_comments'] = true;
        $GLOBALS['crlf'] = "\n";

        ob_start();
        $this->assertTrue(
            $this->object->exportData('db', 'tbl', "\n", "err.com", "SELECT")
        );
        $result = ob_get_clean();

        $this->assertContains(
            "-- VIEW  tbl\n",
            $result
        );

        $this->assertContains(
            "-- Data: None\n",
            $result
        );
    }

    /**
     * Test for ExportSql::exportData
     *
     * @return void
    */
    public function testExportDataWithError()
    {
        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $dbi->expects($this->once())
            ->method('getError')
            ->will($this->returnValue('err'));

        $GLOBALS['dbi'] = $dbi;
        $GLOBALS['cfg']['Server']['DisableIS'] = false;
        $GLOBALS['sql_views_as_tables'] = true;
        $GLOBALS['sql_include_comments'] = true;
        $GLOBALS['crlf'] = "\n";

        ob_start();
        $this->assertTrue(
            $this->object->exportData('db', 'table', "\n", "err.com", "SELECT")
        );
        $result = ob_get_clean();

        $this->assertContains(
            "-- Error reading data: (err)\n",
            $result
        );
    }

    /**
     * Test for ExportSql::_makeCreateTableMSSQLCompatible
     *
     * @return void
     */
    public function testMakeCreateTableMSSQLCompatible()
    {

        $query = "CREATE TABLE IF NOT EXISTS (\" date DEFAULT NULL,\n" .
            "\" date DEFAULT NULL\n\" date NOT NULL,\n\" date NOT NULL\n," .
            " \" date NOT NULL DEFAULT 'asd'," .
            " ) unsigned NOT NULL\n, ) unsigned NOT NULL,\n" .
            " ) unsigned DEFAULT NULL\n, ) unsigned DEFAULT NULL,\n" .
            " ) unsigned NOT NULL DEFAULT 'dsa',\n" .
            " \" int(10) DEFAULT NULL,\n" .
            " \" tinyint(0) DEFAULT NULL\n" .
            " \" smallint(10) NOT NULL,\n" .
            " \" bigint(0) NOT NULL\n" .
            " \" bigint(0) NOT NULL DEFAULT '12'\n" .
            " \" float(22,2,) DEFAULT NULL,\n" .
            " \" double DEFAULT NULL\n" .
            " \" float(22,2,) NOT NULL,\n" .
            " \" double NOT NULL\n" .
            " \" double NOT NULL DEFAULT '213'\n";

        $method = new ReflectionMethod(
            'ExportSql', '_makeCreateTableMSSQLCompatible'
        );
        $method->setAccessible(true);
        $result = $method->invoke(
            $this->object, $query
        );

        $this->assertEquals(
            "CREATE TABLE (\" datetime DEFAULT NULL,\n" .
            "\" datetime DEFAULT NULL\n" .
            "\" datetime NOT NULL,\n" .
            "\" datetime NOT NULL\n" .
            ", \" datetime NOT NULL DEFAULT 'asd', ) NOT NULL\n" .
            ", ) NOT NULL,\n" .
            " ) DEFAULT NULL\n" .
            ", ) DEFAULT NULL,\n" .
            " ) NOT NULL DEFAULT 'dsa',\n" .
            " \" int DEFAULT NULL,\n" .
            " \" tinyint DEFAULT NULL\n" .
            " \" smallint NOT NULL,\n" .
            " \" bigint NOT NULL\n" .
            " \" bigint NOT NULL DEFAULT '12'\n" .
            " \" float DEFAULT NULL,\n" .
            " \" float DEFAULT NULL\n" .
            " \" float NOT NULL,\n" .
            " \" float NOT NULL\n" .
            " \" float NOT NULL DEFAULT '213'\n",
            $result
        );
    }

    /**
     * Test for ExportSql::initAlias
     *
     * @return void
    */
    public function testInitAlias()
    {
        $aliases = array(
            'a' => array(
                'alias' => 'aliastest',
                'tables' => array(
                    'foo' => array(
                        'alias' => 'qwerty'
                    ),
                    'bar' => array(
                        'alias' => 'f'
                    )
                )
            )
        );
        $db = 'a';
        $table = null;

        $this->object->initAlias($aliases, $db, $table);
        $this->assertEquals('aliastest', $db);
        $this->assertNull($table);

        $db = 'foo';
        $table = 'qwerty';

        $this->object->initAlias($aliases, $db, $table);
        $this->assertEquals('foo', $db);
        $this->assertEquals('qwerty', $table);

        $db = 'a';
        $table = 'foo';

        $this->object->initAlias($aliases, $db, $table);
        $this->assertEquals('aliastest', $db);
        $this->assertEquals('qwerty', $table);
    }

    /**
     * Test for ExportSql::getAlias
     *
     * @return void
    */
    public function testGetAlias()
    {
        $aliases = array(
            'a' => array(
                'alias' => 'aliastest',
                'tables' => array(
                    'foo' => array(
                        'alias' => 'qwerty',
                        'columns' => array(
                            'baz' => 'p',
                            'pqr' => 'pphymdain'
                        )
                    ),
                    'bar' => array(
                        'alias' => 'f',
                        'columns' => array(
                            'xy' => 'n'
                        )
                    )
                )
            )
        );

        $this->assertEquals(
            'f', $this->object->getAlias($aliases, 'bar')
        );

        $this->assertEquals(
            'aliastest', $this->object->getAlias($aliases, 'a')
        );

        $this->assertEquals(
            'pphymdain', $this->object->getAlias($aliases, 'pqr')
        );

        $this->assertEquals(
            '', $this->object->getAlias($aliases, 'abc')
        );
    }

    /**
     * Test for ExportSql::substituteAlias
     *
     * @return void
    */
    public function testSubstituteAlias()
    {
        $GLOBALS['sql_backquotes'] = '`';
        $sql_query = 'CREATE TABLE `data` ( xyz int )';
        $data = '`data`';
        $alias = 'sample';
        $pos = 19;
        $offset = 0;
        $result =$this->object->substituteAlias(
            $sql_query, $data, $alias, $pos, $offset
        );

        $this->assertEquals(
            'CREATE TABLE `sample` ( xyz int )',
            $result
        );
        $this->assertEquals(2, $offset);

        $GLOBALS['sql_backquotes'] = false;
        $result =$this->object->substituteAlias(
            $sql_query, $data, $alias, $pos
        );

        $this->assertEquals(
            'CREATE TABLE sample ( xyz int )',
            $result
        );

        $GLOBALS['sql_backquotes'] = '`';
        $sql_query = 'CREATE TABLE `sample` ( qwerty int )';
        $data = 'qwerty';
        $alias = 'f';
        $offset = 2;
        $pos = 28 + 2;
        $result =$this->object->substituteAlias(
            $sql_query, $data, $alias, $pos, $offset
        );

        $this->assertEquals(
            'CREATE TABLE `sample` ( `f` int )',
            $result
        );
        $this->assertEquals(-1, $offset);
    }

    /**
     * Test for ExportSql::replaceWithAlias
     *
     * @return void
    */
    public function testReplaceWithAlias()
    {
        $aliases = array(
            'a' => array(
                'alias' => 'aliastest',
                'tables' => array(
                    'foo' => array(
                        'alias' => 'bartest',
                        'columns' => array(
                            'baz' => 'p',
                            'pqr' => 'pphymdain'
                        )
                    ),
                    'bar' => array(
                        'alias' => 'f',
                        'columns' => array(
                            'xy' => 'n'
                        )
                    )
                )
            )
        );

        $GLOBALS['sql_backquotes'] = '`';
        $db = 'a';
        $table = 'foo';
        $sql_query = "CREATE TABLE IF NOT EXISTS foo ("
            . "baz tinyint(3) unsigned NOT NULL COMMENT 'Primary Key',"
            . "xyz varchar(255) COLLATE latin1_general_ci NOT NULL "
            . "COMMENT 'xyz',"
            . "pqr varchar(10) COLLATE latin1_general_ci NOT NULL "
            . "COMMENT 'pqr',"
            . "CONSTRAINT fk_om_dept FOREIGN KEY (baz) "
            . "REFERENCES dept_master (baz),"
            . ") ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE="
            . "latin1_general_ci COMMENT='List' AUTO_INCREMENT=5";
        $result = $this->object->replaceWithAliases(
            $sql_query, $aliases, $db, $table
        );

        $this->assertEquals(
            "CREATE TABLE IF NOT EXISTS `bartest` ("
            . "`p` tinyint(3) unsigned NOT NULL COMMENT 'Primary Key',"
            . "xyz varchar(255) COLLATE latin1_general_ci NOT NULL "
            . "COMMENT 'xyz',"
            . "`pphymdain` varchar(10) COLLATE latin1_general_ci NOT NULL "
            . "COMMENT 'pqr',"
            . "CONSTRAINT fk_om_dept FOREIGN KEY (`p`) "
            . "REFERENCES dept_master (baz),"
            . ") ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE="
            . "latin1_general_ci COMMENT='List' AUTO_INCREMENT=5",
            $result
        );

        $result = $this->object->replaceWithAliases($sql_query, array(), '', '');

        $this->assertEquals(
            "CREATE TABLE IF NOT EXISTS foo ("
            . "baz tinyint(3) unsigned NOT NULL COMMENT 'Primary Key',"
            . "xyz varchar(255) COLLATE latin1_general_ci NOT NULL "
            . "COMMENT 'xyz',"
            . "pqr varchar(10) COLLATE latin1_general_ci NOT NULL "
            . "COMMENT 'pqr',"
            . "CONSTRAINT fk_om_dept FOREIGN KEY (baz) "
            . "REFERENCES dept_master (baz),"
            . ") ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE="
            . "latin1_general_ci COMMENT='List' AUTO_INCREMENT=5",
            $result
        );

        $GLOBALS['sql_backquotes'] = null;
        $table = 'bar';
        $sql_query = "CREATE TRIGGER `BEFORE_bar_INSERT` "
            . "BEFORE INSERT ON `bar`\r\n"
            . "FOR EACH ROW BEGIN\r\n"
            . "SET @cnt=(SELECT count(*) FROM bar WHERE "
            . "xy=NEW.xy AND id=NEW.id AND "
            . "abc=NEW.xy LIMIT 1);\r\n"
            . "IF @cnt<>0 THEN\n"
            . "SET NEW.xy=1;\r\n"
            . "END IF;\nEND\n$$";
        $result = $this->object->replaceWithAliases(
            $sql_query, $aliases, $db, $table
        );

        $this->assertEquals(
            "CREATE TRIGGER `BEFORE_bar_INSERT` "
            . "BEFORE INSERT ON f\n"
            . "FOR EACH ROW BEGIN\n"
            . "SET @cnt=(SELECT count(*) FROM f WHERE "
            . "n=NEW.n AND id=NEW.id AND "
            . "abc=NEW.n LIMIT 1);\n"
            . "IF @cnt<>0 THEN\n"
            . "SET NEW.n=1;\n"
            . "END IF;\nEND\n$$",
            $result
        );
    }
}
?>
