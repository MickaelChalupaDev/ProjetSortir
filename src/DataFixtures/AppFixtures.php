<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use DateTimeZone;
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
        $etat->setLibelle('Passée');
        $manager->persist($etat);
        $manager->flush();
        $etat = new Etat();
        $etat->setLibelle('Annulée');
        $manager->persist($etat);
        $manager->flush();
        $etat = new Etat();
        $etat->setLibelle('Historique');
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
        $campus= new Campus();
        $campus=$manager->getRepository($campus::class)->findOneBy(['nom'=>'SAINT HERBLAIN']);
        $user->setCampus($campus);
        $user->setNom('Dupont');
        $user->setPrenom('julien');
        $user->setTelephone('0101010101');
        $user->setActif(0);
        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setEmail('sonia@eni.fr');
        $user->setRoles(['ROLE_USER']);
        $password = $this->hasher->hashPassword($user, '1234');
        $user->setPassword($password);
        $campus= new Campus();
        $campus=$manager->getRepository($campus::class)->findOneBy(['nom'=>'SAINT HERBLAIN']);
        $user->setCampus($campus);
        $user->setNom('Joseph');
        $user->setPrenom('Sonia');
        $user->setTelephone('0202020202');
        $user->setActif(0);
        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setEmail('michel@eni.fr');
        $user->setRoles(['ROLE_USER']);
        $password = $this->hasher->hashPassword($user, '1234');
        $user->setPassword($password);
        $campus= new Campus();
        $campus=$manager->getRepository($campus::class)->findOneBy(['nom'=>'CHARTRES DE BRETAGNE']);
        $user->setCampus($campus);
        $user->setNom('Patrick');
        $user->setPrenom('Michel');
        $user->setTelephone('0303030303');
        $user->setActif(0);
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

        $ville=$manager->getRepository($ville::class)->findOneBy(['nom'=>'Herblay']);

        $lieu->setVille($ville);
        $manager->persist($lieu);
        $manager->flush();

        $lieu= new Lieu();
        $lieu->setNom('musée');
        $lieu->setRue('la paix');
        $lieu->setLatitude('42.02');
        $lieu->setLongitude('31.22');
        $ville= new Ville();
        $ville=$manager->getRepository($ville::class)->findOneBy(['nom'=>'Cherbourg']);

        $lieu->setVille($ville);
        $manager->persist($lieu);
        $manager->flush();

        //******************* peuplement des sorties **************/

        $sortie= new Sortie();
        $organisateur= new User();
        $organisateur=$manager->getRepository($organisateur::class)->findOneBy(['prenom'=>'Sonia']);
        $sortie->setOrganisateur($organisateur);
        $campus= new Campus();
        $campus=$manager->getRepository($campus::class)->findOneBy(['nom'=>'CHARTRES DE BRETAGNE']);
        $sortie->setCampus($campus);
        $lieu= new Lieu();
        $lieu=$manager->getRepository($lieu::class)->findOneBy(['nom'=>'parc1']);
        $sortie->setLieu($lieu);
        $etat= new Etat();
        $etat=$manager->getRepository($etat::class)->findOneBy(['libelle'=>'Créée']);
        $sortie->setEtat($etat);
        $sortie->setNom('Balade');
        $sortie->setDuree(120);
        $sortie->setDateHeureDebut(new \DateTime("2022-02-23 00:00:00"));
        $sortie->setDateLimiteInscription(new \DateTime("2022-02-22 18:00:00"));
        $sortie->setNbInscriptionsMax(22);
        $sortie->setInfosSortie('visite guidée');
        $user=$manager->getRepository(User::class)->findOneBy(['prenom'=>'Sonia']);
        $sortie->addUser($user);
        $user=$manager->getRepository(User::class)->findOneBy(['prenom'=>'julien']);
        $sortie->addUser($user);
        $user=$manager->getRepository(User::class)->findOneBy(['prenom'=>'Michel']);
        $sortie->addUser($user);
        $manager->persist($sortie);
        $manager->flush();

        $sortie= new Sortie();
        $organisateur= new User();
        $organisateur=$manager->getRepository($organisateur::class)->findOneBy(['prenom'=>'Michel']);
        $sortie->setOrganisateur($organisateur);
        $campus= new Campus();
        $campus=$manager->getRepository($campus::class)->findOneBy(['nom'=>'LA ROCHE SUR YON.']);
        $sortie->setCampus($campus);
        $lieu= new Lieu();
        $lieu=$manager->getRepository($lieu::class)->findOneBy(['nom'=>'musée']);
        $sortie->setLieu($lieu);
        $etat= new Etat();
        $etat=$manager->getRepository($etat::class)->findOneBy(['libelle'=>'Ouverte']);
        $sortie->setEtat($etat);
        $sortie->setNom('Visite');
        $sortie->setDuree(60);
        $sortie->setDateHeureDebut(new \DateTime("2022-02-23 00:00:00"));
        $sortie->setDateLimiteInscription(new \DateTime("2022-02-22 18:00:00"));
        $sortie->setNbInscriptionsMax(20);
        $sortie->setInfosSortie('visite guidée');
        $manager->persist($sortie);
        $manager->flush();





    }
}
