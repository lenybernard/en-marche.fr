<?php

namespace App\Repository;

use ApiPlatform\Core\DataProvider\PaginatorInterface;
use App\Collection\AdherentCollection;
use App\Collection\CommitteeMembershipCollection;
use App\Entity\Adherent;
use App\Entity\BaseGroup;
use App\Entity\Committee;
use App\Entity\CommitteeCandidacy;
use App\Entity\CommitteeElection;
use App\Entity\CommitteeMembership;
use App\Entity\VotingPlatform\Designation\CandidacyInterface;
use App\Event\Filter\ListFilterObject;
use App\Subscription\SubscriptionTypeEnum;
use App\ValueObject\Genders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CommitteeMembershipRepository extends ServiceEntityRepository
{
    use PaginatorTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommitteeMembership::class);
    }

    /**
     * Returns whether or not the given adherent is already an host of at least
     * one committee.
     */
    public function hostCommittee(Adherent $adherent, Committee $committee = null): bool
    {
        $qb = $this->createQueryBuilder('cm');

        $qb
            ->select('COUNT(cm.uuid)')
            ->where($qb->expr()->in('cm.privilege', CommitteeMembership::getHostPrivileges()))
            ->andWhere('cm.adherent = :adherent')
            ->setParameter('adherent', $adherent)
        ;

        if ($committee) {
            $qb
                ->andWhere('cm.committee = :committee')
                ->setParameter('committee', $committee)
            ;
        }

        return (int) $qb->getQuery()->getSingleScalarResult() >= 1;
    }

    /**
     * Returns whether or not the given adherent is already the supervisor of at
     * least one committee.
     */
    public function superviseCommittee(Adherent $adherent, Committee $committee = null)
    {
        $qb = $this->createQueryBuilder('cm');

        $qb
            ->select('COUNT(cm.uuid)')
            ->where('cm.privilege = :supervisor')
            ->andWhere('cm.adherent = :adherent')
            ->setParameter('adherent', $adherent)
            ->setParameter('supervisor', CommitteeMembership::COMMITTEE_SUPERVISOR)
        ;

        if ($committee) {
            $qb
                ->andWhere('cm.committee = :committee')
                ->setParameter('committee', $committee)
            ;
        }

        return (int) $qb->getQuery()->getSingleScalarResult() >= 1;
    }

    public function findActivityMemberships(Adherent $adherent, int $page = 1, int $limit = 5): PaginatorInterface
    {
        $queryBuilder = $this
            ->createQueryBuilder('cm')
            ->innerJoin('cm.committee', 'c')
            ->where('cm.adherent = :adherent')
            ->andWhere('c.status = :approved')
            ->andWhere('c.approvedAt IS NOT NULL')
            ->setParameter('adherent', $adherent)
            ->setParameter('approved', BaseGroup::APPROVED)
        ;

        return $this->configurePaginator(
            $queryBuilder,
            $page,
            $limit
        );
    }

    public function findMemberships(Adherent $adherent): CommitteeMembershipCollection
    {
        $query = $this
            ->createQueryBuilder('cm')
            ->where('cm.adherent = :adherent')
            ->setParameter('adherent', $adherent)
            ->getQuery()
        ;

        return new CommitteeMembershipCollection($query->getResult());
    }

    public function findMembershipsForActiveCommittees(Adherent $adherent): CommitteeMembershipCollection
    {
        $query = $this
            ->createQueryBuilder('cm')
            ->innerJoin('cm.committee', 'committee')
            ->addSelect('committee')
            ->where('cm.adherent = :adherent')
            ->andWhere('committee.status = :status')
            ->setParameter('adherent', $adherent)
            ->setParameter('status', Committee::APPROVED)
            ->addOrderBy('cm.privilege', 'DESC')
            ->getQuery()
        ;

        return new CommitteeMembershipCollection($query->getResult());
    }

    public function findMembership(Adherent $adherent, Committee $committee): ?CommitteeMembership
    {
        $query = $this
            ->createMembershipQueryBuilder($adherent, $committee)
            ->getQuery()
        ;

        return $query->getOneOrNullResult();
    }

    /**
     * @return CommitteeMembership[]
     */
    public function findVotingMemberships(Committee $committee): array
    {
        return $this
            ->createQueryBuilder('cm')
            ->addSelect('adherent')
            ->innerJoin('cm.adherent', 'adherent')
            ->where('cm.committee = :committee')
            ->andWhere('cm.enableVote = :true')
            ->setParameters([
                'committee' => $committee,
                'true' => true,
            ])
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Creates the query builder to fetch the membership relationship between
     * an adherent and a committee.
     */
    private function createMembershipQueryBuilder(Adherent $adherent, Committee $committee): QueryBuilder
    {
        return $this
            ->createQueryBuilder('cm')
            ->where('cm.adherent = :adherent')
            ->andWhere('cm.committee = :committee')
            ->setParameter('adherent', $adherent)
            ->setParameter('committee', $committee)
        ;
    }

    /**
     * Returns the number of host members for the given committee.
     */
    public function countHostMembers(Committee $committee): int
    {
        return $this->countMembers($committee, CommitteeMembership::getHostPrivileges());
    }

    public function countSupervisorMembers(Committee $committee): int
    {
        return $this->countMembers($committee, [CommitteeMembership::COMMITTEE_SUPERVISOR]);
    }

    public function countMembers(Committee $committee, array $privileges): int
    {
        return (int) $this->createQueryBuilder('cm')
            ->select('COUNT(cm.uuid)')
            ->where('cm.committee = :committee')
            ->andWhere('cm.privilege IN (:privileges)')
            ->setParameter('committee', $committee)
            ->setParameter('privileges', $privileges)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Returns the list of all hosts memberships of a committee.
     */
    public function findHostMemberships(Committee $committee): CommitteeMembershipCollection
    {
        return $this->findPrivilegedMemberships($committee, [CommitteeMembership::COMMITTEE_HOST]);
    }

    public function findSupervisorMembership(Committee $committee): ?CommitteeMembership
    {
        return $this->findPrivilegedMemberships($committee, [CommitteeMembership::COMMITTEE_SUPERVISOR])->get(0);
    }

    public function findSupervisor(Committee $committee): ?Adherent
    {
        return $this->findPrivilegedMembers($committee, [CommitteeMembership::COMMITTEE_SUPERVISOR])->get(0);
    }

    /**
     * Returns the list of all hosts memberships of a committee.
     */
    public function findHostMembers(Committee $committee): AdherentCollection
    {
        return $this->findPrivilegedMembers($committee, CommitteeMembership::getHostPrivileges());
    }

    /**
     * Finds the list of all committee followers memberships.
     *
     * @param Committee $committee    The committee
     * @param bool      $includeHosts Whether or not to include committee hosts as followers
     */
    public function findFollowerMemberships(
        Committee $committee,
        bool $includeHosts = true
    ): CommitteeMembershipCollection {
        $privileges = [CommitteeMembership::COMMITTEE_FOLLOWER];
        if ($includeHosts) {
            $privileges = array_merge($privileges, CommitteeMembership::getHostPrivileges());
        }

        return $this->findPrivilegedMemberships($committee, $privileges);
    }

    /**
     * Finds the list of all committee followers.
     *
     * @param Committee $committee    The committee UUID
     * @param bool      $includeHosts Whether or not to include committee hosts as followers
     */
    public function findFollowers(Committee $committee, bool $includeHosts = true): AdherentCollection
    {
        $privileges = [CommitteeMembership::COMMITTEE_FOLLOWER];
        if ($includeHosts) {
            $privileges = array_merge($privileges, CommitteeMembership::getHostPrivileges());
        }

        return $this->findPrivilegedMembers($committee, $privileges);
    }

    /**
     * Returns the list of all privileged memberships of a committee.
     *
     * @param Committee $committee  The committee
     * @param array     $privileges An array of privilege constants (see {@link : CommitteeMembership}
     */
    private function findPrivilegedMemberships(Committee $committee, array $privileges): CommitteeMembershipCollection
    {
        $qb = $this->createQueryBuilder('cm');

        $query = $qb
            ->where('cm.committee = :committee')
            ->andWhere($qb->expr()->in('cm.privilege', $privileges))
            ->orderBy('cm.joinedAt', 'ASC')
            ->setParameter('committee', $committee)
            ->getQuery()
        ;

        return new CommitteeMembershipCollection($query->getResult());
    }

    /**
     * Returns the list of all privileged members of a committee.
     *
     * @param Committee $committee  The committee
     * @param array     $privileges An array of privilege constants (see {@link : CommitteeMembership}
     */
    private function findPrivilegedMembers(Committee $committee, array $privileges): AdherentCollection
    {
        $qb = $this->createQueryBuilder('cm');

        $query = $qb
            ->select('cm', 'adherent')
            ->leftJoin('cm.adherent', 'adherent')
            ->where('cm.committee = :committee')
            ->andWhere($qb->expr()->in('cm.privilege', $privileges))
            ->orderBy('cm.privilege', 'DESC')
            ->addOrderBy('cm.joinedAt', 'ASC')
            ->setParameter('committee', $committee)
            ->getQuery()
        ;

        return $this->createAdherentCollection($query);
    }

    /**
     * Returns the list of all members of a committee.
     */
    public function findMembers(Committee $committee): AdherentCollection
    {
        return $this->createAdherentCollection($this->createCommitteeMembershipsQueryBuilder($committee)->getQuery());
    }

    /**
     * Returns the list of all members of $sourceCommittee
     * that does not already belongs to list of all members of $destinationCommittee
     *
     * NOTE: this returns poorly hydrated instances of Adherent, but is optimized for merging of committees
     *
     * @return CommitteeMembership[]
     */
    public function findMembersToMerge(Committee $sourceCommittee, Committee $destinationCommittee): array
    {
        return $this
            ->createQueryBuilder('cm_src')
            ->select('PARTIAL adherent.{id, uuid, emailAddress, firstName, lastName}')
            ->addSelect('PARTIAL cm_src.{id, joinedAt}')
            ->innerJoin('cm_src.adherent', 'adherent')
            ->leftJoin(CommitteeMembership::class, 'cm_dest', Join::WITH, 'cm_dest.adherent = adherent AND cm_dest.committee = :dest_committee')
            ->where('cm_dest.id IS NULL AND cm_src.committee = :src_committee')
            ->setParameters([
                'src_committee' => $sourceCommittee,
                'dest_committee' => $destinationCommittee,
            ])
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Returns the list of all committee memberships of a committee.
     */
    public function findCommitteeMemberships(Committee $committee): CommitteeMembershipCollection
    {
        return new CommitteeMembershipCollection(
            $this
                ->createCommitteeMembershipsQueryBuilder($committee)
                ->addSelect('a')
                ->getQuery()
                ->getResult()
        );
    }

    /**
     * @return CommitteeMembership[]|PaginatorInterface|iterable
     */
    public function getCommitteeMembershipsPaginator(
        Committee $committee,
        ListFilterObject $filter = null,
        int $page = 1,
        ?int $limit = 30
    ): iterable {
        $qb = $this
            ->createCommitteeMembershipsQueryBuilder($committee)
            ->addSelect('a')
            ->addSelect('GROUP_CONCAT(st.code) AS HIDDEN st_codes')
            ->leftJoin('a.subscriptionTypes', 'st')
            ->groupBy('a.id')
        ;

        if ($filter) {
            if ($filter->getAgeMin() || $filter->getAgeMax()) {
                $now = new \DateTimeImmutable();

                if ($filter->getAgeMin()) {
                    $qb
                        ->andWhere('a.birthdate <= :min_birth_date')
                        ->setParameter('min_birth_date', $now->sub(new \DateInterval(sprintf('P%dY', $filter->getAgeMin()))))
                    ;
                }

                if ($filter->getAgeMax()) {
                    $qb
                        ->andWhere('a.birthdate >= :min_birth_date')
                        ->setParameter('min_birth_date', $now->sub(new \DateInterval(sprintf('P%dY', $filter->getAgeMax()))))
                    ;
                }
            }

            if (null !== $filter->isSubscribed()) {
                $subscriptionCondition = 'st_codes LIKE :subscription_code';
                if (false === $filter->isSubscribed()) {
                    $subscriptionCondition = 'st_codes IS NULL OR st_codes NOT LIKE :subscription_code';
                }

                $qb
                    ->having($subscriptionCondition)
                    ->setParameter('subscription_code', '%'.SubscriptionTypeEnum::LOCAL_HOST_EMAIL.'%')
                ;
            }

            if ($filter->getFirstName()) {
                $qb
                    ->andWhere('a.firstName = :first_name')
                    ->setParameter('first_name', $filter->getFirstName())
                ;
            }

            if ($filter->getLastName()) {
                $qb
                    ->andWhere('a.lastName = :last_name')
                    ->setParameter('last_name', $filter->getLastName())
                ;
            }

            if ($filter->getRegisteredSince()) {
                $qb
                    ->andWhere('a.registeredAt >= :registered_since')
                    ->setParameter('registered_since', $filter->getRegisteredSince())
                ;
            }

            if ($filter->getRegisteredUntil()) {
                $qb
                    ->andWhere('a.registeredAt <= :registered_until')
                    ->setParameter('registered_until', $filter->getRegisteredUntil())
                ;
            }

            if ($filter->getJoinedSince()) {
                $qb
                    ->andWhere('cm.joinedAt >= :joined_since')
                    ->setParameter('joined_since', $filter->getJoinedSince())
                ;
            }

            if ($filter->getJoinedUntil()) {
                $qb
                    ->andWhere('cm.joinedAt <= :joined_until')
                    ->setParameter('joined_until', $filter->getJoinedUntil())
                ;
            }

            if ($filter->getCity()) {
                $qb
                    ->andWhere('(a.postAddress.cityName = :city OR a.postAddress.postalCode = :city)')
                    ->setParameter('city', $filter->getCity())
                ;
            }

            if ($filter->getVotersOnly()) {
                $qb
                    ->andWhere('cm.enableVote = :enable_vote')
                    ->setParameter('enable_vote', true)
                ;
            }

            if ($filter->getSort()) {
                $qb->orderBy('cm.'.$filter->getSort(), $filter->getOrder() ?? 'ASC');
            }
        }

        if (!$limit) {
            return $qb->getQuery()->getResult();
        }

        return $this->configurePaginator($qb, $page, $limit);
    }

    /**
     * Creates a QueryBuilder instance to fetch memberships of a committee.
     *
     * @param Committee $committee The committee for which the memberships to fetch belong
     * @param string    $alias     The custom root alias for the query
     */
    private function createCommitteeMembershipsQueryBuilder(Committee $committee, string $alias = 'cm'): QueryBuilder
    {
        return $this
            ->createQueryBuilder($alias)
            ->leftJoin($alias.'.adherent', 'a')
            ->where($alias.'.committee = :committee')
            ->orderBy($alias.'.privilege', 'DESC')
            ->addOrderBy('a.firstName', 'ASC')
            ->setParameter('committee', $committee)
        ;
    }

    /**
     * Creates an AdherentCollection instance with the results of a Query.
     *
     * The query must return a list of CommitteeMembership entities.
     *
     * @param Query $query The query to execute
     */
    private function createAdherentCollection(Query $query): AdherentCollection
    {
        return new AdherentCollection(
            array_map(
                function (CommitteeMembership $membership) {
                    return $membership->getAdherent();
                },
                $query->getResult()
            )
        );
    }

    /**
     * @return string[]
     */
    public function findCommitteesUuidByHostFirstName(string $firstName): array
    {
        return $this->findCommitteesUuid([
            'firstName' => $firstName,
            'privileges' => CommitteeMembership::getHostPrivileges(),
        ]);
    }

    /**
     * @return string[]
     */
    public function findCommitteesUuidByHostLastName(string $lastName): array
    {
        return $this->findCommitteesUuid([
            'lastName' => $lastName,
            'privileges' => CommitteeMembership::getHostPrivileges(),
        ]);
    }

    /**
     * @return string[]
     */
    public function findCommitteesUuidByHostEmailAddress(string $emailAddress): array
    {
        return $this->findCommitteesUuid([
            'emailAddress' => $emailAddress,
            'privileges' => CommitteeMembership::getHostPrivileges(),
        ]);
    }

    public function findCommitteesUuid(array $criteria): array
    {
        $qb = $this
            ->createQueryBuilder('cm')
            ->select('c.uuid')
            ->innerJoin('cm.committee', 'c')
        ;

        if (!empty($criteria['privileges'])) {
            $qb
                ->andWhere('cm.privilege IN (:privileges)')
                ->setParameter('privileges', (array) $criteria['privileges'])
            ;
        }

        if (isset($criteria['firstName']) || isset($criteria['lastName']) || isset($criteria['emailAddress'])) {
            $qb->innerJoin('cm.adherent', 'a');

            if (isset($criteria['firstName'])) {
                $qb
                    ->andWhere('a.firstName LIKE :firstName')
                    ->setParameter('firstName', '%'.$criteria['firstName'].'%')
                ;
            }

            if (isset($criteria['lastName'])) {
                $qb
                    ->andWhere('a.lastName LIKE :lastName')
                    ->setParameter('lastName', '%'.$criteria['lastName'].'%')
                ;
            }

            if (isset($criteria['emailAddress'])) {
                $qb
                    ->andWhere('a.emailAddress LIKE :emailAddress')
                    ->setParameter('emailAddress', '%'.$criteria['emailAddress'].'%')
                ;
            }
        }

        return array_map(function (UuidInterface $uuid) {
            return $uuid->toString();
        }, array_column($qb->getQuery()->getArrayResult(), 'uuid'));
    }

    public function isAdherentInCommittee(Adherent $adherent, Committee $committee): bool
    {
        return 0 !== $this->count(['adherent' => $adherent, 'committee' => $committee]);
    }

    /**
     * @return CommitteeMembership[]
     */
    public function getCandidacyMemberships(CommitteeElection $election): array
    {
        return $this->createQueryBuilder('m')
            ->addSelect('a', 'c')
            ->innerJoin('m.committeeCandidacies', 'c')
            ->innerJoin('m.adherent', 'a')
            ->where('c.committeeElection = :election')
            ->setParameter('election', $election)
            ->getQuery()
            ->getResult()
        ;
    }

    public function updateVoteStatusForAdherents(Committee $committee, array $adherents, bool $value): void
    {
        $this->createQueryBuilder('cm')
            ->update()
            ->set('cm.enableVote', ':value')
            ->Where('cm IN (:memberships)')
            ->setParameters([
                'memberships' => $this->findBy([
                    'adherent' => $adherents,
                    'committee' => $committee,
                ]),
                'value' => true === $value ?: null,
            ])
            ->getQuery()
            ->execute()
        ;
    }

    public function committeeHasVoters(Committee $committee): bool
    {
        return (bool) $this->createQueryBuilder('cm')
            ->select('COUNT(1)')
            ->where('cm.committee = :committee AND cm.enableVote = :true')
            ->setParameters([
                'committee' => $committee,
                'true' => true,
            ])
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @return CommitteeMembership[]
     */
    public function findVotingForSupervisorMemberships(Committee $committee, \DateTimeImmutable $refDate): array
    {
        return $this->createQueryBuilderForVotingMemberships($committee, $refDate)
            ->getQuery()
            ->getResult()
        ;
    }

    public function committeeHasVotersForSupervisorElection(Committee $committee, \DateTimeImmutable $refDate): bool
    {
        return (bool) $this->createQueryBuilderForVotingMemberships($committee, $refDate)
            ->select('COUNT(1)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @return CommitteeMembership[]
     */
    public function findAvailableMemberships(CommitteeCandidacy $candidacy, string $query): array
    {
        $membership = $candidacy->getCommitteeMembership();
        $refDate = $candidacy->getElection()->getVoteEndDate() ?? new \DateTime();

        return $this
            ->createQueryBuilder('membership')
            ->addSelect('adherent')
            ->innerJoin('membership.adherent', 'adherent')
            ->leftJoin('membership.committeeCandidacies', 'candidacy', Join::WITH, 'candidacy.committeeMembership = membership AND candidacy.committeeElection = :election')
            ->where('membership.committee = :committee')
            ->andWhere('candidacy IS NULL OR candidacy.status = :candidacy_draft_status')
            ->andWhere('membership.id != :membership_id')
            ->andWhere('adherent.gender = :gender AND adherent.status = :adherent_status')
            ->andWhere('(adherent.firstName LIKE :query OR adherent.lastName LIKE :query)')
            ->andWhere('adherent.certifiedAt IS NOT NULL AND adherent.registeredAt <= :registration_limit_date AND membership.joinedAt <= :limit_date')
            ->setParameters([
                'query' => sprintf('%s%%', $query),
                'candidacy_draft_status' => CandidacyInterface::STATUS_DRAFT,
                'election' => $candidacy->getElection(),
                'committee' => $membership->getCommittee(),
                'membership_id' => $membership->getId(),
                'gender' => $candidacy->isFemale() ? Genders::MALE : Genders::FEMALE,
                'adherent_status' => Adherent::ENABLED,
                'registration_limit_date' => (clone $refDate)->modify('-3 months'),
                'limit_date' => (clone $refDate)->modify('-30 days'),
            ])
            ->orderBy('adherent.lastName')
            ->addOrderBy('adherent.firstName')
            ->getQuery()
            ->getResult()
        ;
    }

    private function createQueryBuilderForVotingMemberships(
        Committee $committee,
        \DateTimeImmutable $refDate
    ): QueryBuilder {
        return $this->createQueryBuilder('cm')
            ->innerJoin('cm.adherent', 'a')
            ->where('cm.committee = :committee')
            ->andWhere('cm.joinedAt <= :joined_at_min')
            ->andWhere('a.registeredAt <= :registered_at_min')
            ->andWhere('a.certifiedAt IS NOT NULL')
            ->setParameters([
                'committee' => $committee,
                'joined_at_min' => $refDate->modify('-30 days'),
                'registered_at_min' => $refDate->modify('-3 months'),
            ])
        ;
    }
}
