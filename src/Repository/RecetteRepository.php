<?php

namespace App\Repository;

use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recette>
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }

    /**
     * @return Recette[]
     */
    public function find_all_recettes_avec_ingredients(): array
    {
        return $this->createQueryBuilder('r')
            ->addSelect('i')
            ->leftJoin('r.ingredients', 'i')
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // ---------- 10 premières recettes avec leurs ingrédients ----------

    public function find_recettes_avec_ingredients_sql(): array
    {
        $sql = 'SELECT r.id, r.nom, r.temps, r.prix, r.difficulte, i.nom AS ingredient_nom, i.prix AS ingredient_prix
                FROM (SELECT * FROM recette ORDER BY id ASC LIMIT 10) r
                JOIN recette_ingredient ri ON ri.recette_id = r.id
                JOIN ingredient i ON i.id = ri.ingredient_id
                ORDER BY r.id ASC, i.id ASC';

        return $this->group_sql($this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAllAssociative());
    }

    /**
     * @return Recette[]
     */
    public function find_recettes_avec_ingredients_dql(): array
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT r, i FROM App\Entity\Recette r JOIN r.ingredients i ORDER BY r.id ASC')
            ->setMaxResults(10)
        ;

        return iterator_to_array(new Paginator($query, true));
    }

    /**
     * @return Recette[]
     */
    public function find_recettes_avec_ingredients(): array
    {
        $qb = $this->createQueryBuilder('r')
            ->addSelect('i')
            ->join('r.ingredients', 'i')
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
        ;

        return iterator_to_array(new Paginator($qb, true));
    }

    // ---------- recettes qui ont exactement 5 ingrédients ----------

    public function find_recettes_avec_5_ingredients_sql(): array
    {
        $sql = 'SELECT r.id, r.nom, r.temps, r.prix, r.difficulte, i.nom AS ingredient_nom, i.prix AS ingredient_prix
                FROM recette r
                JOIN recette_ingredient ri ON ri.recette_id = r.id
                JOIN ingredient i ON i.id = ri.ingredient_id
                WHERE r.id IN (SELECT recette_id FROM recette_ingredient GROUP BY recette_id HAVING COUNT(*) = 5)
                ORDER BY r.id ASC, i.id ASC';

        return $this->group_sql($this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAllAssociative());
    }

    /**
     * @return Recette[]
     */
    public function find_recettes_avec_5_ingredients_dql(): array
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT r, i FROM App\Entity\Recette r JOIN r.ingredients i
                 WHERE r.id IN (SELECT r2.id FROM App\Entity\Recette r2 JOIN r2.ingredients i2 GROUP BY r2.id HAVING COUNT(i2.id) = 5)
                 ORDER BY r.id ASC'
            )
            ->getResult()
        ;
    }

    /**
     * @return Recette[]
     */
    public function find_recettes_avec_5_ingredients(): array
    {
        $sous_requete = $this->createQueryBuilder('r2')
            ->select('r2.id')
            ->join('r2.ingredients', 'i2')
            ->groupBy('r2.id')
            ->having('COUNT(i2.id) = 5')
        ;

        $qb = $this->createQueryBuilder('r');

        return $qb
            ->addSelect('i')
            ->join('r.ingredients', 'i')
            ->andWhere($qb->expr()->in('r.id', $sous_requete->getDQL()))
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Regroupe les lignes SQL (1 ligne par recette + ingrédient) en 1 tableau par recette.
     */
    private function group_sql(array $rows): array
    {
        $recettes = [];
        foreach ($rows as $row) {
            $id = $row['id'];
            if (!isset($recettes[$id])) {
                $recettes[$id] = [
                    'id' => $id,
                    'nom' => $row['nom'],
                    'temps' => $row['temps'],
                    'prix' => $row['prix'],
                    'difficulte' => $row['difficulte'],
                    'ingredients' => [],
                ];
            }
            $recettes[$id]['ingredients'][] = ['nom' => $row['ingredient_nom'], 'prix' => $row['ingredient_prix']];
        }

        return array_values($recettes);
    }
}
