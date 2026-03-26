<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhoneAddressToUsers extends Migration
{
    private function columnExists(string $table, string $column): bool
    {
        $row = $this->db->query("SHOW COLUMNS FROM {$table} LIKE '{$column}'")->getFirstRow();
        return $row !== null;
    }

    public function up()
    {
        $phoneExists = $this->columnExists('users', 'phone');
        $addressExists = $this->columnExists('users', 'address');

        if ($phoneExists && $addressExists) {
            return;
        }

        $fields = [
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'email',
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'phone',
            ],
        ];

        if (! $phoneExists && ! $addressExists) {
            $this->forge->addColumn('users', $fields);
            return;
        }

        if (! $phoneExists) {
            $this->forge->addColumn('users', ['phone' => $fields['phone']]);
        }

        if (! $addressExists) {
            $this->forge->addColumn('users', ['address' => $fields['address']]);
        }
    }

    public function down()
    {
        if ($this->columnExists('users', 'phone')) {
            $this->forge->dropColumn('users', 'phone');
        }

        if ($this->columnExists('users', 'address')) {
            $this->forge->dropColumn('users', 'address');
        }
    }
}
