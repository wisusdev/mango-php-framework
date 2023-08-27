<?php

namespace Database\migrations;

use Core\Application;

class m0002_add_token_to_users_table
{
    private mixed $builder;

	/**
	 * @throws \Exception
	 */
	public function __construct()
    {
        $this->builder = Application::get('database');
    }

    public function up(): void
	{
        $query = "ALTER TABLE users ADD COLUMN token TEXT NULL AFTER password";
        $this->builder->execute($query);
    }

    public function down(): void
	{
        $query = "DROP TABLE users;";
        $this->builder->execute($query);
    }
}