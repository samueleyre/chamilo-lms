<?php

declare(strict_types=1);

/* For licensing terms, see /license.txt */

namespace Chamilo\CourseBundle\Entity;

use Chamilo\CoreBundle\Entity\AbstractResource;
use Chamilo\CoreBundle\Entity\ResourceInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CWiki.
 *
 * @ORM\Table(
 *     name="c_wiki",
 *     options={"row_format":"DYNAMIC"},
 *     indexes={
 *         @ORM\Index(name="course", columns={"c_id"}),
 *         @ORM\Index(name="reflink", columns={"reflink"}),
 *         @ORM\Index(name="group_id", columns={"group_id"}),
 *         @ORM\Index(name="page_id", columns={"page_id"}),
 *         @ORM\Index(name="session_id", columns={"session_id"})
 *     }
 * )
 * @ORM\Entity
 */
class CWiki extends AbstractResource implements ResourceInterface
{
    /**
     * @ORM\Column(name="iid", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected int $iid;

    /**
     * @ORM\Column(name="c_id", type="integer")
     */
    protected int $cId;

    /**
     * @ORM\Column(name="page_id", type="integer", nullable=true)
     */
    protected ?int $pageId;

    /**
     * @ORM\Column(name="reflink", type="string", length=255, nullable=false)
     */
    protected string $reflink;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    protected string $title;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    protected string $content;

    /**
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    protected int $userId;

    /**
     * @ORM\Column(name="group_id", type="integer", nullable=true)
     */
    protected ?int $groupId;

    /**
     * @ORM\Column(name="dtime", type="datetime", nullable=true)
     */
    protected ?DateTime $dtime;

    /**
     * @ORM\Column(name="addlock", type="integer", nullable=false)
     */
    protected int $addlock;

    /**
     * @ORM\Column(name="editlock", type="integer", nullable=false)
     */
    protected int $editlock;

    /**
     * @ORM\Column(name="visibility", type="integer", nullable=false)
     */
    protected int $visibility;

    /**
     * @ORM\Column(name="addlock_disc", type="integer", nullable=false)
     */
    protected int $addlockDisc;

    /**
     * @ORM\Column(name="visibility_disc", type="integer", nullable=false)
     */
    protected int $visibilityDisc;

    /**
     * @ORM\Column(name="ratinglock_disc", type="integer", nullable=false)
     */
    protected int $ratinglockDisc;

    /**
     * @ORM\Column(name="assignment", type="integer", nullable=false)
     */
    protected int $assignment;

    /**
     * @ORM\Column(name="comment", type="text", nullable=false)
     */
    protected string $comment;

    /**
     * @ORM\Column(name="progress", type="text", nullable=false)
     */
    protected string $progress;

    /**
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    protected ?int $score;

    /**
     * @ORM\Column(name="version", type="integer", nullable=true)
     */
    protected ?int $version;

    /**
     * @ORM\Column(name="is_editing", type="integer", nullable=false)
     */
    protected int $isEditing;

    /**
     * @ORM\Column(name="time_edit", type="datetime", nullable=true)
     */
    protected ?DateTime $timeEdit;

    /**
     * @ORM\Column(name="hits", type="integer", nullable=true)
     */
    protected ?int $hits;

    /**
     * @ORM\Column(name="linksto", type="text", nullable=false)
     */
    protected string $linksto;

    /**
     * @ORM\Column(name="tag", type="text", nullable=false)
     */
    protected string $tag;

    /**
     * @ORM\Column(name="user_ip", type="string", length=39, nullable=false)
     */
    protected string $userIp;

    /**
     * @ORM\Column(name="session_id", type="integer", nullable=true)
     */
    protected ?int $sessionId;

    public function __toString(): string
    {
        return $this->getTitle();
    }

    public function getIid(): int
    {
        return $this->iid;
    }

    /**
     * Set pageId.
     *
     * @param int $pageId
     *
     * @return CWiki
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;

        return $this;
    }

    /**
     * Get pageId.
     *
     * @return int
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Set reflink.
     *
     * @param string $reflink
     *
     * @return CWiki
     */
    public function setReflink($reflink)
    {
        $this->reflink = $reflink;

        return $this;
    }

