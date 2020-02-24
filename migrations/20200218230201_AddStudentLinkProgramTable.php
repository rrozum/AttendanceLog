<?php

use Phpmig\Migration\Migration;

class AddStudentLinkProgramTable extends Migration
{
    const TABLE = 'student_link_program';

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
                $table->unsignedInteger('program_id');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')
                    ->default($connection->raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
                $table->index(['student_id', 'program_id'], 'student_program_ids');
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
