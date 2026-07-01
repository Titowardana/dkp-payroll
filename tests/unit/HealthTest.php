<?php namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

class HealthTest extends CIUnitTestCase
{
    public function testBaseUrlConfigured()
    {
        $this->assertNotEmpty(config('App')->baseURL);
    }

    public function testDatabaseConfigHasTestGroup()
    {
        $db = config('Database');
        $this->assertNotEmpty($db->tests);
        $this->assertEquals('SQLite3', $db->tests['DBDriver']);
    }
}
