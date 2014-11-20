<?php

namespace Mitom\Bundle\FormHandlerBundle\Handler;

use Mitom\Bundle\FormHandlerBundle\FormDataInterface;
use Symfony\Component\Form\FormFactoryInterface;

abstract class AbstractFormHandler implements FormHandlerInterface
{
    /**
     * @var FormFactoryInterface $formFactory
     */
    protected $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @inheritDoc
     */
    public function createForm($data = null, array $options = [])
    {
        $data = $data ?: $this->getNewDataInstance();

        return $this->formFactory->create($this->getType(), $data, $options);
    }

    /**
     * @inheritdoc
     */
    public function handle(FormDataInterface $formData)
    {
        $request = $formData->getRequest();
        $data = $formData->getData() ? :$this->getNewDataInstance();
        $options = $formData->getOptions() ? :[];

        if ($formData->getForm()) {
            $form = $formData->getForm();
        } else {
            $form = $this->createForm($data, $options);
            $formData->setForm($form);
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData->setData($form->getData());
            return $this->onSuccess($formData);
        } else {
            return $this->onError($formData);
        }
    }
} 