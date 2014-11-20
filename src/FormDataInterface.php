<?php

namespace Mitom\Bundle\FormHandlerBundle;


use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface FormDataInterface
{
    /**
     * @return FormInterface
     */
    public function getForm();

    /**
     * @param FormInterface $form
     */
    public function setForm(FormInterface $form);

    /**
     * @param Request $request
     */
    public function setRequest(Request $request);

    /**
     * @return Request
     */
    public function getRequest();

    /**
     * @param $data
     */
    public function setData($data);

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @param $key
     * @param $value
     */
    public function setOption($key, $value);

    /**
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param $key
     * @param $value
     */
    public function setParameter($key, $value);

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getParameter($key);
} 