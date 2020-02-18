<?php

use Phpmig\Migration\Migration;

class AddProgramTable extends Migration
{
    const TABLE = 'program';

    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();

        /** @var \Illuminate\Database\Capsule\Manager $db */
        $db = $container['db'];
        $schema = $db->schema();
        $connection = $schema->getConnection();

        $schema->create(
            self::TABLE,
            function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->unsignedInteger('id', true);
                $table->char('name', 255);
            }
        );
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();
        /** @var \Illuminate\Database\Capsule\Manager $db */
        $db = $container['db'];
        $schema = $db->schema();
        $schema->dropIfExists(self::TABLE);
    }
}
