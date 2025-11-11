<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->dropForeignIfExists('m_domains', 'tenant_id');
        $this->dropForeignIfExists('t_contracts', 'tenant_id');
        $this->dropForeignIfExists('t_tenants', 'group_id');
        $this->dropForeignIfExists('t_tenants', 'admin_tenant_id');
        DB::statement('ALTER TABLE t_tenants MODIFY COLUMN id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;');
        DB::statement('ALTER TABLE m_domains MODIFY COLUMN tenant_id BIGINT UNSIGNED NOT NULL;');
        DB::statement('ALTER TABLE t_contracts MODIFY COLUMN tenant_id BIGINT UNSIGNED NOT NULL;');
        Schema::table('t_tenants', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable()->change();
            $table->unsignedBigInteger('admin_tenant_id')->nullable()->change();
        });
        Schema::table('t_tenants', function (Blueprint $table) {
            $table->foreign('group_id')
                ->references('id')
                ->on('m_groups')
                ->nullOnDelete();
            $table->foreign('admin_tenant_id')
                ->references('id')
                ->on('m_admin_tenants')
                ->nullOnDelete();
        });
        Schema::table('m_domains', function (Blueprint $table) {
            $table->foreign('tenant_id')->references('id')->on('t_tenants');
        });
        Schema::table('t_contracts', function (Blueprint $table) {
            $table->foreign('tenant_id')->references('id')->on('t_tenants');
        });
    }

    public function down(): void
    {
        $this->dropForeignIfExists('m_domains', 'tenant_id');
        $this->dropForeignIfExists('t_contracts', 'tenant_id');
        $this->dropForeignIfExists('t_tenants', 'group_id');
        $this->dropForeignIfExists('t_tenants', 'admin_tenant_id');
        DB::statement('ALTER TABLE t_tenants MODIFY COLUMN id VARCHAR(255) NOT NULL;');
        DB::statement('ALTER TABLE m_domains MODIFY COLUMN tenant_id VARCHAR(255) NOT NULL;');
        DB::statement('ALTER TABLE t_contracts MODIFY COLUMN tenant_id VARCHAR(255) NOT NULL;');
        Schema::table('t_tenants', function (Blueprint $table) {
            $table->string('group_id', 255)->nullable()->change();
            $table->string('admin_tenant_id', 255)->nullable()->change();
        });
    }

    private function dropForeignIfExists(string $table, string $column): void
    {
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND COLUMN_NAME = ?
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$table, $column]);
        if (!empty($foreignKeys)) {
            $constraint = $foreignKeys[0]->CONSTRAINT_NAME;
            DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$constraint}");
        }
    }
};
