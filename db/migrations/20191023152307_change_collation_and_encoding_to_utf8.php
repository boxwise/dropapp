<?php

use Phinx\Migration\AbstractMigration;

class ChangeCollationAndEncodingToUtf8 extends AbstractMigration
{
    public function up(): void
    {
        $this->execute('alter table camps convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table cms_functions convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table genders convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table history convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table locations convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table log convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table need_periods convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table numbers convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table people convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table products convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table qr convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table sizegroup convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table sizes convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table stock convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table tipofday convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table transactions convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table translate_categories convert to character set utf8 collate utf8_general_ci');
        $this->execute('alter table units convert to character set utf8 collate utf8_general_ci');
    }
}
