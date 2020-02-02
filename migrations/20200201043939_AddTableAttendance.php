<?php

use Phpmig\Migration\Migration;

class AddTableAttendance extends Migration
{
    const TABLE = 'attendance';

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
            function (\Illuminate\Database\Schema\Blueprint $table) use ($connection) {
                $table->unsignedInteger('student_id')->primary();
                $table->timestamp('date');
                $table->tinyInteger('attend');
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
