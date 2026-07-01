<?php namespace Tests\Support;

use CodeIgniter\Test\CIDatabaseTestCase;

class DatabaseTestCase extends CIDatabaseTestCase
{
    protected $refresh = true;
    protected $seed = 'Tests\Support\Database\Seeds\TestSeeder';
    protected $basePath = SUPPORTPATH . 'Database/';
    protected $namespace = 'Tests\Support';
}
