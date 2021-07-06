<?php


namespace App\Controller;


use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * The TaskController is used to render and generate all pages of the website,
 * whose require any kind resources in provenance of the Task entity.
 *
 * Class TaskController
 * @package App\Controller
 * @Route(path="/task")
 */
class TaskController extends AbstractController
{

    /**
     * This property will make a relation between the TaskController and the TaskRepository.
     *
     * It is via this property that __the *TaskController* will be able to retrieve any resources,
     * in provenance of the *Task entity*.__
     *
     * @var TaskRepository
     */
    private $taskRepository;
    /**
     * This property will make a relation between the TaskController and the the EntityManagerInterface.
     *
     * It is via this property that __the *TaskController* will be able to interact with the *Database*.__
     *
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * This property will make a relation between the TaskController and the UserRepository.
     *
     * It is via this property that __the *TaskController* will be able to retrieve any resources,
     * in provenance of the *User entity*.__
     *
     * @var \App\Repository\UserRepository
     */
    private $userRepository;

    /**
     * TaskController constructor.
     *
     * @param \App\Repository\TaskRepository $taskRepository
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @param \App\Repository\UserRepository $userRepository
     */
    public function __construct(TaskRepository $taskRepository, EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->manager = $manager;
        $this->userRepository = $userRepository;
    }

    /**
     * The index() method is used to render the listing pages of all not done yet tasks.
     *
     * It will be called in however ways the user manage to __go to the *"/task" URL*__,
     * it also __use the *taskRepository* property to retrieve only the tasks that hasn't been done yet.__
     *
     * @Route(path="/", name="task.index")
     * @return Response
     */
    public function index(): Response
    {
        $tasks = $this->taskRepository->findBy(['isDone' => 0]);
        return $this->render('task/list.html.twig',
            [
                'tasks' => $tasks
            ]
        );
    }

    /**
     * The finishedTask() method is used to render the listing pages of all already done tasks.
     *
     * It will be called in however ways the user manage to __go to the *"/task/done" URL*__,
     * it also __use the *taskRepository* property to retrieve only the tasks that has already been done.__
     *
     * @Route(path="/done", name="task.done")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function finishedTask(): Response
    {
        $tasks = $this->taskRepository->findBy(['isDone' => 1]);
        return $this->render('task/done.html.twig', [
                'tasks' => $tasks
            ]
        );
    }

    /**
     * The create() method is used to render and handle the task's creation form,
     * but also to create a new Task entity in the Database.
     *
     * It will be called in however ways the user manage to __go to the *"/task/create" URL*__,
     * in the handling of the __*Request*__, __there will be two separate case__ (is the form is submitted or not).
     * In the case where the form hasn't been submitted,
     * the __*create()*__ method will just __render the *task/create.html.twig template*__,
     * where the user will be able to fill the form and submit it.
     * In the case where the form has been submitted,
     * the __*create()*__ method will __create a new *Task entity*__ and assign its column with the form's values,
     * __persist this new *Task entity*__ and __flush the changes into the database.__
     *
     * @Route(path="/create", name="task.create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->userRepository->findOneBy(['username' => $this->getUser()->getUsername()]));
            $task->setCreatedAt(new DateTime());
            $this->manager->persist($task);
            $this->manager->flush();

            $this->addFlash('success', 'La tâche a été correctement ajoutée.');

            return $this->redirectToRoute('task.index');
        }

        return $this->render('task/create.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * The edit() method is used to render and handle the task's edition form,
     * but also to update an existing Task entity in the Database.
     *
     * It will be called in however ways the user manage to __go to the *"/task/edit/{id}" URL*__,
     * in the handling of the __*Request*__, __there will be two separate case__ (is the form submitted or not).
     * In the case where the form hasn't been submitted,
     * the __*edit()*__ method will just __render the *task/edit.html.twig template*__,
     * where the user will be able to fill the form and submit it.
     * In the case where the form has been submitted,
     * the __*edit()*__ method will __retrieve an existing *Task entity* via its *id*__ and assign its column with the form's values,
     * and __flush the changes into the database.__
     *
     * @Route(path="/edit/{id}", name="task.edit")
     * @param Task $task
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

            $this->addFlash('success', 'La tâche a été correctement modifiée.');

            return $this->redirectToRoute('task.index');
        }

        return $this->render('task/edit.html.twig',
            [
                'form' => $form->createView(),
                'task' => $task
            ]
        );
    }

    /**
     * The toggle() method is used to switch the isDone column in the Database from either 1 or 0 to either 0 or 1.
     *
     * It will be called in however ways the user manage to __go to the *"/task/toggle/{id}" URL*__,
     * The __*toggle()*__ method will __retrieve the *Task entity* provided__ in the URL, __to simply update its isDone column.__
     * After the update done and the Database flushed, the __*toggle()*__ method __redirect the user to the Task entity listing.__
     *
     * @Route(path="/toggle/{id}", name="task.toggle")
     * @param Task $task
     * @return RedirectResponse
     */
    public function toggle(Task $task): RedirectResponse
    {
        $task->toggle(!$task->IsDone());
        $this->manager->flush();

        $this->addFlash(
            'success',
            sprintf(
                'La tâche %s a bien été marquée comme faite.',
                $task->getTitle()
            )
        );

        return $this->redirectToRoute('task.index');
    }


    /**
     * The delete() method is used to delete the provided Task entity from the Database.
     *
     * It will be called in however ways the user manage to __go to the *"/task/delete/{id}" URL*__,
     * The __*delete()*__ method will __retrieve the *Task entity* provided__ in the URL, __to simply delete it.__
     * After the deletion done and the Database flushed, the __*delete()*__ method __redirect the user to the Task entity listing.__
     *
     * @Route(path="/delete/{id}", name="task.delete")
     * @param Task $task
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Task $task): RedirectResponse
    {
        $this->manager->remove($task);
        $this->manager->flush();

        $this->addFlash('success', 'La tâche a correctement été supprimée.');

        return $this->redirectToRoute('task.index');
    }
}