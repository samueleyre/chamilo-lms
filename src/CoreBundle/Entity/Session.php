<?php

declare(strict_types=1);

/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use Chamilo\CourseBundle\Entity\CStudentPublication;
use Database;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={"security"="is_granted('ROLE_ADMIN')"},
 *     normalizationContext={"groups"={"session:read"}, "swagger_definition_name"="Read"},
 *     denormalizationContext={"groups"={"session:write"}},
 *     collectionOperations={
 *         "get"={
 *             "denormalization_context"={
 *                 "groups"={"session:read"},
 *             },
 *         },
 *         "post"={}
 *     },
 *     itemOperations={
 *         "get"={},
 *         "put"={},
 *     }
 * )
 *
 * @ApiFilter(SearchFilter::class, properties={"name":"partial"})
 * @ApiFilter(PropertyFilter::class)
 * @ApiFilter(OrderFilter::class, properties={"id", "name"})
 *
 * @ORM\Table(
 *     name="session",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="name", columns={"name"})
 *     },
 *     indexes={
 *         @ORM\Index(name="idx_id_coach", columns={"id_coach"}),
 *         @ORM\Index(name="idx_id_session_admin_id", columns={"session_admin_id"})
 *     }
 * )
 * @ORM\EntityListeners({"Chamilo\CoreBundle\Entity\Listener\SessionListener"})
 * @ORM\Entity(repositoryClass="Chamilo\CoreBundle\Repository\SessionRepository")
 */
class Session
{
    public const VISIBLE = 1;
    public const READ_ONLY = 2;
    public const INVISIBLE = 3;
    public const AVAILABLE = 4;

    public const STUDENT = 0;
    public const DRH = 1;
    public const COACH = 2;

    /**
     * @Groups({"session:read"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue()
     */
    protected int $id;

    /**
     * @var ArrayCollection|SkillRelCourse[]
     * @ORM\OneToMany(targetEntity="SkillRelCourse", mappedBy="session", cascade={"persist", "remove"})
     */
    protected $skills;

    /**
     * @var ArrayCollection|SessionRelCourse[]
     *
     * @ORM\OrderBy({"position"="ASC"})
     * @ORM\OneToMany(targetEntity="SessionRelCourse", mappedBy="session", cascade={"persist"}, orphanRemoval=true)
     */
    protected $courses;

    /**
     * @var ArrayCollection|SessionRelUser[]
     *
     * @ORM\OneToMany(targetEntity="SessionRelUser", mappedBy="session", cascade={"persist"}, orphanRemoval=true)
     */
    protected $users;

    /**
     * @ORM\OneToMany(
     *     targetEntity="SessionRelCourseRelUser",
     *     mappedBy="session",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     */
    protected Collection $userCourseSubscriptions;

    protected Course $currentCourse;

    /**
     * @var Collection|SkillRelUser[]
     *
     * @ORM\OneToMany(targetEntity="Chamilo\CoreBundle\Entity\SkillRelUser", mappedBy="session", cascade={"persist"})
     */
    protected $issuedSkills;

    /**
     * @var AccessUrlRelSession[]|Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="Chamilo\CoreBundle\Entity\AccessUrlRelSession",
     *     mappedBy="session",
     *     cascade={"persist"}, orphanRemoval=true
     * )
     */
    protected $urls;

    /**
     * @var Collection|ResourceLink[]
     *
     * @ORM\OneToMany(targetEntity="ResourceLink", mappedBy="session", cascade={"remove"}, orphanRemoval=true)
     */
    protected $resourceLinks;

    protected AccessUrl $currentUrl;

    /**
     * @Assert\NotBlank()
     * @Groups({"session:read", "session:write", "session_rel_course_rel_user:read", "document:read"})
     * @ORM\Column(name="name", type="string", length=150)
     */
    protected string $name;

    /**
     * @Groups({"session:read", "session:write"})
     *
     * @ORM\Column(name="description", type="text", nullable=true, unique=false)
     */
    protected ?string $description;

    /**
     * @Groups({"session:read", "session:write"})
     *
     * @ORM\Column(name="show_description", type="boolean", nullable=true)
     */
    protected ?bool $showDescription;

    /**
     * @ORM\Column(name="duration", type="integer", nullable=true)
     */
    protected ?int $duration;

    /**
     * @Groups({"session:read"})
     * @ORM\Column(name="nbr_courses", type="integer", nullable=false, unique=false)
     */
    protected int $nbrCourses;

