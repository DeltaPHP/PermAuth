<?php

use Phinx\Migration\AbstractMigration;

class PermAuthInitPg extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE perm_auth_series
(
  id serial NOT NULL,
  series text,
  "user" integer,
  created timestamp with time zone,
  CONSTRAINT perm_auth_series_pkey PRIMARY KEY (id),
  CONSTRAINT perm_auth_series_user_fkey FOREIGN KEY ("user")
      REFERENCES users (id) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
);

CREATE TABLE perm_auth_tokens
(
  id serial NOT NULL,
  token text,
  series integer,
  "user" integer,
  created timestamp with time zone,
  CONSTRAINT perm_auth_tokens_pkey PRIMARY KEY (id),
  CONSTRAINT perm_auth_tokens_serial_fkey FOREIGN KEY (series)
      REFERENCES perm_auth_series (id) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT perm_auth_tokens_user_fkey FOREIGN KEY ("user")
      REFERENCES users (id) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
);
SQL;
        $this->execute($sql);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}