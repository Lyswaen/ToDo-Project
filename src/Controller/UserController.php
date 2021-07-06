<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * The UserController is used to render and generate all pages of the website,
 * whose require any kind resources in provenance of the User entity.
 *
 * Class UserController
 * @package App\Controller
 * @Route(path="/user")
 */
class UserController extends AbstractController
{

    /**
     * This property will make a relation between the UserController and the UserRepository.
     *
     * It is via this property that __the *UserController* will be able to retrieve any resources,
     * in provenance of the *User entity*.__
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * This property will make a relation between the UserController and the the EntityManagerInterface.
     *
     * It is via this property that __the *UserController* will be able to interact with the *Database*.__
     *
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * UserController constructor.
     *
     * @param \App\Repository\UserRepository $userRepository
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $manager)
    {
        $this->userRepository = $userRepository;
        $this->manager = $manager;
    }

    /**
     * The index() method is used to render the listing pages of all users registered in the Database.
     *
     * It will be called in however ways the user manage to __go to the *"/user" URL*__,
     * it also __use the *UserRepository* property to retrieve only the tasks that hasn't been done yet.__
     *
     * @Route(path="/", name="user.index")
     */
    public function index(): Response
    {
        return $this->render('user/list.html.twig',
            [
                'users' => $this->userRepository->findAll()
            ]
        );
    }

    /**
     * The create() method is used to render and handle the user's creation form,
     * but also to create a new User entity in the Database.
     *
     * It will be called in however ways the user manage to __go to the *"/user/create" URL*__,
     * in the handling of the __*Request*__, __there will be two separate case__ (is the form is submitted or not).
     * In the case where the form hasn't been submitted,
     * the __*create()*__ method will just __render the *user/create.html.twig template*__,
     * where the user will be able to fill the form and submit it.
     * In the case where the form has been submitted,
     * the __*create()*__ method will __create a new *User entity*__ and assign its column with the form's values,
     * __persist this new *User entity*__ and __flush the changes into the database.__
     *
     *
     * @Route(path="/create", name="user.create")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     */
    public function create(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setRoles([$request->get('roles')]);
            $user->setPassword($password);

            $this->manager->persist($user);
            $this->manager->flush();

            $this->addFlash('success', 'L\'utilisateur a été correctement ajouté.');

            return $this->redirectToRoute('user.index');
        }

        return $this->render('user/create.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * The edit() method is used to render and handle the user's edition form,
     * but also to update an existing Task entity in the Database.
     *
     * It will be called in however ways the user manage to __go to the *"/user/edit/{id}" URL*__,
     * in the handling of the __*Request*__, __there will be two separate case__ (is the form submitted or not).
     * In the case where the form hasn't been submitted,
     * the __*edit()*__ method will just __render the *user/edit.html.twig template*__,
     * where the user will be able to fill the form and submit it.
     * In the case where the form has been submitted,
     * the __*edit()*__ method will __retrieve an existing *User entity* via its *id*__ and assign its column with the form's values,
     * and __flush the changes into the database.__
     *
     *
     * @Route(path="/edit/{id}", name="user.edit")
     * @param User $user
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     */
    public function edit(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setRoles([$request->get('roles')]);
            $user->setPassword($password);

            $this->manager->flush();

            $this->addFlash('success', "L'utilisateur a été correctment édité.");

            return $this->redirectToRoute("user.index");
        }

        return $this->render('user/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user
            ]
        );
    }
}