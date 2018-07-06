<?php

namespace ApiBundle\Controller\AdminPanel;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Company;
use ApiBundle\Service\AdminPanel\UsersManagementService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * AdminPanel controller.
 *
 * @Route("admin_panel")
 */
class AdminPanelController extends AbstractController
{
    /**
     * @ApiDoc(
     *  section="[AdminPanel] UsersManagement",
     *  description="AdminPanel.",
     *  output="",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/users_management", name="users_management")
     * @Rest\View()
     * @return JsonResponse
     */
    public function getUsersInfoAction()
    {
        /** @var $service UsersManagementService*/
        $service = $this->get('service.admin_panel.users_management');
        return $service->getInfo();

    }

    /**
     * @ApiDoc(
     *  section="[AdminPanel] UserBlock",
     *  description="AdminPanel.",
     *  output="",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/user_block", name="user_block")
     * @Rest\View()
     * @param Request $request
     * @return JsonResponse
     */
    public function userBlockAction(Request $request)
    {
        /** @var $service UsersManagementService*/
        $service = $this->get('service.admin_panel.users_management');
        return $service->userBlock($request->get('user'), $request->get('block'));
    }

    /**
     * @ApiDoc(
     *  section="[AdminPanel] RestPassword",
     *  description="AdminPanel.",
     *  output="",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/reset_password", name="user_block")
     * @Rest\View()
     * @param Request $request
     * @return JsonResponse
     */
    public function reset_password(Request $request)
    {
        /** @var $service UsersManagementService*/
        $service = $this->get('service.admin_panel.users_management');
        return $service->reset_password($request->get('user'));
    }

    /**
     * @ApiDoc(
     *  section="[AdminPanel] Statistic",
     *  description="AdminPanel.",
     *  output="",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/statistic/{id_company}", name="statistic")
     * @ParamConverter("company", options={"mapping": {"id_company" : "id"}})
     * @Rest\View()
     * @param Company $company
     * @return JsonResponse
     */
    public function statistic(Company $company)
    {
        /** @var $service UsersManagementService*/
        $service = $this->get('service.admin_panel.users_management');
        return $service->getStatistic($company);
    }

}
