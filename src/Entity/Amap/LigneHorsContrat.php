<?php

namespace App\Entity\Amap;

use Doctrine\ORM\Mapping as ORM;
/**
 * LigneHorsContrat
 * @ORM\Entity()
 */

class LigneHorsContrat extends AbstractLigne
{
    public const path = 'lignehorscontrat';
    public const title = 'Ligne Hors Contrat';
}
