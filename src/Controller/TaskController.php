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
 * Class TaskController
 * @package App\Controller
 * @Route("/task")
 */
class TaskController extends AbstractController
{

    /**
     * @var TaskRepository
     */
    private $taskRepository;
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var \App\Repository\UserRepository
     */
    private $userRepository;

    public function __construct(TaskRepository $taskRepository, EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->manager = $manager;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="task.index")
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
     * @Route("/done", name="task.done")
     *
     */
    public function finishedTask()
    {
        $tasks = $this->taskRepository->findBy(['isDone' => 1]);
        return $this->render('task/done.html.twig', [
                'tasks' => $tasks
            ]
        );
    }

    /**
     * @Route("/create", name="task.create")
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
     * @Route("/edit/{id}", name="task.edit")
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
     * @Route("/toggle/{id}", name="task.toggle")
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
     * @Route("/delete/{id}", name="task.delete")
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