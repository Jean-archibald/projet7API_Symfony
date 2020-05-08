<?php

namespace App\Repository;

use App\Entity\Partners;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * @method Partners|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partners|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partners[]    findAll()
 * @method Partners[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartnersRepository extends ServiceEntityRepository  implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partners::class);
    }

    public function persistPartners($data,$encoder,$manager)
    {
            $data->setCreatedAt(new \Datetime());
            $data->setPassword('undefined');
            $data->setStatusConfirmed(0);
            $data->setRoles(["ROLE_USER"]);
            $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
            $data->setConfirmationToken($token);
            $hash = $encoder->encodePassword($data, $data->getPassword());
            $data->setPassword($hash);
            $manager->persist($data);
            $manager->flush();
    }

    public function updatePartners($data,$manager)
    {
        $data->setCreatedAt(new \Datetime());
        $manager->persist($data);
        $manager->flush();
    }

    public function sendMailConfirmation($partners,$notification,$mailer)
    {
        $notification->notify($partners,$mailer);
    }

    public function confirmedStatusPartners($partners,$manager)
    {
        $partners->setStatusConfirmed(1);
        $manager->persist($partners);
        $manager->flush();
    }

    public function setPasswordAccount(Partners $partners,$password,$manager)
    {
        $partners->setPassword($password);
        $manager->persist($partners);
        $manager->flush();
    }


    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function confirmation()
    {
        $user = $this->getUser();
        dd($user);
    }

    public function confirmedMailReception($manager,$request,$encoder,$partners)
    {

        if ($request->isMethod('POST')) {
            if (isset($partners)) {

                if (isset($_POST) && !empty($_POST) && ($_POST["password"] == $_POST["password2"])) {
                    $password = htmlspecialchars($_POST['password']);
                    $password = $encoder->encodePassword($partners, $_POST["password"]);
                    $this->setPasswordAccount($partners,$password,$manager);
                    $this->confirmedStatusPartners($partners, $manager);
                }
            }
        }
    }




    // /**
    //  * @return Partners[] Returns an array of Partners objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Partners
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
