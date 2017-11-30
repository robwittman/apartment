<?php

namespace Baz;

use Apartment\Migration\AbstractMigration;

class RenameInfoTableToStatusesTable extends AbstractMigration
{
    /**
     * Change.
     */
    public function change()
    {
        // users table
        $table = $this->table('info_baz');
        $table->rename('statuses_baz');
    }

    /**
     * Migrate Up.
     */
    public function up()
    {
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
    }
}
