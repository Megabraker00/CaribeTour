<?php

namespace Tests;

use App\Models\Status;
use App\Models\Type;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        Status::forgetLookupCache();
        Type::forgetLookupCache();
    }
}
