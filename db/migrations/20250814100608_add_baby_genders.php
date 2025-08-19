<?php

use Phinx\Migration\AbstractMigration;

class AddBabyGenders extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(
            '
UPDATE genders
SET baby = 1
WHERE id = 9;'
        );

        $this->execute(
            '
INSERT INTO genders
  (id,label,shortlabel,seq,created,created_by,modified,modified_by,male,female,adult,child,baby,color)
VALUES
  (7,"Baby Girl","Girl",8,NULL,NULL,NULL,NULL,0,1,0,0,1,"1"),
  (8,"Baby Boy","Boy",8,NULL,NULL,NULL,NULL,1,0,0,0,1,"1");'
        );
    }

    public function down(): void
    {
        $this->execute(
            '
UPDATE genders
SET baby = 0
WHERE id = 9;'
        );

        $this->execute('DELETE FROM genders WHERE id IN (7, 8);');
    }
}
