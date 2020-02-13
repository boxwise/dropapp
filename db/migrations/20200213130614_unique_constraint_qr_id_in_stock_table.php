<?php

use Phinx\Migration\AbstractMigration;

class UniqueConstraintQrIdInStockTable extends AbstractMigration
{
    public function change()
    {
        // find qr_ids which are double and set the qr_id null where the box is not in a visible location or deleted
        $this->execute('
            UPDATE 
                stock s
            LEFT JOIN 
                locations l ON l.id = s.location_id
            INNER JOIN 
                (SELECT
                    subs.qr_id 
                FROM 
                    stock subs
                GROUP BY 
                    subs.qr_id
                HAVING 
                    COUNT(*) >1
                ) s2 ON s.qr_id = s2.qr_id
            SET 
                s.qr_id = null
            WHERE 
                NOT l.visible OR s.deleted;');
        // Find qr_ids which are double and set the qr_id to null where the id is not the last of the boxes
        $this->execute('
            UPDATE 
                stock s
			LEFT JOIN 
				locations l ON l.id = s.location_id
			INNER JOIN 
				(SELECT
					subs.qr_id,
                    MAX(subs.id) as max_id
				FROM 
					stock subs
				GROUP BY 
                    subs.qr_id
                HAVING 
                    COUNT(*) >1
				) s2 ON s.qr_id=s2.qr_id AND s.id != s2.max_id
			SET 
                s.qr_id = null;');
        // add unique constraint to qr_id column
        $this->table('stock')
            ->addIndex(['qr_id'], ['name' => 'qr_id_unique', 'unique' => true])
            ->save()
        ;
    }
}
