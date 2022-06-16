<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends Controller
{
    /**
     * @Route("/todos", name="todos")
     */
    public function listAction(Request $request)
    {
        // querying for all todo object
        $todos = $this->getDoctrine()
            ->getRepository('AppBundle:Todo') // access the Todo table in the database
            ->findAll(); // get all todos
        return $this->render('todo/index.html.twig', array(
            'todos' => $todos
        ));
    }
    /**
     * @Route("/todos/create", name="todos_create")
     */
    public function createAction(Request $request)
    {
        $todo = new Todo;

        // creating form template for use input
        // TextType, TextAreaType, ChoiceType, DateTimeType, SubmitType defines the input type
        $form = $this->createFormBuilder($todo)
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('category', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('descrption', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            // for ChoiceType, we need to pass in the list of choices
            ->add('priority', ChoiceType::class, array('choices' => array('Low' => 'low', 'Normal' => 'normal', 'High' => 'high'), 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('due_date', DateTimeType::class, array('attr' => array('class' => 'formcontrol', 'style' => 'margin-bottom:15px')))
            // button for submission
            ->add('save', SubmitType::class, array('label' => 'Create Todo', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # code...
            // die("SUBMITTED");
            // assigning the variables for respective input values
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $descrption = $form['descrption']->getData();
            $priority = $form['priority']->getData();
            $dueDate = $form['due_date']->getData();

            $now = new DateTime('now');

            // setting values to respective column
            // all respective set methods are from Entity\Todo.php
            // note: the entity and reposity are generated from "php bin/console doctrine:schema:update --force"
            // refer https://symfony.com/doc/3.4/doctrine.html#creating-an-entity-class
            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescrption($descrption);
            $todo->setPriority($priority);
            $todo->setDueDate($dueDate);
            $todo->setCreateDate($now);

            // fetch EntityManager
            $em = $this->getDoctrine()->getManager();
            // tells Doctrine to save the todo (no queries yet)
            $em->persist($todo);
            // executes the queries
            $em->flush();

            // https://symfony.com/doc/3.4/controller.html#flash-messages
            $this->addFlash(
                'notice',
                'Todo Added'
            );

            // redirect to the page name specified
            // https://symfony.com/doc/3.4/controller.html#redirecting
            return $this->redirectToRoute('todos');
        }
        // replace this example code with whatever you need
        return $this->render('todo/create.html.twig', array('form' => $form->createView()));
    }
    /**
     * @Route("/todos/edit/{id}", name="todos_edit")
     */
    public function editAction($id, Request $request)
    {
       // querying for todo object by ID
        $todo = $this->getDoctrine()
            ->getRepository(('AppBundle:Todo'))
            ->find(($id));

        $now = new DateTime('now');

        // get the current data using getters
        $todo->setName($todo->getName());
        $todo->setCategory($todo->getCategory());
        $todo->setDescrption($todo->getDescrption());
        $todo->setPriority($todo->getPriority());
        $todo->setDueDate($todo->getDueDate());
        $todo->setCreateDate($now);

        //? how does the setters tight together with the form input value?

        // creating form template for use input
        // TextType, TextAreaType, ChoiceType, DateTimeType, SubmitType defines the input type
        $form = $this->createFormBuilder($todo)
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('category', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('descrption', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            // for ChoiceType, we need to pass in the list of choices
            ->add('priority', ChoiceType::class, array('choices' => array('Low' => 'low', 'Normal' => 'normal', 'High' => 'high'), 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('due_date', DateTimeType::class, array('attr' => array('class' => 'formcontrol', 'style' => 'margin-bottom:15px')))
            // button for submission
            ->add('save', SubmitType::class, array('label' => 'Update Todo', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # code...
            // die("SUBMITTED");
            // assigning the variables for respective input values
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $descrption = $form['descrption']->getData();
            $priority = $form['priority']->getData();
            $dueDate = $form['due_date']->getData();

            $now = new DateTime('now');

            $em = $this->getDoctrine()->getManager();
            $todo = $em->getRepository('AppBundle:Todo')->find(($id));
            // setting values to respective column
            // all respective set methods are from Entity\Todo.php
            // note: the entity and reposity are generated from "php bin/console doctrine:schema:update --force"
            // refer https://symfony.com/doc/3.4/doctrine.html#creating-an-entity-class
            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescrption($descrption);
            $todo->setPriority($priority);
            $todo->setDueDate($dueDate);
            $todo->setCreateDate($now);

            $em->flush();

            // https://symfony.com/doc/3.4/controller.html#flash-messages
            $this->addFlash(
                'notice',
                'Todo Added'
            );

            // redirect to the page name specified
            // https://symfony.com/doc/3.4/controller.html#redirecting
            return $this->redirectToRoute('todos');
        }
        // replace this example code with whatever you need
        return $this->render('todo/edit.html.twig', array('todo' => $todo, 'form' => $form->createView()));
    }
    /**
     * @Route("/todos/details/{id}", name="todos_details")
     */
    public function detailsAction($id)
    {
        // querying for todo object by ID
        $todo = $this->getDoctrine()
            ->getRepository(('AppBundle:Todo'))
            ->find(($id));
        // replace this example code with whatever you need
        return $this->render('todo/details.html.twig', array('todo' => $todo));
    }
    /**
     * @Route("/todos/delete/{id}")
     */
    public function deleteAction($id)
    {   
        // fetch EntityManager
        $em = $this->getDoctrine()->getManager();
        // fetch todo by ID
        $todo = $em->getRepository('AppBundle:Todo')->find(($id));

        // deleting the specified todo object
        $em->remove($todo);
        // execute the queries
        $em->flush();

        // https://symfony.com/doc/3.4/controller.html#flash-messages
        $this->addFlash(
            'notice',
            'Todo removed'
        );

        // redirect to the page name specified
        // https://symfony.com/doc/3.4/controller.html#redirecting
        return $this->redirectToRoute('todos');
    }
}
