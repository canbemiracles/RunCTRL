<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\BranchStation;
use ApiBundle\Service\AdminPanel\UsersManagementService;
use ApiBundle\Service\Cloudflare\CloudflareSetting;
use ApiBundle\Service\Elastica\SearchService;
use ApiBundle\Service\OpenExchangeRates\CurrencyService;
use ApiBundle\Service\Currency\CurrencyService as CurrencyLayer;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebSocketsBundle\Service\NotificationService;

class DefaultController extends AbstractController
{
    /**
     * @Rest\Get("/")
     * @Rest\View()
     */
    public function indexAction()
    {
        return $this->render('ApiBundle:Default:index.html.twig');
    }
    /**
     * @Rest\Get("/qr_test", name="qr_test")
     * @Rest\View()
     */
    public function qrAction()
    {
        $em = $this->getDoctrine()->getManager();
        /** @var BranchStation */
        $branchStations = $em->getRepository('ApiBundle:BranchStation')->findAll();
        $qrGenerator = $this->get('endroid.qrcode.factory');
        $now = new \DateTime();

        print('current server time: '. $now->format('Y-m-d H:i:s'). '<br><br><br>');
        foreach($branchStations as $station)
        {
            /** @var $station BranchStation*/
            if(!empty($station->getBranch()->getGeographicalArea()->getCountry())) {
                $address = $station->getBranch()->getGeographicalArea()->getCountry()->getName();
            } else {
                $address = '';
            }
            $qrCode = $qrGenerator->create($station->getDeviceCode(), ['size' => 200]);
            print 'Branch address: '.$address.', '
                . $station->getBranch()->getGeographicalArea()->getCity().', '
                . $station->getBranch()->getGeographicalArea()->getStreetAddress() .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            print 'Station: '.$station->getName().'<br>';
            print 'Pin code: '.$station->getPin().'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            if($station->getPinExpire() != null) {
                print 'Valid until: ' . $station->getPinExpire()->format('Y-m-d H:i:s');
            }
            print('<br><img src="'.$qrCode->writeDataUri().'"><br><br><br><br>');
        }
    }

    /**
     * @Rest\Get("/snooze", name="snooze")
     * @Rest\View()
     * @return mixed $response
     */
    public function snoozeAction() {
        return $this->get('service.assignments.manager')->handleNewAndRepeatableAssignments();
    }

    /**
     * @Rest\Get("/device_notification_new", name="device_notification_new")
     * @Rest\View()
     * @return mixed $response
     */
    public function deviceNotificationNewAction() {
        return $this->get('service.device.notifications.manager')->handleDeviceNotifications();
    }

    /**
     * @Rest\Post("/open", name="branch_shift_open")
     * @Rest\View()
     * @return mixed $branchShift
     */
    public function openAction() {
        return $this->get('service.branch_shift.branch_shift_management')->openShift();
    }

    /**
     * @Rest\GET("/close", name="branch_shift_close")
     * @Rest\View()
     * @return mixed $shift
     */
    public function closeAction() {
        return $this->get('service.branch_shift.branch_shift_management')->closeShift();
    }

    /**
     * @Rest\GET("/close_managers", name="branch_shift_close_managers")
     * @Rest\View()
     * @return mixed $shift
     */
    public function closeBranchManagerAction() {
        return $this->get('service.branch_shift.branch_shift_management')->closeOldShiftBranchManagers();
    }

    /**
     * @Rest\GET("/close_employees", name="branch_shift_close_employees")
     * @Rest\View()
     * @return mixed $shift
     */
    public function closeEmployeeAction() {
        return $this->get('service.branch_shift.branch_shift_management')->closeOldShiftEmployees();
    }

    /**
     * @Rest\Post("/search", name="search")
     * @Rest\View()
     * @param Request $request
     * @return Response
     */
    public function searchReports(Request $request)
    {
        /** @var $response SearchService*/
        $response = $this->get('service.elastica.search');
        $response->search(
           'employee',
           'dfdfg',
            $this->getDoctrine()->getManager()->getRepository('ApiBundle:Company')->findOneBy([])
        );
        return $response;
    }

    /**
     * @Rest\Get("/test_fcm", name="test_fcm")
     * @Rest\View()
     * @return Response
     */
    public function testFCM()
    {
        return $this->get('service.device.notifications.manager')->handleDeviceNotifications();
    }

    /**
     * @Rest\Get("/test_check_problem_tasks", name="test_check_problem_tasks")
     * @Rest\View()
     * @return Response
     */
    public function testCheckProblemTasks()
    {
        return $this->get('service.assignments.manager')->checkProblemTasks();
    }

    /**
     * @Rest\Get("/open_exchange_rates", name="open_exchange_rates")
     * @Rest\View()
     * @return mixed
     */
    public function testCurrencyApi()
    {
        /** @var $service CurrencyService*/
        $service = $this->get('service.currency.open_exchange_rates');

        return $service->convertCurrency(10, 'GBP', 'EUR');
    }

    /**
     * @Rest\Get("/currencylayer", name="currencylayer")
     * @Rest\View()
     * @return mixed
     */
    public function testCurrencyLayer()
    {
        /** @var $service CurrencyLayer*/
        $service = $this->get('service.currency.currency_service');

        return $service->convert('USD', 'RUB', 1);
    }

    /**
     * @Rest\Post("/check_ip", name="check_ip")
     * @Rest\View()
     * @param Request $request
     * @return mixed
     */
    public function testCheckIp(Request $request)
    {
        /** @var $service NotificationService*/
        $service = $this->get('service.notification_service');

        return $service->check_ip($this->getUser(), $request->getClientIp());
    }

    /**
     * @Rest\Post("/cloudflare", name="cloudflare")
     * @Rest\View()
     * @param Request $request
     * @return mixed
     */
    public function testCloudflare(Request $request)
    {
        /** @var $service CloudflareSetting*/
        $service = $this->get('service.cloudflare.cloudflare_setting');

        return $service->setting($request->get('method'), $request->get('url'));
    }
}
