<?php

namespace App\Entity\Amap;

use Doctrine\ORM\Mapping as ORM;
/**
 * LigneContrat
 * @ORM\Entity()
 */

class LigneContrat extends AbstractLigne

{
    public const path = 'lignecontrat';
    public const title = 'Ligne Contrat';
}