    /**
     * Get reflink.
     *
     * @return string
     */
    public function getReflink()
    {
        return $this->reflink;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return CWiki
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return CWiki
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set userId.
     *
     * @param int $userId
     *
     * @return CWiki
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set groupId.
     *
     * @param int $groupId
     *
     * @return CWiki
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * Get groupId.
     *
     * @return int
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Set dtime.
     *
     * @param DateTime $dtime
     *
     * @return CWiki
     */
    public function setDtime($dtime)
    {
        $this->dtime = $dtime;

        return $this;
    }

    /**
     * Get dtime.
     *
     * @return DateTime
     */
    public function getDtime()
    {
        return $this->dtime;
    }

    /**
     * Set addlock.
     *
     * @param int $addlock
     *
     * @return CWiki
     */
    public function setAddlock($addlock)
    {
        $this->addlock = $addlock;

        return $this;
    }

    /**
     * Get addlock.
     *
     * @return int
     */
    public function getAddlock()
    {
        return $this->addlock;
    }

    /**
     * Set editlock.
     *
     * @param int $editlock
     *
     * @return CWiki
     */
    public function setEditlock($editlock)
    {
        $this->editlock = $editlock;

        return $this;
    }

    /**
     * Get editlock.
     *
     * @return int
     */
    public function getEditlock()
    {
        return $this->editlock;
    }

    /**
     * Set visibility.
     *
     * @param int $visibility
     *
     * @return CWiki
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
     * Set addlockDisc.
     *
     * @param int $addlockDisc
     *
     * @return CWiki
     */
    public function setAddlockDisc($addlockDisc)
    {
        $this->addlockDisc = $addlockDisc;

        return $this;
    }

    /**
     * Get addlockDisc.
     *
     * @return int
     */
    public function getAddlockDisc()
    {
        return $this->addlockDisc;
    }

    /**
     * Set visibilityDisc.
     *
     * @param int $visibilityDisc
     *
     * @return CWiki
     */
    public function setVisibilityDisc($visibilityDisc)
    {
        $this->visibilityDisc = $visibilityDisc;

        return $this;
    }

    /**
     * Get visibilityDisc.
     *
     * @return int
     */
    public function getVisibilityDisc()
    {
        return $this->visibilityDisc;
    }

    /**
     * Set ratinglockDisc.
     *
     * @param int $ratinglockDisc
     *
     * @return CWiki
     */
    public function setRatinglockDisc($ratinglockDisc)
    {
        $this->ratinglockDisc = $ratinglockDisc;

        return $this;
    }

    /**
     * Get ratinglockDisc.
     *
     * @return int
     */
    public function getRatinglockDisc()
    {
        return $this->ratinglockDisc;
    }

    /**
     * Set assignment.
     *
     * @param int $assignment
     *
     * @return CWiki
     */
    public function setAssignment($assignment)
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * Get assignment.
     *
     * @return int
     */
    public function getAssignment()
    {
        return $this->assignment;
    }

    /**
     * Set comment.
     *
     * @param string $comment
     *
     * @return CWiki
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set progress.
     *
     * @param string $progress
     *
     * @return CWiki
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * Get progress.
     *
     * @return string
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * Set score.
     *
     * @param int $score
     *
     * @return CWiki
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score.
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set version.
     *
     * @param int $version
     *
     * @return CWiki
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version.
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set isEditing.
     *
     * @param int $isEditing
     *
     * @return CWiki
     */
    public function setIsEditing($isEditing)
    {
        $this->isEditing = $isEditing;

        return $this;
    }

    /**
     * Get isEditing.
     *
     * @return int
     */
    public function getIsEditing()
    {
        return $this->isEditing;
    }

    /**
     * Set timeEdit.
     *
     * @param DateTime $timeEdit
     *
     * @return CWiki
     */
    public function setTimeEdit($timeEdit)
    {
        $this->timeEdit = $timeEdit;

        return $this;
    }

    /**
     * Get timeEdit.
     *
     * @return DateTime
     */
    public function getTimeEdit()
    {
        return $this->timeEdit;
    }

    /**
     * Set hits.
     *
     * @param int $hits
     *
     * @return CWiki
     */
    public function setHits($hits)
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * Get hits.
     *
     * @return int
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Set linksto.
     *
     * @param string $linksto
     *
     * @return CWiki
     */
    public function setLinksto($linksto)
    {
        $this->linksto = $linksto;

        return $this;
    }

    /**
     * Get linksto.
     *
     * @return string
     */
    public function getLinksto()
    {
        return $this->linksto;
    }

    /**
     * Set tag.
     *
     * @param string $tag
     *
     * @return CWiki
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag.
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set userIp.
     *
     * @param string $userIp
     *
     * @return CWiki
     */
    public function setUserIp($userIp)
    {
        $this->userIp = $userIp;

        return $this;
    }

    /**
     * Get userIp.
     *
     * @return string
     */
    public function getUserIp()
    {
        return $this->userIp;
    }

    /**
     * Set sessionId.
     *
     * @param int $sessionId
     *
     * @return CWiki
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get sessionId.
     *
     * @return int
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set cId.
     *
     * @param int $cId
     *
     * @return CWiki
     */
    public function setCId($cId)
    {
        $this->cId = $cId;

        return $this;
    }

    /**
     * Get cId.
     *
     * @return int
     */
    public function getCId()
    {
        return $this->cId;
    }

    public function getResourceIdentifier(): int
    {
        return $this->getIid();
    }

    public function getResourceName(): string
    {
        return $this->getTitle();
    }

    public function setResourceName(string $name): self
    {
        return $this->setTitle($name);
    }
}
