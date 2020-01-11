<?php

namespace App\DataPersister;

use App\Entity\User;
use App\DataPersister\UserDataPersister;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;



class UserDataPersister implements DataPersisterInterface
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager,UserPasswordEncoderInterface $userpasswordEncoder, TokenStorageInterface $tokenStorage)
    {

        $this->userPasswordEncoder = $userpasswordEncoder;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }
    public function supports($data): bool
    {
        return $data instanceof User;
    }
     /**
     * @param User $data
     */
    public function persist($data)
    {
     
     
            //variable role user connecté
            $userRoles=$this->tokenStorage->getToken()->getUser()->getRoles()[0];
            //dd($this->tokenStorage->getToken());
            //variable role user à modifié
            $usersModi=$data->getRoles()[0];
            if($userRoles=="ROLE_SUPADMIN"){
                if($usersModi ==  "ROLE_SUPADMIN"){
                    throw new HttpException("401","Acces non Autorisé");
        
                }else{
                    $data->setPassword($this->userPasswordEncoder->encodePassword($data, $data->getPassword()));
                    
                    $data->eraseCredentials();
                    
                    $this->entityManager->persist($data);
                    $this->entityManager->flush();
                }
            }if($userRoles=="ROLE_ADMIN")
                if($usersModi ==  "ROLE_SUPADMIN" || $usersModi ==  "ROLE_ADMIN" ){
                    throw new HttpException("401","Acces non Autorisé");
    
                }else{
                    $data->setPassword($this->userPasswordEncoder->encodePassword($data, $data->getPassword()));
                    
                    $data->eraseCredentials();
                    
                    $this->entityManager->persist($data);
                    $this->entityManager->flush();
                }
        }
        public function remove($data)
        {
            $this->entityManager->remove($data);
            $this->entityManager->flush();
        }
    }
