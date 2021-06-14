<?php

namespace App\Repository;

use App\Entity\CompteRendu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompteRendu|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompteRendu|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompteRendu[]    findAll()
 * @method CompteRendu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteRenduRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompteRendu::class);
    }


    public function findAllWithUser($user)
    {
        $qb = $this->createQueryBuilder('cr');
        $qb
            ->where('cr.user = :user')
            ->setParameter('user', $user);

        return $qb->getQuery()->getResult();
    }


    public function findMedicamentsPresentes($listeCompteRendu)
    {
        $qb = $this->createQueryBuilder('cr');
        $qb 
            ->join('cr.presentation', 'pres')
            ->join('pres.medicament', 'med')
            ->select('cr.dateVisite, med.nomCommercial')
            ->where('cr.id in (:listeCompteRendu)')
            ->setParameter('listeCompteRendu', $listeCompteRendu)
            ->andWhere('cr.dateVisite BETWEEN :start AND :end')
            ->setParameter(':start', "2021-01-01")
            ->setParameter(':end', "2021-12-31");

        return $qb->getQuery()->getResult();
    }


    public function findMedicamentsOfferts($listeCompteRendu)
    {
        $qb = $this->createQueryBuilder('cr');
        $qb 
            ->join('cr.presentation', 'pres')
            ->join('pres.medicament', 'med')
            ->select('cr.dateVisite, med.nomCommercial, med.prixEchantillon, pres.quantiteOfferte')
            ->where('cr.id in (:listeCompteRendu)')
            ->setParameter('listeCompteRendu', $listeCompteRendu)
            ->andWhere('pres.quantiteOfferte > 0');

        return $qb->getQuery()->getResult();

    }


}
