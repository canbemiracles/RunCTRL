<?php

namespace ApiBundle\Controller\User;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\Permission;
use ApiBundle\Service\Branch\BranchService;
use ApiBundle\Service\Subscription\SubscriptionLimits;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Permission controller.
 *
 * @Route("permissions")
 */
class PermissionController extends AbstractController
{
    /**
     * @Security("has_role('ROLE_OWNER') or has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  section="Permission",
     *  description="Lists all Permission entities.",
     *  output="ApiBundle\Entity\User\Permission",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="permission_index")
     * @Rest\View()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:User\Permission')->findAll();
    }

    /**
     * @Security("has_role('ROLE_OWNER') or has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  section="Permission",
     *  description="Creates a new Permission entity.",
     *  input="ApiBundle\Form\User\PermissionType",
     *  output="ApiBundle\Entity\User\Permission",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="permission_new")
     * @Rest\View()
     * @param Request $request
     * @return Permission|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $permission = new Permission();

        $form = $this->createForm('ApiBundle\Form\User\PermissionType', $permission);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($permission);
            $em->flush();

            return $permission;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("has_role('ROLE_OWNER') or has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  section="Permission",
     *  description="Finds and displays a Permission entity.",
     *  output="ApiBundle\Entity\User\Permission",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="permission_show")
     * @Rest\View()
     * @param Permission $permission
     * @return Permission
     */
    public function showAction(Permission $permission)
    {

        if(!$permission){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        return $permission;
    }

    /**
     * @Security("has_role('ROLE_OWNER') or has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  section="Permission",
     *  description="Displays a form to edit an existing Permission entity.",
     *  output="ApiBundle\Entity\User\Permission",
     *  input="ApiBundle\Form\User\PermissionType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="permission_put")
     * @Rest\Patch("/{id}", name="permission_patch")
     * @param Request $request
     * @param Permission $permission
     * @return Permission|\Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function editAction(Request $request, Permission $permission)
    {
        $editForm = $this->createForm('ApiBundle\Form\User\PermissionType', $permission, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $permission;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("has_role('ROLE_OWNER') or has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  section="Permission",
     *  description="Deletes a Permission entity.",
     *  output="ApiBundle\Entity\User\Permission",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="permission_delete")
     * @Rest\View()
     * @param Permission $permission
     * @return Response
     */
    public function deleteAction(Permission $permission)
    {

        if(!$permission){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($permission);
        $em->flush();

        return new Response();
    }


    /**
     * @Security("has_role('ROLE_OWNER') or has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  section="Permission",
     *  description="Get data from Permission.",
     *  output="ApiBundle\Entity\User\Permission",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/assign", name="permission_assign")
     * @Rest\View()
     * @param Request $request
     * @return Response
     */
    public function assignAction(Request $request)
    {
        return $this->get('service.permission')->assign(
            $request->get('group'),
            $request->get('permissions')
        );
    }
}
