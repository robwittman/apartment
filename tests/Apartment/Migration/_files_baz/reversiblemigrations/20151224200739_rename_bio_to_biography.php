<?php

namespace Baz;

use Apartment\Migration\AbstractMigration;

class RenameBioToBiography extends AbstractMigration
{
    /**
     * Change.
     */
    public function change()
    {
        // users table
        $table = $this->table('users_baz');
        $table->renameColumn('bio', 'biography');
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
