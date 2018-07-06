<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractController extends FOSRestController
{
    /**
     * @param FormInterface $form
     *
     * @return JsonResponse
     */
    protected function getJsonErrorsResponse(FormInterface $form)
    {
        $data = [
            'type'   => 'validation_error',
            'title'  => 'There was a validation error',
            'errors' => $this->getErrorsFromForm($form),
        ];

        return new JsonResponse($data, 400);
    }

    /**
     * @param FormInterface $form
     *
     * @return array
     */
    protected function getErrorsFromForm(FormInterface $form)
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface && $childErrors = $this->getErrorsFromForm($childForm)) {
                $errors[ $childForm->getName() ] = $childErrors;
            }
        }

        return $errors;
    }
}