    /**
     * @Groups({"session:read"})
     * @ORM\Column(name="nbr_users", type="integer", nullable=false, unique=false)
     */
    protected int $nbrUsers;

    /**
     * @Groups({"session:read"})
     * @ORM\Column(name="nbr_classes", type="integer", nullable=false, unique=false)
     */
    protected int $nbrClasses;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CoreBundle\Entity\User")
     * @ORM\JoinColumn(name="session_admin_id", referencedColumnName="id", nullable=true)
     */
    protected User $sessionAdmin;

    /**
     * @Groups({"session:read"})
     * @ORM\Column(name="visibility", type="integer")
     */
    protected int $visibility;

    /**
     * @ORM\Column(name="promotion_id", type="integer", nullable=true, unique=false)
     */
    protected ?int $promotionId;

    /**
     * @Groups({"session:read"})
     * @ORM\Column(name="display_start_date", type="datetime", nullable=true, unique=false)
     */
    protected ?DateTime $displayStartDate;

    /**
     * @Groups({"session:read"})
     * @ORM\Column(name="display_end_date", type="datetime", nullable=true, unique=false)
     */
    protected ?DateTime $displayEndDate;

    /**
     * @ORM\Column(name="access_start_date", type="datetime", nullable=true, unique=false)
     */
    protected ?DateTime $accessStartDate;

    /**
     * @ORM\Column(name="access_end_date", type="datetime", nullable=true, unique=false)
     */
    protected ?DateTime $accessEndDate;

    /**
     * @ORM\Column(name="coach_access_start_date", type="datetime", nullable=true, unique=false)
     */
    protected ?DateTime $coachAccessStartDate;

    /**
     * @ORM\Column(name="coach_access_end_date", type="datetime", nullable=true, unique=false)
     */
    protected ?DateTime $coachAccessEndDate;

    /**
     * @ORM\Column(name="position", type="integer", nullable=false, options={"default":0})
     */
    protected int $position;

    /**
     * @Groups({"session:read"})
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    protected int $status;

    /**
     * @Assert\NotBlank
     * @Groups({"session:read", "session:write"})
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sessionsAsGeneralCoach")
     * @ORM\JoinColumn(name="id_coach", referencedColumnName="id")
     */
    protected User $generalCoach;

    /**
     * @Groups({"session:read", "session:write"})
     * @ORM\ManyToOne(targetEntity="SessionCategory", inversedBy="session")
     * @ORM\JoinColumn(name="session_category_id", referencedColumnName="id")
     */
    protected ?SessionCategory $category;

    /**
     * @ORM\Column(name="send_subscription_notification", type="boolean", nullable=false, options={"default":false})
     */
    protected bool $sendSubscriptionNotification;

    /**
     * @var Collection|CStudentPublication[]
     *
     * @ORM\OneToMany(
     *     targetEntity="Chamilo\CourseBundle\Entity\CStudentPublication",
     *     mappedBy="session",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     */
    protected $studentPublications;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->userCourseSubscriptions = new ArrayCollection();
        $this->studentPublications = new ArrayCollection();
        //$this->items = new ArrayCollection();
        $this->urls = new ArrayCollection();

