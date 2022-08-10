<?php

namespace App\Repository;

use App\Entity\Exports;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exports>
 *
 * @method Exports|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exports|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exports[]    findAll()
 * @method Exports[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExportsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exports::class);
    }

    public function add(Exports $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Exports $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getRecordsByFilters(array $filters)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('u');
        !empty($filters['export_place']) ? $qb->Andwhere("e.export_date = '{$filters['export_place']}'") : null;
        !empty($filters['date_from']) ? $qb->Andwhere("e.export_date > '{$filters['date_from']}'") : null;
        !empty($filters['date_to']) ? $qb->Andwhere("e.export_date < '{$filters['date_to']}'") : null;
        return $qb->getQuery()->getResult();
    }
}
