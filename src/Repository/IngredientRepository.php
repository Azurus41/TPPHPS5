<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    /**
     * @return Ingredient[]
     */
    private function run_dql(string $where = '', array $params = []): array
    {
        return $this->getEntityManager()
            ->createQuery('SELECT i FROM App\Entity\Ingredient i' . $where . ' ORDER BY i.id ASC')
            ->setParameters($params)
            ->getResult()
        ;
    }

    public function findAll_dql(): array
    {
        return $this->run_dql();
    }

    public function find_ingredient_tomate_dql(): array
    {
        return $this->run_dql(' WHERE i.nom = :nom', ['nom' => 'tomate']);
    }

    public function find_ingredient_tomate_5_dql(): array
    {
        return $this->run_dql(' WHERE i.nom = :nom AND i.prix >= :prix', ['nom' => 'tomate', 'prix' => 5]);
    }

    public function find_ingredient_tom_dql(): array
    {
        return $this->run_dql(' WHERE i.nom LIKE :nom', ['nom' => 'tomate%']);
    }

    public function find_ingredient_by_price_dql(float $prix): array
    {
        return $this->run_dql(' WHERE i.prix = :prix', ['prix' => $prix]);
    }

    public function find_ingredient_by_price_and_name_dql(float $prix, string $nom): array
    {
        return $this->run_dql(' WHERE i.prix = :prix AND i.nom = :nom', ['prix' => $prix, 'nom' => $nom]);
    }

    private const SELECT_SQL ='SELECT id, nom, prix, created_at AS createdAt FROM ingredient';

    private function run_sql(string $where = '', array $params = []): array
    {
        $sql = self::SELECT_SQL . $where . ' ORDER BY id ASC';

        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetchAllAssociative();
    }

    public function findAll_sql(): array
    {
        return $this->run_sql();
    }

    public function find_ingredient_tomate_sql(): array
    {
        return $this->run_sql(' WHERE nom = :nom', ['nom' => 'tomate']);
    }

    public function find_ingredient_tomate_5_sql(): array
    {
        return $this->run_sql(' WHERE nom = :nom AND prix >= :prix', ['nom' => 'tomate', 'prix' => 5]);
    }

    public function find_ingredient_tom_sql(): array
    {
        return $this->run_sql(' WHERE nom LIKE :nom', ['nom' => 'tomate%']);
    }

    public function find_ingredient_by_price_sql(float $prix): array
    {
        return $this->run_sql(' WHERE prix = :prix', ['prix' => $prix]);
    }

    public function find_ingredient_by_price_and_name_sql(float $prix, string $nom): array
    {
        return $this->run_sql(' WHERE prix = :prix AND nom = :nom', ['prix' => $prix, 'nom' => $nom]);
    }

    /**
     * @return Ingredient[]
     */
    public function find_ingredient_tomate(): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.nom = :nom')
            ->setParameter('nom', 'tomate')
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Ingredient[]
     */
    public function find_ingredient_tomate_5(): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.nom = :nom')
            ->andWhere('i.prix >= :prix')
            ->setParameter('nom', 'tomate')
            ->setParameter('prix', 5)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Ingredient[]
     */
    public function find_ingredient_tom5(): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.nom LIKE :nom')
            ->setParameter('nom', 'tomate%')
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Ingredient[]
     */
    public function find_ingredient_by_price(float $prix): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.prix = :prix')
            ->setParameter('prix', $prix)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Ingredient[]
     */
    public function find_ingredient_by_price_and_name(float $prix, string $nom): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.nom = :nom')
            ->andWhere('i.prix = :prix')
            ->setParameter('nom', $nom)
            ->setParameter('prix', $prix)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return Ingredient[] Returns an array of Ingredient objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Ingredient
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
