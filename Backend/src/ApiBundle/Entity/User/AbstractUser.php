<?php

namespace ApiBundle\Entity\User;

use ApiBundle\Entity\Security\WhiteCountry;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use WebSocketsBundle\Entity\Notification\AlertNotification;

/**
 * AbstractUser
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\User\AbstractUserRepository")
 * @ORM\AttributeOverrides({
 *              @ORM\AttributeOverride(name="email", column=@ORM\Column(nullable=true)),
 *              @ORM\AttributeOverride(name="emailCanonical", column=@ORM\Column(nullable=true, unique=true)),
 *              @ORM\AttributeOverride(name="username", column=@ORM\Column(nullable=true)),
 *              @ORM\AttributeOverride(name="usernameCanonical", column=@ORM\Column(nullable=true)),
 *              @ORM\AttributeOverride(name="password", column=@ORM\Column(nullable=true))
 * })
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *      "owner" = "Owner",
 *      "admin" = "Admin",
 *      "branch_manager" = "BranchManager",
 *      "co_manager" = "CoManager",
 *      "office_employee" = "OfficeEmployee",
 *      "supervisor" = "Supervisor",
 *      "device" = "ApiBundle\Entity\User\Device\Device"
 *     }
 * )
 */
abstract class AbstractUser extends BaseUser implements TwoFactorInterface
{
    const ROLE_OWNER = "ROLE_OWNER";
    const ROLE_SUPERVISOR = "ROLE_SUPERVISOR";
    const ROLE_BRANCH_MANAGER = "ROLE_BRANCH_MANAGER";
    const ROLE_CO_MANAGER = "ROLE_CO_MANAGER";
    const ROLE_ADMIN = "ROLE_ADMIN";
    const ROLE_OFFICE_EMPLOYEE = "ROLE_OFFICE_EMPLOYEE";
    const ROLE_DEVICE = "ROLE_DEVICE";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * TODO: Remove this field? We are using field "username" for OAuth
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $login;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    protected $first_name;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    protected $last_name;

    /**
     * @ORM\OneToOne(targetEntity="ApiBundle\Entity\PhoneNumber", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="phone_number_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $phone_number;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\OAuth\AccessToken", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $access_tokens;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\OAuth\RefreshToken", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $refresh_tokens;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\OAuth\AuthCode", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $auth_codes;

    /**
     * @ORM\OneToMany(targetEntity="WebSocketsBundle\Entity\Notification\AlertNotification", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $alerts;

    /**
     * @ORM\OneToMany(targetEntity="WebSocketsBundle\Entity\Notification\ReportNotification", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $report_notifications;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\User\Group", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Company", inversedBy="users")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $company;

    /**
     * @ORM\OneToOne(targetEntity="ApiBundle\Entity\File\AvatarFile", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="avatar_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $avatar;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $locale = "en_EN";

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Security\RecentLogin", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $recentLogins;

    /**
     * @ORM\ManyToMany(targetEntity="WebSocketsBundle\Entity\Notification\AnnouncementNotification", mappedBy="users", cascade={"persist", "remove"})
     */
    protected $announcement_notifications;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Card\Card", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $cards;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Security\WhiteCountry", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $whiteCountries;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $social_security_number = null;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $newsletter_notification_announcement;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $newsletter_notification_alert;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $newsletter_notification_report;

    /**
     * @ORM\Column(name="googleAuthenticatorSecret", type="string", nullable=true)
     */
    protected $googleAuthenticatorSecret;

    public function getGoogleAuthenticatorSecret()
    {
        return $this->googleAuthenticatorSecret;
    }

