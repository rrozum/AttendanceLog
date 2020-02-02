<?php

use Phpmig\Migration\Migration;

class AddTableStudent extends Migration
{
    const TABLE = 'student';

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
                $table->unsignedInteger('id')->autoIncrement();
                $table->char('name', 250);
                $table->timestamp('birth');
                $table->char('phone', 15)->nullable();
                $table->char('email', 50)->nullable();
                $table->char('type', 50)->nullable();
                $table->unsignedInteger('course_id');
                $table->unsignedInteger('school_id');
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