        $this->description = '';
        $this->nbrClasses = 0;
        $this->nbrUsers = 0;
        $this->nbrCourses = 0;
        $this->sendSubscriptionNotification = false;
        $this->displayStartDate = new DateTime();
        $this->displayEndDate = new DateTime();
        $this->accessStartDate = new DateTime();
        $this->accessEndDate = new DateTime();
        $this->coachAccessStartDate = new DateTime();
        $this->coachAccessEndDate = new DateTime();
        $this->visibility = 1;
        $this->showDescription = false;
        $this->category = null;
        $this->status = 0;
        $this->position = 0;
    }

    public function __toString(): string
    {
        return (string) $this->getName();
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getShowDescription(): bool
    {
        return $this->showDescription;
    }

    public function setShowDescription(bool $showDescription): self
    {
        $this->showDescription = $showDescription;

        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users): self
    {
        $this->users = new ArrayCollection();

        foreach ($users as $user) {
            $this->addUser($user);
        }

        return $this;
    }

    public function addUser(SessionRelUser $user): void
    {
        $user->setSession($this);

        if (!$this->hasUser($user)) {
            $this->users[] = $user;
        }
    }

    /**
     * @param int $status
     */
    public function addUserInSession($status, User $user): self
    {
        $sessionRelUser = new SessionRelUser();
        $sessionRelUser->setSession($this);
        $sessionRelUser->setUser($user);
        $sessionRelUser->setRelationType($status);

        $this->addUser($sessionRelUser);

        return $this;
    }

    /**
     * @return bool
     */
    public function hasUser(SessionRelUser $subscription)
    {
        if ($this->getUsers()->count()) {
            $criteria = Criteria::create()->where(
                Criteria::expr()->eq('user', $subscription->getUser())
            )->andWhere(
                Criteria::expr()->eq('session', $subscription->getSession())
            )->andWhere(
                Criteria::expr()->eq('relationType', $subscription->getRelationType())
            );

            $relation = $this->getUsers()->matching($criteria);

            return $relation->count() > 0;
        }

        return false;
    }

    /**
     * @return ArrayCollection
     */
    public function getCourses()
    {
        return $this->courses;
    }

    public function setCourses(ArrayCollection $courses): void
    {
        $this->courses = new ArrayCollection();

        foreach ($courses as $course) {
            $this->addCourses($course);
        }
    }

    public function addCourses(SessionRelCourse $course): void
    {
        $course->setSession($this);
        $this->courses[] = $course;
    }

    public function hasCourse(Course $course): bool
    {
        if ($this->getCourses()->count()) {
            $criteria = Criteria::create()->where(
                Criteria::expr()->eq('course', $course)
            );
            $relation = $this->getCourses()->matching($criteria);

            return $relation->count() > 0;
        }

        return false;
    }

    /**
     * Check for existence of a relation (SessionRelCourse) between a course and this session.
     *
     * @return bool whether the course is related to this session
     */
    public function isRelatedToCourse(Course $course): bool
    {
        return null !== Database::getManager()->getRepository(SessionRelCourse::class)->findOneBy([
            'session' => $this,
            'course' => $course,
        ]);
    }

    /**
     * Remove $course.
     *
     * @param SessionRelCourse $course
     */
    public function removeCourses($course): void
    {
        foreach ($this->courses as $key => $value) {
            if ($value->getId() === $course->getId()) {
                unset($this->courses[$key]);
            }
        }
    }

    /**
     * Remove course subscription for a user.
     * If user status in session is student, then decrease number of course users.
     */
    public function removeUserCourseSubscription(User $user, Course $course): void
    {
        /** @var SessionRelCourseRelUser $courseSubscription */
        foreach ($this->userCourseSubscriptions as $i => $courseSubscription) {
            if ($courseSubscription->getCourse()->getId() === $course->getId() &&
                $courseSubscription->getUser()->getId() === $user->getId()) {
                if (self::STUDENT === $this->userCourseSubscriptions[$i]->getStatus()) {
                    $sessionCourse = $this->getCourseSubscription($course);

                    $sessionCourse->setNbrUsers(
                        $sessionCourse->getNbrUsers() - 1
                    );
                }

                unset($this->userCourseSubscriptions[$i]);
            }
        }
    }

    /**
     * @param int $status if not set it will check if the user is registered
     *                    with any status
     */
    public function hasUserInCourse(User $user, Course $course, $status = null): bool
    {
        $relation = $this->getUserInCourse($user, $course, $status);

        return $relation->count() > 0;
    }

    /**
     * @return bool
     */
    public function hasStudentInCourse(User $user, Course $course)
    {
        return $this->hasUserInCourse($user, $course, self::STUDENT);
    }

    public function hasCoachInCourseWithStatus(User $user, Course $course = null): bool
    {
        if (null === $course) {
            return false;
        }

        return $this->hasUserInCourse($user, $course, self::COACH);
    }

    public function getUserInCourse(User $user, Course $course, $status = null): Collection
    {
        $criteria = Criteria::create()->where(
            Criteria::expr()->eq('course', $course)
        )->andWhere(
            Criteria::expr()->eq('user', $user)
        );

        if (null !== $status) {
            $criteria->andWhere(
                Criteria::expr()->eq('status', $status)
            );
        }

        return $this->getUserCourseSubscriptions()->matching($criteria);
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set nbrCourses.
     *
     * @param int $nbrCourses
     *
     * @return Session
     */
    public function setNbrCourses($nbrCourses)
    {
        $this->nbrCourses = $nbrCourses;

        return $this;
    }

    /**
     * Get nbrCourses.
     *
     * @return int
     */
    public function getNbrCourses()
    {
        return $this->nbrCourses;
    }

    /**
     * Set nbrUsers.
     *
     * @param int $nbrUsers
     *
     * @return Session
     */
    public function setNbrUsers($nbrUsers)
    {
        $this->nbrUsers = $nbrUsers;

        return $this;
    }

    /**
     * Get nbrUsers.
     *
     * @return int
     */
    public function getNbrUsers()
    {
        return $this->nbrUsers;
    }

    /**
     * Set nbrClasses.
     *
     * @param int $nbrClasses
     *
     * @return Session
     */
    public function setNbrClasses($nbrClasses)
    {
        $this->nbrClasses = $nbrClasses;

        return $this;
    }

    /**
     * Get nbrClasses.
     *
     * @return int
     */
    public function getNbrClasses()
    {
        return $this->nbrClasses;
    }

    /**
     * Set visibility.
     *
     * @param int $visibility
     *
     * @return Session
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get visibility.
     *
     * @return int
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Set promotionId.
     *
     * @param int $promotionId
     *
     * @return Session
     */
    public function setPromotionId($promotionId)
    {
        $this->promotionId = $promotionId;

        return $this;
    }

    /**
     * Get promotionId.
     *
     * @return int
     */
    public function getPromotionId()
    {
        return $this->promotionId;
    }

    /**
     * Set displayStartDate.
     *
     * @param DateTime $displayStartDate
     *
     * @return Session
     */
    public function setDisplayStartDate($displayStartDate)
    {
        $this->displayStartDate = $displayStartDate;

        return $this;
    }

    /**
     * Get displayStartDate.
     *
     * @return DateTime
     */
    public function getDisplayStartDate()
    {
        return $this->displayStartDate;
    }

    /**
     * Set displayEndDate.
     *
     * @param DateTime $displayEndDate
     *
     * @return Session
     */
    public function setDisplayEndDate($displayEndDate)
    {
        $this->displayEndDate = $displayEndDate;

        return $this;
    }

    /**
     * Get displayEndDate.
     *
     * @return DateTime
     */
    public function getDisplayEndDate()
    {
        return $this->displayEndDate;
    }

    /**
     * Set accessStartDate.
     *
     * @param DateTime $accessStartDate
     *
     * @return Session
     */
    public function setAccessStartDate($accessStartDate)
    {
        $this->accessStartDate = $accessStartDate;

        return $this;
    }

    /**
     * Get accessStartDate.
     *
     * @return DateTime
     */
    public function getAccessStartDate()
    {
        return $this->accessStartDate;
    }

    /**
     * Set accessEndDate.
     *
     * @param DateTime $accessEndDate
     *
     * @return Session
     */
    public function setAccessEndDate($accessEndDate)
    {
        $this->accessEndDate = $accessEndDate;

        return $this;
    }

    /**
     * Get accessEndDate.
     *
     * @return DateTime
     */
    public function getAccessEndDate()
    {
        return $this->accessEndDate;
    }

    /**
     * Set coachAccessStartDate.
     *
     * @param DateTime $coachAccessStartDate
     *
     * @return Session
     */
    public function setCoachAccessStartDate($coachAccessStartDate)
    {
        $this->coachAccessStartDate = $coachAccessStartDate;

        return $this;
    }

    /**
     * Get coachAccessStartDate.
     *
     * @return DateTime
     */
    public function getCoachAccessStartDate()
    {
        return $this->coachAccessStartDate;
    }

    /**
     * Set coachAccessEndDate.
     *
     * @param DateTime $coachAccessEndDate
     *
     * @return Session
     */
    public function setCoachAccessEndDate($coachAccessEndDate)
    {
        $this->coachAccessEndDate = $coachAccessEndDate;

        return $this;
    }

    /**
     * Get coachAccessEndDate.
     *
     * @return DateTime
     */
    public function getCoachAccessEndDate()
    {
        return $this->coachAccessEndDate;
    }

    /**
     * @return User
     */
    public function getGeneralCoach()
    {
        return $this->generalCoach;
    }

    public function setGeneralCoach(User $coach): self
    {
        $this->generalCoach = $coach;

        return $this;
    }

    /**
     * @return SessionCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category): self
    {
        $this->category = $category;

        return $this;
    }

    public static function getStatusList(): array
    {
        return [
            self::VISIBLE => 'status_visible',
            self::READ_ONLY => 'status_read_only',
            self::INVISIBLE => 'status_invisible',
            self::AVAILABLE => 'status_available',
        ];
    }

    /**
     * Check if session is visible.
     */
    public function isActive(): bool
    {
        $now = new Datetime('now');

        if ($now > $this->getAccessStartDate()) {
            return true;
        }

        return false;
    }

    public function isActiveForStudent(): bool
    {
        $start = $this->getAccessStartDate();
        $end = $this->getAccessEndDate();

        return $this->compareDates($start, $end);
    }

    public function isActiveForCoach(): bool
    {
        $start = $this->getCoachAccessStartDate();
        $end = $this->getCoachAccessEndDate();

        return $this->compareDates($start, $end);
    }

    /**
     * Compare the current date with start and end access dates.
     * Either missing date is interpreted as no limit.
     *
     * @return bool whether now is between the session access start and end dates
     */
    public function isCurrentlyAccessible(): bool
    {
        try {
            $now = new Datetime();
        } catch (Exception $exception) {
            return false;
        }

        return (null === $this->accessStartDate || $this->accessStartDate < $now)
            && (null === $this->accessEndDate || $now < $this->accessEndDate);
    }

    public function addCourse(Course $course): void
    {
        $entity = new SessionRelCourse();
        $entity->setCourse($course);
        $this->addCourses($entity);
    }

    /**
     * Removes a course from this session.
     *
     * @param Course $course the course to remove from this session
     *
     * @return bool whether the course was actually found in this session and removed from it
     */
    public function removeCourse(Course $course)
    {
        $relCourse = $this->getCourseSubscription($course);
        if ($relCourse) {
            $this->courses->removeElement($relCourse);
            $this->setNbrCourses(count($this->courses));

            return true;
        }

        return false;
    }

    public function getUserCourseSubscriptions(): Collection
    {
        return $this->userCourseSubscriptions;
    }

    /**
     * @param ArrayCollection $userCourseSubscriptions
     *
     * @return $this
     */
    public function setUserCourseSubscriptions($userCourseSubscriptions)
    {
        $this->userCourseSubscriptions = new ArrayCollection();

        foreach ($userCourseSubscriptions as $item) {
            $this->addUserCourseSubscription($item);
        }

        return $this;
    }

    public function addUserCourseSubscription(SessionRelCourseRelUser $subscription): void
    {
        $subscription->setSession($this);
        if (!$this->hasUserCourseSubscription($subscription)) {
            $this->userCourseSubscriptions[] = $subscription;
        }
    }

    /**
     * @return null|SessionRelCourse
     */
    public function getCourseSubscription(Course $course)
    {
        $criteria = Criteria::create()->where(
            Criteria::expr()->eq('course', $course)
        );

        return $this->courses->matching($criteria)->current();
    }

    /**
     * Add a user course subscription.
     * If user status in session is student, then increase number of course users.
     *
     * @param int $status
     */
    public function addUserInCourse($status, User $user, Course $course): void
    {
        $userRelCourseRelSession = new SessionRelCourseRelUser();
        $userRelCourseRelSession->setCourse($course);
        $userRelCourseRelSession->setUser($user);
        $userRelCourseRelSession->setSession($this);
        $userRelCourseRelSession->setStatus($status);
        $this->addUserCourseSubscription($userRelCourseRelSession);

        if (self::STUDENT === $status) {
            $sessionCourse = $this->getCourseSubscription($course);

            $sessionCourse->setNbrUsers(
                $sessionCourse->getNbrUsers() + 1
            );
        }
    }

    /**
     * @return bool
     */
    public function hasUserCourseSubscription(SessionRelCourseRelUser $subscription)
    {
        if ($this->getUserCourseSubscriptions()->count()) {
            $criteria = Criteria::create()->where(
                Criteria::expr()->eq('user', $subscription->getUser())
            )->andWhere(
                Criteria::expr()->eq('course', $subscription->getCourse())
            )->andWhere(
                Criteria::expr()->eq('session', $subscription->getSession())
            );
            $relation = $this->getUserCourseSubscriptions()->matching($criteria);

            return $relation->count() > 0;
        }

        return false;
    }

    /**
     * currentCourse is set in CourseListener.
     *
     * @return Course
     */
    public function getCurrentCourse()
    {
        return $this->currentCourse;
    }

    /**
     * currentCourse is set in CourseListener.
     *
     * @return $this
     */
    public function setCurrentCourse(Course $course)
    {
        // If the session is registered in the course session list.
        $exists = $this->getCourses()->exists(
            function ($key, $element) use ($course) {
                /** @var SessionRelCourse $element */
                return $course->getId() === $element->getCourse()->getId();
            }
        );

        if ($exists) {
            $this->currentCourse = $course;
        }

        return $this;
    }

    public function setSendSubscriptionNotification(bool $sendNotification): self
    {
        $this->sendSubscriptionNotification = $sendNotification;

        return $this;
    }

    public function getSendSubscriptionNotification(): bool
    {
        return $this->sendSubscriptionNotification;
    }

    /**
     * Get user from course by status.
     *
     * @param int $status
     *
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getUserCourseSubscriptionsByStatus(Course $course, $status)
    {
        $criteria = Criteria::create()
            ->where(
                Criteria::expr()->eq('course', $course)
            )
            ->andWhere(
                Criteria::expr()->eq('status', $status)
            )
        ;

        return $this->userCourseSubscriptions->matching($criteria);
    }

    /**
     * @return Session
     */
    public function setStudentPublications(Collection $studentPublications)
    {
        $this->studentPublications = new ArrayCollection();

        foreach ($studentPublications as $studentPublication) {
            $this->addStudentPublication($studentPublication);
        }

        return $this;
    }

    /**
     * @return Session
     */
    public function addStudentPublication(CStudentPublication $studentPublication)
    {
        $this->studentPublications[] = $studentPublication;

        return $this;
    }

    public function getStudentPublications(): Collection
    {
        return $this->studentPublications;
    }

    public function getIssuedSkills(): Collection
    {
        return $this->issuedSkills;
    }

    /**
     * @return $this
     */
    public function setCurrentUrl(AccessUrl $url)
    {
        $urlList = $this->getUrls();
        /** @var AccessUrlRelCourse $item */
        foreach ($urlList as $item) {
            if ($item->getUrl()->getId() === $url->getId()) {
                $this->currentUrl = $url;

                break;
            }
        }

        return $this;
    }

    /**
     * @return AccessUrl
     */
    public function getCurrentUrl()
    {
        return $this->currentUrl;
    }

    /**
     * @return Collection
     */
    public function getUrls()
    {
        return $this->urls;
    }

    public function setUrls($urls): void
    {
        $this->urls = new ArrayCollection();

        foreach ($urls as $url) {
            $this->addUrls($url);
        }
    }

    public function addUrl(AccessUrl $url): void
    {
        $accessUrlRelSession = new AccessUrlRelSession();
        $accessUrlRelSession->setUrl($url);
        $accessUrlRelSession->setSession($this);

        $this->addUrls($accessUrlRelSession);
    }

    public function addUrls(AccessUrlRelSession $url): void
    {
        $url->setSession($this);
        $this->urls[] = $url;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     *
     * @return Session
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSessionAdmin(): User
    {
        return $this->sessionAdmin;
    }

    public function setSessionAdmin(User $sessionAdmin): self
    {
        $this->sessionAdmin = $sessionAdmin;

        return $this;
    }

    public function isUserGeneralCoach(User $user): bool
    {
        $generalCoach = $this->getGeneralCoach();

        if ($generalCoach instanceof User && $user->getId() === $generalCoach->getId()) {
            return true;
        }

        return false;
    }

    /**
     * Check if $user is course coach in any course.
     *
     * @return bool
     */
    public function hasCoachInCourseList(User $user)
    {
        /** @var SessionRelCourse $sessionCourse */
        foreach ($this->courses as $sessionCourse) {
            if ($this->hasCoachInCourseWithStatus($user, $sessionCourse->getCourse())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if $user is student in any course.
     *
     * @return bool
     */
    public function hasStudentInCourseList(User $user)
    {
        /** @var SessionRelCourse $sessionCourse */
        foreach ($this->courses as $sessionCourse) {
            if ($this->hasStudentInCourse($user, $sessionCourse->getCourse())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param DateTime $start
     * @param DateTime $end
     */
    protected function compareDates($start, $end): bool
    {
        $now = new Datetime('now');

        if (!empty($start) && !empty($end)) {
            if ($now >= $start && $now <= $end) {
                return true;
            }
        }

        if (!empty($start)) {
            if ($now >= $start) {
                return true;
            }
        }

        if (!empty($end)) {
            if ($now <= $end) {
                return true;
            }
        }

        return false;
    }
}
