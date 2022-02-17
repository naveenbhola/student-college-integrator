<?php
/**
 * File for user\Entities\UserFlags Entity
 */

namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\UserFlags
 */
class UserFlags
{
    /**
     * Field for Pending Verification
     * @var string $pendingVerification
     */
    private $pendingVerification;

    /**
     * Field for hard bounce
     * @var string $hardBounce
     */
    private $hardBounce;

    /**
     * Field for Unsubscribe
     * @var string $unsubscribe
     */
    private $unsubscribe;

    /**
     * Field for Ownership challenged
     * @var string $ownershipChallenged
     */
    private $ownershipChallenged;

    /**
     * Field for Soft Bounce
     * @var string $softBounce
     */
    private $softBounce;

    /**
     * Field for abused
     * @var string $abused
     */
    private $abused;

    /**
     * Field for MobileVerified
     * @var string $mobileVerified
     */
    private $mobileVerified;

    /**
     * Field for EMailSentCount
     * @var integer $emailSentCount
     */
    private $emailSentCount;

    /**
     * Field for Email Verified
     * @var string $emailVerified
     */
    private $emailVerified;

    /**
     * Field for isNDNC
     * @var string $isNDNC
     */
    private $isNDNC;

    /**
     * Field for IsLDBUser
     * @var string $isLDBUser
     */
    private $isLDBUser;
    
    /**
     * Field for isTestUser
     * @var string $isTestUser
     */
    private $isTestUser;

    /**
     * Field for ID
     * @var integer $id
     */
    private $id;

    /**
     * Entity user\Entities\User
     * @var user\Entities\User
     */
    private $user;
    
    /**
     * Field for isMR
     * @var string $isMR
     */
    private $isMR;


    /**
     * Set pendingVerification
     *
     * @param string $pendingVerification
     * @return UserFlags
     */
    public function setPendingVerification($pendingVerification)
    {
        $this->pendingVerification = $pendingVerification;
        return $this;
    }

    /**
     * Get pendingVerification
     *
     * @return string 
     */
    public function getPendingVerification()
    {
        return $this->pendingVerification;
    }

    /**
     * Set hardBounce
     *
     * @param string $hardBounce
     * @return UserFlags
     */
    public function setHardBounce($hardBounce)
    {
        $this->hardBounce = $hardBounce;
        return $this;
    }

    /**
     * Get hardBounce
     *
     * @return string 
     */
    public function getHardBounce()
    {
        return $this->hardBounce;
    }

    /**
     * Set unsubscribe
     *
     * @param string $unsubscribe
     * @return UserFlags
     */
    public function setUnsubscribe($unsubscribe)
    {
        $this->unsubscribe = $unsubscribe;
        return $this;
    }

    /**
     * Get unsubscribe
     *
     * @return string 
     */
    public function getUnsubscribe()
    {
        return $this->unsubscribe;
    }

    /**
     * Set ownershipChallenged
     *
     * @param string $ownershipChallenged
     * @return UserFlags
     */
    public function setOwnershipChallenged($ownershipChallenged)
    {
        $this->ownershipChallenged = $ownershipChallenged;
        return $this;
    }

    /**
     * Get ownershipChallenged
     *
     * @return string 
     */
    public function getOwnershipChallenged()
    {
        return $this->ownershipChallenged;
    }

    /**
     * Set softBounce
     *
     * @param string $softBounce
     * @return UserFlags
     */
    public function setSoftBounce($softBounce)
    {
        $this->softBounce = $softBounce;
        return $this;
    }

    /**
     * Get softBounce
     *
     * @return string 
     */
    public function getSoftBounce()
    {
        return $this->softBounce;
    }

    /**
     * Set abused
     *
     * @param string $abused
     * @return UserFlags
     */
    public function setAbused($abused)
    {
        $this->abused = $abused;
        return $this;
    }

    /**
     * Get abused
     *
     * @return string 
     */
    public function getAbused()
    {
        return $this->abused;
    }

    /**
     * Set mobileVerified
     *
     * @param string $mobileVerified
     * @return UserFlags
     */
    public function setMobileVerified($mobileVerified)
    {
        $this->mobileVerified = $mobileVerified;
        return $this;
    }

    /**
     * Get mobileVerified
     *
     * @return string 
     */
    public function getMobileVerified()
    {
        return $this->mobileVerified;
    }

    /**
     * Set emailSentCount
     *
     * @param integer $emailSentCount
     * @return UserFlags
     */
    public function setEmailSentCount($emailSentCount)
    {
        $this->emailSentCount = $emailSentCount;
        return $this;
    }

    /**
     * Get emailSentCount
     *
     * @return integer 
     */
    public function getEmailSentCount()
    {
        return $this->emailSentCount;
    }

    /**
     * Set emailVerified
     *
     * @param string $emailVerified
     * @return UserFlags
     */
    public function setEmailVerified($emailVerified)
    {
        $this->emailVerified = $emailVerified;
        return $this;
    }

    /**
     * Get emailVerified
     *
     * @return string 
     */
    public function getEmailVerified()
    {
        return $this->emailVerified;
    }

    /**
     * Set isNDNC
     *
     * @param string $isNDNC
     * @return UserFlags
     */
    public function setIsNDNC($isNDNC)
    {
        $this->isNDNC = $isNDNC;
        return $this;
    }

    /**
     * Get isNDNC
     *
     * @return string 
     */
    public function getIsNDNC()
    {
        return $this->isNDNC;
    }

    /**
     * Set isLdbUser
     *
     * @param string $isLDBUser
     * @return UserFlags
     */
    public function setIsLDBUser($isLDBUser)
    {
        $this->isLDBUser = $isLDBUser;
        return $this;
    }

    /**
     * Get isLDBUser
     *
     * @return string 
     */
    public function getIsLDBUser()
    {
        return $this->isLDBUser;
    }

    /**
     * Set isTestUser
     *
     * @param string $isTestUser
     * @return UserFlags
     */
    public function setIsTestUser($isTestUser)
    {
        $this->isTestUser = $isTestUser;
        return $this;
    }

    /**
     * Get isTestUser
     *
     * @return string 
     */
    public function getIsTestUser()
    {
        return $this->isTestUser;
    }
    
    /**
     * Set isTestUser
     *
     * @param string $isTestUser
     * @return UserFlags
     */
    public function setIsMR($isMR)
    {
        $this->isMR = $isMR;
        return $this;
    }

    /**
     * Get isTestUser
     *
     * @return string 
     */
    public function getIsMR()
    {
        return $this->isMR;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param user\Entities\User $user
     * @return UserFlags
     */
    public function setUser(\user\Entities\User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return user\Entities\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}