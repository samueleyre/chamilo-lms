<?php

declare(strict_types=1);

/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrackELinks.
 *
 * @ORM\Table(name="track_e_links", indexes={
 *     @ORM\Index(name="idx_tel_c_id", columns={"c_id"}),
 *     @ORM\Index(name="idx_tel_user_id", columns={"links_user_id"}),
 *     @ORM\Index(name="links_session_id", columns={"links_session_id"})
 * })
 * @ORM\Entity
 */
class TrackELinks
{
    /**
     * @ORM\Column(name="links_user_id", type="integer", nullable=true)
     */
    protected ?int $linksUserId;

    /**
     * @ORM\Column(name="links_date", type="datetime", nullable=false)
     */
    protected DateTime $linksDate;

    /**
     * @ORM\Column(name="c_id", type="integer", nullable=false)
     */
    protected int $cId;

    /**
     * @ORM\Column(name="links_link_id", type="integer", nullable=false)
     */
    protected int $linksLinkId;

    /**
     * @ORM\Column(name="links_session_id", type="integer", nullable=false)
     */
    protected int $linksSessionId;

    /**
     * @ORM\Column(name="links_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected int $linksId;

    /**
     * Set linksUserId.
     *
     * @param int $linksUserId
     *
     * @return TrackELinks
     */
    public function setLinksUserId($linksUserId)
    {
        $this->linksUserId = $linksUserId;

        return $this;
    }

    /**
     * Get linksUserId.
     *
     * @return int
     */
    public function getLinksUserId()
    {
        return $this->linksUserId;
    }

    /**
     * Set linksDate.
     *
     * @param DateTime $linksDate
     *
     * @return TrackELinks
     */
    public function setLinksDate($linksDate)
    {
        $this->linksDate = $linksDate;

        return $this;
    }

    /**
     * Get linksDate.
     *
     * @return DateTime
     */
    public function getLinksDate()
    {
        return $this->linksDate;
    }

    /**
     * Set cId.
     *
     * @param int $cId
     *
     * @return TrackELinks
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

    /**
     * Set linksLinkId.
     *
     * @param int $linksLinkId
     *
     * @return TrackELinks
     */
    public function setLinksLinkId($linksLinkId)
    {
        $this->linksLinkId = $linksLinkId;

        return $this;
    }

    /**
     * Get linksLinkId.
     *
     * @return int
     */
    public function getLinksLinkId()
    {
        return $this->linksLinkId;
    }

    /**
     * Set linksSessionId.
     *
     * @param int $linksSessionId
     *
     * @return TrackELinks
     */
    public function setLinksSessionId($linksSessionId)
    {
        $this->linksSessionId = $linksSessionId;

        return $this;
    }

    /**
     * Get linksSessionId.
     *
     * @return int
     */
    public function getLinksSessionId()
    {
        return $this->linksSessionId;
    }

    /**
     * Get linksId.
     *
     * @return int
     */
    public function getLinksId()
    {
        return $this->linksId;
    }
}
