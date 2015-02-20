<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Olcs\Db\Entity\Traits;

/**
 * DiscSequence Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="disc_sequence",
 *    indexes={
 *        @ORM\Index(name="fk_disc_sequence_ref_data1_idx", columns={"goods_or_psv"}),
 *        @ORM\Index(name="fk_disc_sequence_traffic_area1_idx", columns={"traffic_area_id"}),
 *        @ORM\Index(name="fk_disc_sequence_user1_idx", columns={"created_by"}),
 *        @ORM\Index(name="fk_disc_sequence_user2_idx", columns={"last_modified_by"})
 *    }
 * )
 */
class DiscSequence implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\CreatedByManyToOne,
        Traits\CustomCreatedOnField,
        Traits\GoodsOrPsvManyToOneAlt1,
        Traits\IdIdentity,
        Traits\LastModifiedByManyToOne,
        Traits\CustomLastModifiedOnField,
        Traits\TrafficAreaManyToOneAlt1,
        Traits\CustomVersionField;

    /**
     * Is ni self serve
     *
     * @var string
     *
     * @ORM\Column(type="yesno", name="is_ni_self_serve", nullable=false, options={"default": 0})
     */
    protected $isNiSelfServe = 0;

    /**
     * Is self serve
     *
     * @var string
     *
     * @ORM\Column(type="yesno", name="is_self_serve", nullable=false, options={"default": 0})
     */
    protected $isSelfServe = 0;

    /**
     * R prefix
     *
     * @var string
     *
     * @ORM\Column(type="string", name="r_prefix", length=3, nullable=true)
     */
    protected $rPrefix;

    /**
     * Restricted
     *
     * @var int
     *
     * @ORM\Column(type="integer", name="restricted", nullable=true)
     */
    protected $restricted;

    /**
     * Si prefix
     *
     * @var string
     *
     * @ORM\Column(type="string", name="si_prefix", length=3, nullable=true)
     */
    protected $siPrefix;

    /**
     * Sn prefix
     *
     * @var string
     *
     * @ORM\Column(type="string", name="sn_prefix", length=3, nullable=true)
     */
    protected $snPrefix;

    /**
     * Special restricted
     *
     * @var int
     *
     * @ORM\Column(type="integer", name="special_restricted", nullable=true)
     */
    protected $specialRestricted;

    /**
     * Sr prefix
     *
     * @var string
     *
     * @ORM\Column(type="string", name="sr_prefix", length=3, nullable=true)
     */
    protected $srPrefix;

    /**
     * Standard international
     *
     * @var int
     *
     * @ORM\Column(type="integer", name="standard_international", nullable=true)
     */
    protected $standardInternational;

    /**
     * Standard national
     *
     * @var int
     *
     * @ORM\Column(type="integer", name="standard_national", nullable=true)
     */
    protected $standardNational;

    /**
     * Set the is ni self serve
     *
     * @param string $isNiSelfServe
     * @return DiscSequence
     */
    public function setIsNiSelfServe($isNiSelfServe)
    {
        $this->isNiSelfServe = $isNiSelfServe;

        return $this;
    }

    /**
     * Get the is ni self serve
     *
     * @return string
     */
    public function getIsNiSelfServe()
    {
        return $this->isNiSelfServe;
    }

    /**
     * Set the is self serve
     *
     * @param string $isSelfServe
     * @return DiscSequence
     */
    public function setIsSelfServe($isSelfServe)
    {
        $this->isSelfServe = $isSelfServe;

        return $this;
    }

    /**
     * Get the is self serve
     *
     * @return string
     */
    public function getIsSelfServe()
    {
        return $this->isSelfServe;
    }

    /**
     * Set the r prefix
     *
     * @param string $rPrefix
     * @return DiscSequence
     */
    public function setRPrefix($rPrefix)
    {
        $this->rPrefix = $rPrefix;

        return $this;
    }

    /**
     * Get the r prefix
     *
     * @return string
     */
    public function getRPrefix()
    {
        return $this->rPrefix;
    }

    /**
     * Set the restricted
     *
     * @param int $restricted
     * @return DiscSequence
     */
    public function setRestricted($restricted)
    {
        $this->restricted = $restricted;

        return $this;
    }

    /**
     * Get the restricted
     *
     * @return int
     */
    public function getRestricted()
    {
        return $this->restricted;
    }

    /**
     * Set the si prefix
     *
     * @param string $siPrefix
     * @return DiscSequence
     */
    public function setSiPrefix($siPrefix)
    {
        $this->siPrefix = $siPrefix;

        return $this;
    }

    /**
     * Get the si prefix
     *
     * @return string
     */
    public function getSiPrefix()
    {
        return $this->siPrefix;
    }

    /**
     * Set the sn prefix
     *
     * @param string $snPrefix
     * @return DiscSequence
     */
    public function setSnPrefix($snPrefix)
    {
        $this->snPrefix = $snPrefix;

        return $this;
    }

    /**
     * Get the sn prefix
     *
     * @return string
     */
    public function getSnPrefix()
    {
        return $this->snPrefix;
    }

    /**
     * Set the special restricted
     *
     * @param int $specialRestricted
     * @return DiscSequence
     */
    public function setSpecialRestricted($specialRestricted)
    {
        $this->specialRestricted = $specialRestricted;

        return $this;
    }

    /**
     * Get the special restricted
     *
     * @return int
     */
    public function getSpecialRestricted()
    {
        return $this->specialRestricted;
    }

    /**
     * Set the sr prefix
     *
     * @param string $srPrefix
     * @return DiscSequence
     */
    public function setSrPrefix($srPrefix)
    {
        $this->srPrefix = $srPrefix;

        return $this;
    }

    /**
     * Get the sr prefix
     *
     * @return string
     */
    public function getSrPrefix()
    {
        return $this->srPrefix;
    }

    /**
     * Set the standard international
     *
     * @param int $standardInternational
     * @return DiscSequence
     */
    public function setStandardInternational($standardInternational)
    {
        $this->standardInternational = $standardInternational;

        return $this;
    }

    /**
     * Get the standard international
     *
     * @return int
     */
    public function getStandardInternational()
    {
        return $this->standardInternational;
    }

    /**
     * Set the standard national
     *
     * @param int $standardNational
     * @return DiscSequence
     */
    public function setStandardNational($standardNational)
    {
        $this->standardNational = $standardNational;

        return $this;
    }

    /**
     * Get the standard national
     *
     * @return int
     */
    public function getStandardNational()
    {
        return $this->standardNational;
    }
}
