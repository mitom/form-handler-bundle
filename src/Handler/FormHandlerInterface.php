<?php

namespace Mitom\Bundle\FormHandlerBundle\Handler;


use Mitom\Bundle\FormHandlerBundle\FormDataInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

Interface FormHandlerInterface
{
    public function handle(FormDataInterface $data);

    /**
     * @return FormTypeInterface|string
     */
    public function getType();

    /**
     * @param       $data
     * @param array $options
     *
     * @return FormInterface
     */
    public function createForm($data, array $options = []);

    /**
     * @param FormDataInterface $data
     *
     * @return mixed
     */
    public function onSuccess(FormDataInterface $data);

    /**
     * @param FormDataInterface $data
     *
     * @return mixed
     */
    public function onError(FormDataInterface $data);

} 