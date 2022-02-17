<?php
/**
 * User repository class File
 */
namespace user\Repositories;
use Doctrine\Shiksha\MyEntityRepository;
use user\Entities;

/**
 * User repository class
 */ 
class UserRepository extends MyEntityRepository
{
    /**
     * Find user by id
     *
     * @param integer|array $userId
     * @return object|array \user\Entities\User
     */ 
    public function find($userId)
    {
        if(is_array($userId)) {
            $dql = "SELECT u FROM user\Entities\User u WHERE u.id IN (:ids)";
            $query = $this->getEntityManager()->createQuery($dql)->setParameter('ids', $userId);
            return $query->getResult();
        }
        else {
            return $this->findOneBy(array('id' => $userId));    
        }
    }
    
    /**
     * Find user by display name
     *
     * @param string $displayName
     * @return array \user\Entities\User
     */
    public function findByDisplayName($displayName)
    {
        $dql = "SELECT u FROM user\Entities\User u WHERE u.displayName = :displayName";
        $query = $this->getEntityManager()->createQuery($dql)->setParameter('displayName', $displayName);
        return $query->getResult();
    }
    
    /**
     * Find user by email
     *
     * @param string $email
     * @return array \user\Entities\User
     */
    public function findByEmail($email)
    {
        $dql = "SELECT u FROM user\Entities\User u WHERE u.email = :email";
        $query = $this->getEntityManager()->createQuery($dql)->setParameter('email', $email);
        return $query->getResult();
    }
    
    /**
     * Store a user in databases
     *
     * @param object $user \user\Entities\User
     */ 
    public function store(\user\Entities\User $user)
    {
         try {
            $this->getEntityManager()->persist($user);
        }
        catch(\Exception $e) {
            echo $e->getMessage();
        }
        
        $this->getEntityManager()->flush();
    }
}