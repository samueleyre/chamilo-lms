<?php

declare(strict_types=1);

/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket.
 *
 * @ORM\Table(name="ticket_ticket")
 * @ORM\Entity
 */
class Ticket
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected int $id;

    /**
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    protected string $code;

    /**
     * @ORM\Column(name="subject", type="string", length=255, nullable=false)
     */
    protected string $subject;

    /**
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    protected ?string $message;

    /**
     * @ORM\ManyToOne(targetEntity="TicketProject")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected TicketProject $project;

    /**
     * @ORM\ManyToOne(targetEntity="TicketCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected TicketProject $category;

    /**
     * @ORM\ManyToOne(targetEntity="TicketPriority")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id")
     */
    protected TicketPriority $priority;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CoreBundle\Entity\Course")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    protected Course $course;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CoreBundle\Entity\Session")
     * @ORM\JoinColumn(name="session_id", referencedColumnName="id")
     */
    protected Session $session;

    /**
     * @ORM\Column(name="personal_email", type="string", length=255, nullable=false)
     */
    protected string $personalEmail;

    /**
     * @ORM\Column(name="assigned_last_user", type="integer", nullable=true)
     */
    protected ?int $assignedLastUser;

    /**
     * @ORM\ManyToOne(targetEntity="TicketStatus")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    protected TicketStatus $status;

    /**
     * @ORM\Column(name="total_messages", type="integer", nullable=false)
     */
    protected int $totalMessages;

    /**
     * @ORM\Column(name="keyword", type="string", length=255, nullable=true)
     */
    protected ?string $keyword;

    /**
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    protected ?string $source;

    /**
     * @ORM\Column(name="start_date", type="datetime", nullable=true, unique=false)
     */
    protected ?DateTime $startDate;

    /**
     * @ORM\Column(name="end_date", type="datetime", nullable=true, unique=false)
     */
    protected ?DateTime $endDate;

    /**
     * @ORM\Column(name="sys_insert_user_id", type="integer")
     */
    protected int $insertUserId;

    /**
     * @ORM\Column(name="sys_insert_datetime", type="datetime")
     */
    protected DateTime $insertDateTime;

    /**
     * @ORM\Column(name="sys_lastedit_user_id", type="integer", nullable=true, unique=false)
     */
    protected int $lastEditUserId;

    /**
     * @ORM\Column(name="sys_lastedit_datetime", type="datetime", nullable=true, unique=false)
     */
    protected DateTime $lastEditDateTime;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return Ticket
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return Ticket
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return Ticket
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}
