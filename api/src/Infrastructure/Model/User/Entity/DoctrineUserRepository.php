<?php

declare(strict_types=1);


namespace Api\Infrastructure\Model\User\Entity;


use Api\Model\EntityNotFoundException;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\User;
use Api\Model\User\Entity\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineUserRepository implements UserRepository
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @var EntityRepository
     */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(User::class);
    }

    /**
     * @param Email $email
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @return bool
     */
    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('t')
        ->select('COUNT(t.id)')
        ->andWhere('t.email = :email')
        ->setParameter(':email', $email->getEmail())
        ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param User $user
     */
    public function add(User $user): void
    {
        $this->em->persist($user);
    }

    /**
     * @param Email $email
     *
     * @throws EntityNotFoundException
     * @return User
     */
    public function getUserByEmail(Email $email): User
    {
        if (!$user = $this->repo->findOneBy(['email' => $email->getEmail()])) {
            throw new EntityNotFoundException('User is not found.');
        }

        return $user;
    }
}