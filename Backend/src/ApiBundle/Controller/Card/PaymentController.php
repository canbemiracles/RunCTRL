<?php

namespace ApiBundle\Controller\Card;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Card\Payment;
use ApiBundle\Entity\User\AbstractUser;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Payum\Core\Payum;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Security\TokenInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Payment controller.
 *
 * @Route("payments")
 */
class PaymentController extends AbstractController
{
    /**
     * @Security("is_granted('payments_payment')")
     * @ApiDoc(
     *  section="[Card] Payment",
     *  description="Payment.",
     *  output="ApiBundle\Entity\Card\Payment",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/payment", name="payments_payment")
     * @Rest\View()
     * @param Request $request
     * @return mixed
     */
    public function paymentAction(Request $request)
    {
        $user = $this->getUser();
        $gatewayName = $request->get("payment_method");

        /** @var $storage Payum*/
        $storage = $this->get('payum');
        $storagePayment = $storage->getStorage('ApiBundle\Entity\Card\Payment');


        $payment = $storagePayment->create();
        $payment->setNumber(uniqid());
        $payment->setCurrencyCode($request->get("currency"));
        $payment->setTotalAmount($request->get("amount"));
        $payment->setDescription('A description');
        $payment->setClientId('anId');
        $payment->setClientEmail($user->getEmail());
        $payment->setUser($user);

        $storagePayment->update($payment);

        /** @var $captureToken TokenInterface*/
        $captureToken = $storage->getTokenFactory()->createCaptureToken(
            $gatewayName,
            $payment,
            $this->getParameter('frontend_url')."/payment?payment_link=".$this->generateUrl("payments_done", [], UrlGeneratorInterface::ABSOLUTE_URL)
        );

        return $captureToken->getTargetUrl();
    }

    /**
     * @Security("is_granted('payments_done')")
     * @ApiDoc(
     *  section="[Card] Payment",
     *  description="Payment.",
     *  output="ApiBundle\Entity\Card\Payment",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/done", name="payments_done")
     * @param Request $request
     * @Rest\View()
     * @return JsonResponse
     */
    public function doneAction(Request $request)
    {
        /** @var $user AbstractUser*/
        $user = $this->getUser();

        /** @var $payum Payum*/
        $payum = $this->get('payum');
        $storagePayment = $payum->getStorage('ApiBundle\Entity\Card\Payment');

        $token = $payum->getHttpRequestVerifier()->verify($request);

        $gateway = $payum->getGateway($token->getGatewayName());

        $gateway->execute($status = new GetHumanStatus($token));
        $payment = $status->getFirstModel();

        if ($status->isCaptured()) {
            $payment->setHasPaid(true);
            $storagePayment->update($payment);

            /** @var $pdf Pdf*/
            $pdf = $this->get('knp_snappy.pdf');

            /** TODO example */
            $html =  $this->renderView(
                'email_confirmation_invite.html.twig',
                array('user' => $this->getUser(), 'company' => 1, 'frontend_url' => '',
                    'type_user' => '', 'confirmationUrl' => '', 'branch' => '', 'system_email' => '')
            );

            $pdf->generateFromHtml(
                $html,
                'invoice.pdf'
            );

            /** @var $mail \Swift_Mailer*/
            $mail = $this->get('swiftmailer.mailer.default');
            $mail->send(
                (new \Swift_Message("Invoice!"))
                    ->setFrom($this->getParameter('mailer_sender_address'))
                    ->setTo($user->getEmail())
                    ->setBody(
                        "INVOICE",
                        'text/html')
                    ->attach(\Swift_Attachment::fromPath('invoice.pdf'))
            );
        }

        return new JsonResponse(array(
            'status' => $status->getValue(),
            'payment' => array(
                'total_amount' => $payment->getTotalAmount(),
                'currency_code' => $payment->getCurrencyCode(),
            ),
        ));
    }

}
