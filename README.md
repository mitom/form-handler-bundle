form-handler-bundle
===================

The aim of this bundle is to simplify the management and submission of forms and provide a level of abstraction over their submission.
You will possibly gain the following advantages by using it:
 * Fewer dependencies in Controllers
 * Easily testable form handling
 * Extracting business logic from Controllers
 * More re-usable code

It is in an early stage and changes may occur, however the interfaces are unlikely to change. It is recommended that you follow versions, instead of branches (as in `~0.1`).

# Installation

Add it in your `composer.json`:
```json
{
    "require" : {
        "mitom/form-handler-bundle" : "~0.1"
    }
}
```

Then add the bundle in your `AppKernel.php`:
```php
    $bundles = [
        // ...
        new Mitom\Bundle\FormHandlerBundle\FormHandlerBundle()
    ];
```

# Usage

First of you will need a `FormType` to work with, you can create one according to [the official symfony documentation](http://symfony.com/doc/current/book/forms.html#creating-form-classes). For example:
```php
namespace Acme\TaskBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('task')
            ->add('dueDate', null, ['widget' => 'single_text'])
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'task';
    }
}
```

Next you will have to create your `FormHandler` for it. An `AbstractFormHandler` is provided in the bundle to make it easier for you, but of course you don't have to use it. The `FormHandler` only has to implement the `FormHandlerInterface`.

```php
namespace Acme\TaskBundle\Form\Handler;

use Mitom\Bundle\FormHandlerBundle\Handler\AbstractFormHandler;
use Acme\Entity\Task;
use Acme\Entity\Form\Type\TaskType;
use Symfony\Component\Routing\RouterInterface;

class TaskFormHandler extends AbstractFormHandler
{
    private $router;

    public function __construct(RouterInterface $router)
    {
      $this->router = $router;
    }

    /** @inheritDoc */
    public function getType()
    {
        /**
         * In case your FormType is a service, you could just return
         * its' alias here as a string and let the FormFactory create it
         * for you.
         */
        return new TaskType();
    }

    public function onSuccess(FormData $formData)
    {
        /**
         * do whatever you want, like persisting to database
         */

        return new RedirectResponse($this->router->generate('acme.task', ['task' => $formData->getData()->getId()]));
    }

    public function onError(FormData $formData)
    {
        /**
         * do whatever you want, like log the error or simply return the FormData.
         */

        return ['form' => $formData->getForm()->createView()];
    }
}
```

The next step is to register the `FormHandler` as a service and tagging it with `mitom.form_handler`:

```yml
acme_task.task.form_handler:
    class: Acme\TaskBundle\Form\Handler\TaskFormHandler
    arguments:
        - "@router"
    tags:
        - { name: "mitom.form_handler" }
```

In your controller inject the `mitom_form_handler.manager` service:
```yml
acme_task.task.controller:
    class: Acme\TaskBundle\Controller\TaskController
    arguments:
        - "@mitom_form_handler.manager"
```
> Alternatively you could inject a FormHandler straight away, if you only need a single handler in your controller. However I'd recommend going through the Manager anyway, as it makes it easy to user other handlers later and keeps thigs consistent.

And finally make use of it:

```php
namespace Acme\TaskBundle\Controller;

use Mitom\Bundle\FormHandlerBundle\FormData;
use Mitom\Bundle\FormHandlerBundle\FormHandlerManager;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class TaskController
{
    protected $formHandlerManager;

    /**
     * @param FormHandlerManager $formHandlerManager
     */
    public function __construct(FormHandlerManager $formHandlerManager)
    {
        $this->formHandlerManager = $formHandlerManager;
    }


    /**
     * @Template()
     */
    public function newAction()
    {
        return ['form' => $this->formHandlerManager()->getHandler('task')->createForm()->createView()];
    }


    /**
     * @Template()
     */
    public function createAction(Request $request)
    {
        $formData = new FormData();
        $formData->setRequest($request);

        // note that you can get the handler by using the name of the FormType
        return $this->formHandlerManager()->getHandler('task')->handle($formData);
    }
}
```
> The example above is using the `Template` annotation to make it shorter, it is however not a dependency of this bundle.
