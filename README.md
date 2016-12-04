# ArchitectBundle

This Bundle provides an architecture to separate the different job layers in your Symfony application.

## 1. Installation

### Prerequisites

This bundle requires Symfony >= 2.7

### Step 1: Download ArchitectBundle using composer

Require the bundle with composer:

``` bash
$ composer require mb-x/architect-bundle
```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Mbx\ArchitectBundle\MbxArchitectBundle(),
    );
}
```

## 2. Usage

### Step 1: Implement EntityInterface

First, your entity class should implement EntityInterface and getId method
``` php
<?php
namespace AppBundle\Entity;

use Mbx\ArchitectBundle\Interfaces\EntityInterface;

class Post implements EntityInterface
{
    // ...
    
    public function getId()
    {
        return $this->id;
    }
    
    // ...
}
```

### Step 2: Create the Manager and FormHandler classes

``` bash
$ php app/console mbx:generate AppBundle:Post
```

this command will generate the Manager and FormHandler classes for Post Entity

### Step 3: Registering your Manager and FormHandler classes as a Service

```yaml
appbundle.post_manager:
    class: AppBundle\Manager\PostManager
    parent: mbx.abstract_entity_manager

appbundle.post_form_handler:
    class: AppBundle\FormHandler\PostFormHandler
    parent: mbx.abstract_form_handler
    arguments: ['@appbundle.post_manager']
    scope: request
```
### Step 4: The controller

Your controller will contain less code because all the logic and operations will be done in your Manager and FormHandler classes

``` php
    /**
     * Lists all post entities.
     *
     * @Route("/", name="post_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $posts = $this->get('appbundle.post_manager')->getRepository()->findAll();

        return $this->render('post/index.html.twig', array(
            'posts' => $posts,
        ));
    }

    /**
     * Creates a new post entity.
     *
     * @Route("/new", name="post_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $post = new Post();
        $formHandler = $this->get('appbundle.post_form_handler');

        if ($formHandler->processForm($post)) {
            return $this->redirectToRoute('post_show', array('id' => $post->getId()));
        }

        return $this->render('post/new.html.twig', array(
            'post' => $post,
            'form' => $formHandler->getForm()->createView(),
        ));
    }

    /**
     * Finds and displays a post entity.
     *
     * @Route("/{id}", name="post_show")
     * @Method("GET")
     */
    public function showAction(Post $post)
    {
        $formHandler = $this->get('appbundle.post_form_handler');
        return $this->render('post/show.html.twig', array(
            'post' => $post,
            'delete_form' => $formHandler->createDeleteForm($post)->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing post entity.
     *
     * @Route("/{id}/edit", name="post_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Post $post)
    {
        $formHandler = $this->get('appbundle.post_form_handler');
        if ($formHandler->processForm($post)) {
            return $this->redirectToRoute('post_edit', array('id' => $post->getId()));
        }

        return $this->render('post/edit.html.twig', array(
            'post' => $post,
            'edit_form' => $formHandler->getForm()->createView(),
            'delete_form' => $formHandler->createDeleteForm($post)->createView(),
        ));
    }

    /**
     * Deletes a post entity.
     *
     * @Route("/{id}", name="post_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Post $post)
    {
        $formHandler = $this->get('appbundle.post_form_handler');
        if ($formHandler->processDeleteForm($post)) {
            // $this->get('session')->getFlashBag()->add('Deleted Successfully');
        }

        return $this->redirectToRoute('post_index');
    }
```
## 3. Example

[Demo Application](https://github.com/mb-x/ArchitectBundleDemo)

## 4. Suggestions 

Much like every other piece of software `MbxArchitectBundle` is not perfect.
I will appreciate any suggestion that can improve or add features to this bundle.

## 5. Reporting an issue or a feature request

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/mb-x/architect-bundle/issues).

## 6. Friendly License

This bundle is available under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

You are free to use, modify and distribute this software, as long as the copyright header is left intact (specifically the comment block which starts with /*)!
