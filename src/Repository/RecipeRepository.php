<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * This method allows us to find public recipes based on number of recipes
     *
     * @param int|null $nbRecipes
     * @return array
     */
    public function findPublicRecipe(?int $nbRecipes): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.isPublic = 1')
            ->orderBy('r.createdAt', 'DESC');

        if (!$nbRecipes == 0 || !$nbRecipes === null) {
            $queryBuilder->setMaxResults($nbRecipes);
        }

        return $queryBuilder->getQuery()
            ->getResult();

        // Bonjour je voulais juste rapporter une petite erreur
        // quand tu fais de la pagination il ne faut pas lui
        // donner le getResult mais le getQuery pour que la
        // pagination soit bien faîtes en sql dans le cas
        // de la pagination après un getResult il fait que
        // paginer avec un tableau mais tout a été chargé
        // par doctrine et donc il y aura de gros problèmes
        // de performance voir de dépassement de mémoire quand
        // les tables seront conséquentes. Sinon tes vidéos sont
        // super agréable à regarder merci
    }


    public function save(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
