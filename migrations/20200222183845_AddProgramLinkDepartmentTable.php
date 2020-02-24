<?php

use Phpmig\Migration\Migration;

class AddProgramLinkDepartmentTable extends Migration
{
    const TABLE = 'program_link_department';

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
                $table->unsignedInteger('program_id');
                $table->unsignedInteger('department_id');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')
                    ->default($connection->raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

                $table->index(['program_id', 'department_id'], 'program_department_ids');
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
