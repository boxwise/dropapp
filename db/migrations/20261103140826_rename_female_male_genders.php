<?php

use Phinx\Migration\AbstractMigration;

class RenameFemaleMaleGenders extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(
            '
UPDATE genders
SET label = "Women", shortlabel = "Women"
WHERE id = 1;'
        );

        $this->execute(
            '
UPDATE genders
SET label = "Men", shortlabel = "Men"
WHERE id = 2;'
        );
    }

    public function down(): void
    {
        $this->execute(
            '
UPDATE genders
SET label = "Female", shortlabel = "Female"
WHERE id = 1;'
        );

        $this->execute(
            '
UPDATE genders
SET label = "Male", shortlabel = "Male"
WHERE id = 2;'
        );
    }
}
