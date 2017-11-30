<?php

namespace Foo\Bar;

use Apartment\Migration\AbstractMigration;

class DifferentClassName extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        // do nothing
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        // do nothing
    }
}
