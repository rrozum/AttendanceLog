<?php

use Phpmig\Migration\Migration;

class AddAttendanceTable extends Migration
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
                $table->unsignedInteger('id', true);
                $table->unsignedInteger('student_id');
                $table->timestamp('date');
                $table->tinyInteger('attendance');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')
                    ->default($connection->raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

                $table->index(['student_id', 'date'], 'student_id_and_date_index');
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
