<?php

namespace App\Repository;
use App\Entity\Room;
use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }
    public function findPlayersInRoomWithNoWinner(Room $room): array
    {
        // Create a QueryBuilder instance
        $qb = $this->createQueryBuilder('p')
            ->where('p.room = :room') // Filter by room
            ->andWhere('p.winner != :winner') // Filter by winner not equal to 1
            ->setParameter('room', $room)
            ->setParameter('winner', 1)
            ->orderBy('p.id', 'ASC'); // Optional: order by player ID or other criteria

        return $qb->getQuery()->getResult(); // Execute the query and return the result
    }
    //    /**
    //     * @return Player[] Returns an array of Player objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Player
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