    public function setGoogleAuthenticatorSecret($googleAuthenticatorSecret)
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
    }

    public function is2FA()
    {
        if($this->getGoogleAuthenticatorSecret()) {
            return true;
        }
        return false;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return AbstractUser
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return AbstractUser
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return AbstractUser
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set phoneNumber
     *
     * @param \ApiBundle\Entity\PhoneNumber $phoneNumber
     *
     * @return AbstractUser
     */
    public function setPhoneNumber(\ApiBundle\Entity\PhoneNumber $phoneNumber = null)
    {
        $this->phone_number = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return \ApiBundle\Entity\PhoneNumber
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * Add accessToken
     *
     * @param \ApiBundle\Entity\OAuth\AccessToken $accessToken
     *
     * @return AbstractUser
     */
    public function addAccessToken(\ApiBundle\Entity\OAuth\AccessToken $accessToken)
    {
        $this->access_tokens[] = $accessToken;

        return $this;
    }

    /**
     * Remove accessToken
     *
     * @param \ApiBundle\Entity\OAuth\AccessToken $accessToken
     */
    public function removeAccessToken(\ApiBundle\Entity\OAuth\AccessToken $accessToken)
    {
        $this->access_tokens->removeElement($accessToken);
    }

    /**
     * Get accessTokens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccessTokens()
    {
        return $this->access_tokens;
    }

    /**
     * Get accessToken
     *
     * @return \ApiBundle\Entity\OAuth\AccessToken
     */
    public function getAccessToken($token)
    {
        foreach ($this->getAccessTokens() as $item) {
            /** @var $item \ApiBundle\Entity\OAuth\AccessToken*/
            if($item->getToken() === $token) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Add refreshToken
     *
     * @param \ApiBundle\Entity\OAuth\RefreshToken $refreshToken
     *
     * @return AbstractUser
     */
    public function addRefreshToken(\ApiBundle\Entity\OAuth\RefreshToken $refreshToken)
    {
        $this->refresh_tokens[] = $refreshToken;

        return $this;
    }

    /**
     * Remove refreshToken
     *
     * @param \ApiBundle\Entity\OAuth\RefreshToken $refreshToken
     */
    public function removeRefreshToken(\ApiBundle\Entity\OAuth\RefreshToken $refreshToken)
    {
        $this->refresh_tokens->removeElement($refreshToken);
    }

    /**
     * Get refreshTokens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRefreshTokens()
    {
        return $this->refresh_tokens;
    }

    /**
     * Add authCode
     *
     * @param \ApiBundle\Entity\OAuth\AuthCode $authCode
     *
     * @return AbstractUser
     */
    public function addAuthCode(\ApiBundle\Entity\OAuth\AuthCode $authCode)
    {
        $this->auth_codes[] = $authCode;

        return $this;
    }

    /**
     * Remove authCode
     *
     * @param \ApiBundle\Entity\OAuth\AuthCode $authCode
     */
    public function removeAuthCode(\ApiBundle\Entity\OAuth\AuthCode $authCode)
    {
        $this->auth_codes->removeElement($authCode);
    }

    /**
     * Get authCodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAuthCodes()
    {
        return $this->auth_codes;
    }

    /**
     * Set company
     *
     * @param \ApiBundle\Entity\Company $company
     *
     * @return AbstractUser
     */
    public function setCompany(\ApiBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \ApiBundle\Entity\Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set avatar
     *
     * @param \ApiBundle\Entity\File\AvatarFile $avatar
     *
     * @return AbstractUser
     */
    public function setAvatar(\ApiBundle\Entity\File\AvatarFile $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \ApiBundle\Entity\File\AvatarFile
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set locale
     *
     * @param string $locale
     *
     * @return AbstractUser
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);

        return $this;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmailCanonical($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmailCanonical($email);
        $this->setUsernameCanonical($email);

        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $password = is_null($password) ? '' : $password;
        parent::setPassword($password);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCompanyId()
    {
        if(!$this->getCompany()) {
            return null;
        } else {
            return $this->getCompany()->getId();
        }
    }

    /**
     * Add recentLogin
     *
     * @param \ApiBundle\Entity\Security\RecentLogin $recentLogin
     *
     * @return AbstractUser
     */
    public function addRecentLogin(\ApiBundle\Entity\Security\RecentLogin $recentLogin)
    {
        $this->recentLogins[] = $recentLogin;

        return $this;
    }

    /**
     * Remove recentLogin
     *
     * @param \ApiBundle\Entity\Security\RecentLogin $recentLogin
     */
    public function removeRecentLogin(\ApiBundle\Entity\Security\RecentLogin $recentLogin)
    {
        $this->recentLogins->removeElement($recentLogin);
    }

    /**
     * Get recentLogins
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecentLogins()
    {
        return $this->recentLogins;
    }

    /**
    * Set socialSecurityNumber
    *
    * @param string $socialSecurityNumber
    *
    * @return AbstractUser
    */
    public function setSocialSecurityNumber($socialSecurityNumber)
    {
        $this->social_security_number = $socialSecurityNumber;

        return $this;
    }

    /**
     * Get socialSecurityNumber
     *
     * @return string
     */
    public function getSocialSecurityNumber()
    {
        return $this->social_security_number;
    }

    public function getFullName()
    {
        return "{$this->getLastName()} {$this->getFirstName()}";
    }

    /**
     * Get alerts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlerts()
    {
        return $this->alerts;
    }

    /**
     * Set newsletterNotificationAnnouncement
     *
     * @param boolean $newsletterNotificationAnnouncement
     *
     * @return AbstractUser
     */
    public function setNewsletterNotificationAnnouncement($newsletterNotificationAnnouncement)
    {
        $this->newsletter_notification_announcement = $newsletterNotificationAnnouncement;

        return $this;
    }

    /**
     * Get newsletterNotificationAnnouncement
     *
     * @return boolean
     */
    public function getNewsletterNotificationAnnouncement()
    {
        return $this->newsletter_notification_announcement;
    }

    /**
     * Set newsletterNotificationAlert
     *
     * @param boolean $newsletterNotificationAlert
     *
     * @return AbstractUser
     */
    public function setNewsletterNotificationAlert($newsletterNotificationAlert)
    {
        $this->newsletter_notification_alert = $newsletterNotificationAlert;

        return $this;
    }

    /**
     * Get newsletterNotificationAlert
     *
     * @return boolean
     */
    public function getNewsletterNotificationAlert()
    {
        return $this->newsletter_notification_alert;
    }

    /**
     * Set newsletterNotificationReport
     *
     * @param boolean $newsletterNotificationReport
     *
     * @return AbstractUser
     */
    public function setNewsletterNotificationReport($newsletterNotificationReport)
    {
        $this->newsletter_notification_report = $newsletterNotificationReport;

        return $this;
    }

    /**
     * Get newsletterNotificationReport
     *
     * @return boolean
     */
    public function getNewsletterNotificationReport()
    {
        return $this->newsletter_notification_report;
    }

    /**
     * @return Collection Card
    */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * Add whiteCountry
     *
     * @param \ApiBundle\Entity\Security\WhiteCountry $whiteCountry
     *
     * @return AbstractUser
     */
    public function addWhiteCountry(WhiteCountry $whiteCountry)
    {
        $this->whiteCountries[] = $whiteCountry;

        return $this;
    }

    /**
     * Remove whiteCountry
     *
     * @param \ApiBundle\Entity\Security\WhiteCountry $whiteCountry
     */
    public function removeWhiteCountry(WhiteCountry $whiteCountry)
    {
        $this->whiteCountries->removeElement($whiteCountry);
    }

    /**
     * Get whiteCountry
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWhiteCountries()
    {
        return $this->whiteCountries;
    }
}
