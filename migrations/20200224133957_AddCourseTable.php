<?php

use Phpmig\Migration\Migration;

class AddCourseTable extends Migration
{
    const TABLE = 'course';
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
                $table->char('name', 255)->index();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')
                    ->default($connection->raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
