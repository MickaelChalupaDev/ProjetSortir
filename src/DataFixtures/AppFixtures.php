<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\User;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
     //***** peuplement de la table Etat ************/

        $etat = new Etat();
        $etat->setLibelle('Créée');
        $manager->persist($etat);
        $manager->flush();
        $etat = new Etat();
        $etat->setLibelle('Ouverte');
        $manager->persist($etat);
        $manager->flush();
        $etat = new Etat();
        $etat->setLibelle('Clôturée');
        $manager->persist($etat);
        $manager->flush();
        $etat = new Etat();
        $etat->setLibelle('Activité en cours');
        $manager->persist($etat);
        $manager->flush();
        $etat = new Etat();
        $etat->setLibelle('passée');
        $manager->persist($etat);
        $manager->flush();
        $etat = new Etat();
        $etat->setLibelle('Annulée');
        $manager->persist($etat);
        $manager->flush();

        //************ peuplement de la table Campus *************//
        $campus = new Campus();
        $campus->setNom('SAINT HERBLAIN');
        $manager->persist($campus);
        $manager->flush();
        $campus = new Campus();
        $campus->setNom('CHARTRES DE BRETAGNE');
        $manager->persist($campus);
        $manager->flush();
        $campus = new Campus();
        $campus->setNom('LA ROCHE SUR YON.');
        $manager->persist($campus);
        $manager->flush();

        //*************** peuplement de la table user ************//
        $user = new User();
        $user->setEmail('julien@eni.fr');
        $user->setRoles(['ROLE_ADMIN','ROLE_USER']);
        $password = $this->hasher->hashPassword($user, '1234');
        $user->setPassword($password);
        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setEmail('sonia@eni.fr');
        $user->setRoles(['ROLE_USER']);
        $password = $this->hasher->hashPassword($user, '1234');
        $user->setPassword($password);
        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setEmail('michel@eni.fr');
        $user->setRoles(['ROLE_USER']);
        $password = $this->hasher->hashPassword($user, '1234');
        $user->setPassword($password);
        $manager->persist($user);
        $manager->flush();

        //******************* peuplement des villes **************/
        $ville= new Ville();
        $ville->setNom('Saint Herblain');
        $ville->setCodePostal('44800');
        $manager->persist($ville);
        $manager->flush();

        $ville= new Ville();
        $ville->setNom('Herblay');
        $ville->setCodePostal('95220');
        $manager->persist($ville);
        $manager->flush();

        $ville= new Ville();
        $ville->setNom('Cherbourg');
        $ville->setCodePostal('50100');
        $manager->persist($ville);
        $manager->flush();

        //******************* peuplement des lieux **************/

        $lieu= new Lieu();
        $lieu->setNom('parc1');
        $lieu->setRue('la liberté');
        $lieu->setLatitude('35.1');
        $lieu->setLongitude('50.2');
        $ville=$manager->getRepository($ville::class)->find(8);
        $lieu->setRattacheA($ville);
        $manager->persist($lieu);
        $manager->flush();

        $lieu= new Lieu();
        $lieu->setNom('musée');
        $lieu->setRue('la paix');
        $lieu->setLatitude('42.02');
        $lieu->setLongitude('31.22');
        $ville=$manager->getRepository($ville::class)->find(9);
        $lieu->setRattacheA($ville);
        $manager->persist($lieu);
        $manager->flush();


    }
}
