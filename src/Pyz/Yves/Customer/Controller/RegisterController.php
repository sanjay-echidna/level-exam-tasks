<?php

namespace Pyz\Yves\Customer\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Yves\Customer\CustomerFactory;
use Pyz\Yves\Customer\Plugin\Provider\CustomerControllerProvider;
use Spryker\Client\Customer\CustomerClientInterface;
use Spryker\Shared\Customer\Code\Messages;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CustomerFactory getFactory()
 * @method CustomerClientInterface getClient()
 */
class RegisterController extends AbstractCustomerController
{

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        if ($this->isLoggedInCustomer()) {
            return $this->redirectResponseInternal(CustomerControllerProvider::ROUTE_CUSTOMER_OVERVIEW);
        }

        $registerForm = $this
            ->buildForm($this->getFactory()->createFormRegister())
            ->handleRequest($request);

        if ($registerForm->isValid()) {
            $customerResponseTransfer = $this->registerCustomer($registerForm->getData());

            if ($customerResponseTransfer->getIsSuccess()) {
                $this->loginAfterSuccessfulRegistration($customerResponseTransfer->getCustomerTransfer());

                $this->addSuccessMessage(Messages::CUSTOMER_AUTHORIZATION_SUCCESS);

                return $this->redirectResponseInternal(CustomerControllerProvider::ROUTE_CUSTOMER_OVERVIEW);
            }

            foreach ($customerResponseTransfer->getErrors() as $customerErrorTransfer) {
                $this->addErrorMessage($customerErrorTransfer->getMessage());
            }
        }

        $loginForm = $this->buildForm(
            $this->getFactory()->createFormLogin()
        );

        return $this->viewResponse([
            'loginForm' => $loginForm->createView(),
            'registerForm' => $registerForm->createView(),
        ]);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function registerCustomer(CustomerTransfer $customerTransfer)
    {
        $customerResponseTransfer = $this
            ->getClient()
            ->registerCustomer($customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function loginAfterSuccessfulRegistration(CustomerTransfer $customerTransfer)
    {
        $token = $this->getFactory()->createUsernamePasswordToken($customerTransfer);
        $this->getSecurityContext()->setToken($token);

        $this->getClient()->setCustomer($customerTransfer);
    }

}
